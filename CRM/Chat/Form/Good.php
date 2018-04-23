<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
abstract class CRM_Chat_Form_Good extends CRM_Core_Form {

  var $help = [];

  function getTitle2(){
    return $this->title;
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

    foreach($this->entities as &$entity) {
      $entity['fields'] = civicrm_api3($entity['type'], 'getfields', ['action' => 'create'])['values'];
      if(isset($entity['param'])){
        $entityId = CRM_Utils_Request::retrieve($entity['param'], 'String', $this);
        $entity['before'] = civicrm_api3($entity['type'], 'getsingle', ['id' => $entityId]);
      }
    }

    foreach($this->fields as &$field) {
      $field['name'] = "{$field['entity']}:{$field['field']}";
    }

    foreach($this->entities as &$entity) {
    }

  }

  function buildQuickForm() {

    $this->addFields();

    $this->assign( 'help', $this->help);

    CRM_Utils_System::setTitle($this->getGoodTitle());

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

    foreach($this->fields as $field){

      if(isset($field['type'])) {
        $type = $field['type'];
      }elseif(isset($this->entities[$field['entity']]['fields'][$field['field']]['html']['type'])) {
        $type = $this->entities[$field['entity']]['fields'][$field['field']]['html']['type'];
      }else{
        $type = 'Text';
      }

      switch (strtolower($type)) {

        case 'text':
          $element = $this->addElement(
            'text',
            $field['name'],
            $field['title'],
            ['class' => 'form-control']
          );
          break;

        case 'select':
          $element = $this->addElement(
            'select',
            $field['name'],
            $field['title'],
            $field['options']
          );
          break;

        case 'entityref':
          $element = $this->addEntityRef(
            $field['name'],
            $field['title'],
            [
              'entity' => $this->entities[$field['entity']]['fields'][$field['field']]['FKApiName'],
              'class' => 'form-control crm-form-select',
              'api' => $field['entityref_api']
            ]
          );
          break;
      }
      $this->addHelp('field', $field['name'], $field['help']);

    }
    // exit;

    $this->assign( 'fields', array_map(function($field){ return $field['name']; }, $this->fields));
  }

  function addHelp($type, $key, $text, $level = 'info') {

    $this->help[$type][$key] = ['text' => $text, 'level' => $level];

  }

  function setDefaultValues(){

    $defaults = [];

    foreach($this->fields as $field){

      if(isset($this->entities[$field['entity']]['before'][$field['field']])){
        $defaults[$field['name']] = $this->entities[$field['entity']]['before'][$field['field']];
      }
    }

    return $defaults;

  }

  function postProcess() {

    $submitted = $this->exportValues();

    foreach($this->entities as &$entity) {

      $params = [];

      if(isset($entity['before']['id'])){
        $params['id'] = $entity['before']['id'];
      }

      foreach($this->fields as $field) {
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

    $this->controller->_destination = $this->getDestination();

  }

  abstract function getDestination();

  abstract function getContext();

  function getTemplateFileName() {
    return 'CRM/Chat/Form/Good.tpl';
  }

}
