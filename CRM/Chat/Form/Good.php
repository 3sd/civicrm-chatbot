<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
abstract class CRM_Chat_Form_Good extends CRM_Core_Form {

  var $help = [];

  var $fields = [];

  var $entities = [];

  var $submitText = 'Save';

  function getGoodTitle(){
  }

  function getSaveMessage(){
    return ts('The form has been saved.');
  }

  function initFields(){
  }

  function initEntities(){
  }

  function getFields(){
    return $this->fields;
  }

  function getEntities(){
    return $this->entities;
  }

  function getButtons(){
    $buttons = [
      [
        'type' => 'submit',
        'name' => ts($this->submitText),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ]
    ];

    return $buttons;
  }

  function preProcess() {

    $this->initEntities();

    foreach($this->entities as &$entity) {
      $entity['fields'] = civicrm_api3($entity['type'], 'getfields', ['action' => 'create'])['values'];
      if(isset($entity['param'])){
        $entityId = CRM_Utils_Request::retrieve($entity['param'], 'String', $this);
        $entity['before'] = civicrm_api3($entity['type'], 'getsingle', ['id' => $entityId]);
      }
      if(isset($entity['references'])){
        foreach($entity['references'] as $field => $reference){
          if(isset($entity['before'][$field])){
            $this->entities[$reference['entity']]['before'] = civicrm_api3($reference['entity'], 'getsingle', [$reference['field'] => $entity['before'][$field]]);
          }
        }
      }
    }

    $this->initFields();
    foreach($this->fields as &$field) {
      $field['name'] = "{$field['entity']}:{$field['field']}";
    }

    $this->preProcessMassage();

  }

  function preProcessMassage(){

  }

  function buildQuickForm() {

    $this->addFields();

    $this->assign( 'help', $this->help);

    if($title = $this->getGoodTitle()){
      CRM_Utils_System::setTitle($title);
    }

    $this->addButtons($this->getButtons());
    $this->assign('delete', $this->getDelete());
    $this->addButtons($this->getButtons());

    $session = CRM_Core_Session::singleton();
    $session->pushUserContext($this->getContext());

  }

  function getDelete(){
    return false;
  }

  function addFields() {

    foreach($this->getFields() as $field){

      if(!isset($field['required'])) {
        $field['required'] =  false;
      }
      if(isset($field['type'])) {
        $type = $field['type'];
      }elseif(isset($this->entities[$field['entity']]['fields'][$field['field']]['html']['type'])) {
        $type = $this->entities[$field['entity']]['fields'][$field['field']]['html']['type'];
      }else{
        $type = 'Text';
      }

      switch (strtolower($type)) {

        case 'text':
          $element = $this->add(
            'text',
            $field['name'],
            $field['title'],
            ['class' => 'form-control'],
            $field['required']
          );
          break;

        case 'select':
          $element = $this->add(
            'select',
            $field['name'],
            $field['title'],
            $field['options'],
            $field['required'],
            ['class' => 'form-control crm-form-select']
          );
          break;

        case 'entityref':
          $props = [
            'class' => 'form-control crm-form-select',
          ];
          if(isset($field['entityref_api'])){
            $props['api'] = $field['entityref_api'];
          }
          if(isset($field['entityref_entity'])){
            $props['entity'] = $field['entityref_entity'];
          }else{
            $props['entity'] = $this->entities[$field['entity']]['fields'][$field['field']]['FKApiName'];
          }
          $element = $this->addEntityRef(
            $field['name'],
            $field['title'],
            $props,
            $field['required']
          );
          break;
      }

      if(isset($field['help'])) {
        $this->addHelp('field', $field['name'], $field['help']);
      }

    }
    // exit;

    $this->assign( 'fields', array_map(function($field){ return $field['name']; }, $this->getFields()));
  }

  function addHelp($type, $key, $text, $level = 'info') {

    $this->help[$type][$key] = ['text' => $text, 'level' => $level];

  }

  function setDefaultValues(){

    $defaults = [];

    foreach($this->getFields() as $field){

      if(isset($this->entities[$field['entity']]['before'][$field['field']])){
        $defaults[$field['name']] = $this->entities[$field['entity']]['before'][$field['field']];
      }
    }

    return $defaults;

  }

  function postProcess() {

    $submitted = $this->exportValues();

    $submitted = $this->postProcessMassage($submitted);

    foreach($this->entities as &$entity) {

      if(isset($entity['process']) && $entity['process'] === false){
        continue;
      }

      $params = [];

      if(isset($entity['before']['id'])){
        $params['id'] = $entity['before']['id'];
      }

      foreach($this->getFields() as $field) {
        if($field['entity'] == $entity['type']) {
          if(isset($submitted[$field['name']])){
            $params[$field['field']] = $submitted[$field['name']];
          }
        }
      }

      if(isset($entity['references'])){
        foreach($entity['references'] as $field => $reference) {
          if(isset($this->entities[$reference['entity']]['after'][$reference['field']])) {
            $params[$field] = $this->entities[$reference['entity']]['after'][$reference['field']];
          }elseif(isset($this->entities[$reference['entity']]['before'][$reference['field']])) {
            $params[$field] = $this->entities[$reference['entity']]['before'][$reference['field']];
          }
        }
      }

      $result = civicrm_api3($entity['type'], 'create', $params);
      $entity['after'] = $result['values'][$result['id']];

    }

    CRM_Core_Session::setStatus($this->getSaveMessage());

    $this->controller->_destination = $this->getDestination();

  }

  function postProcessMassage($submitted){
    return $submitted;
  }

  abstract function getDestination();

  abstract function getContext();

  function getTemplateFileName() {
    return 'CRM/Chat/Form/Good.tpl';
  }

}
