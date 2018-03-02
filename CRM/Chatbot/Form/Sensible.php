<?php
abstract class CRM_Chatbot_Form_Sensible extends CRM_Core_Form {

  var $fields = [];

  var $help = [];

  var $saveMessages = [];

  function preProcess() {
    $this->init();
    $this->loadFields();
  }

  abstract function init();

  abstract function getDestination();

  /**
   * Loads meta data etc. for defined fields for use later on
   */
  function loadFields(){
    foreach ($this->fields as $field) {
      if(isset($field['entity'])){
        // Load default
        $this->fields[$field['field']]['value'] = civicrm_api3($field['entity'], 'getvalue', ['name' => $field['field']]);

        // Get metadata
        $result = civicrm_api3($field['entity'], 'getfields', ['name' => $field['field']]);
        $this->fields[$field['field']]['metadata'] = $result['values'][$result['id']];

        if (isset($this->fields[$field['field']]['result']['pseudoconstant'])) {
          $this->fields[$field['field']]['result']['options'] = civicrm_api3('Setting', 'getoptions', array( 'field' => $field['name']));
        }

        if (!isset($this->fields[$field['field']]['id'])) {
          $this->fields[$field['field']]['id'] = '';
        }
      }
    }
  }

  function buildQuickForm() {

    $this->addFields();
    $this->addButtons($this->getButtons());
    $this->setDestination($this->getDestination());
    $this->bootstrapify($this->getFormGroups());
    $this->assign('formGroups', $this->getFormGroups());
    $this->assign('help', $this->getHelp());

  }


  function addFields(){

    foreach ($this->fields as $field) {

      // Call appropriate function to add element
      if(isset($field['entity'])){
        $element = $this->addEntityField($field['metadata']);
      }else{
        //TODO
      }

      // Freeze read only elements
      if(isset($field['freeze']) && $field['freeze']){
        $element->freeze();
      }

      // Add field help
      if(isset($field['help_text'])){
        $this->addHelp('field', $field['field'], $field['help_text']);
      }elseif(isset($field['metadata']['help_text'])){
        $this->addHelp('field', $field['field'], $field['metadata']['help_text']);
      }
    }
  }

  function addEntityField($field){

    $elementClass = 'add' . $field['quick_form_type'];

    switch ($elementClass) {

      case 'addElement':
        $element = $this->$elementClass(
          $field['html_type'],
          $field['name'],
          ts($field['title']),
          (isset($field['options'])) ? $field['options']['values'] : CRM_Utils_Array::value('html_attributes', $field, array()),
          (isset($field['options'])) ? CRM_Utils_Array::value('html_attributes', $field, array()) : NULL
        );
        break;

      case 'addSelect':
        $element = $this->addElement('select', $field['name'], ts($field['title']), $field['options']['values'], CRM_Utils_Array::value('html_attributes', $field));
        break;

      case 'addCheckBox':
        $element = $this->addCheckBox($field['name'], ts($field['title']), $field['options']['values'], NULL, CRM_Utils_Array::value('html_attributes', $field), NULL, NULL, array('&nbsp;&nbsp;'));
        break;

      case 'addChainSelect':
        $element = $this->addChainSelect($field['name'], array( 'label' => ts($field['title']) ));
        break;

      case 'addMonthDay':
        $element = $this->add('date', $field['name'], ts($field['title']), CRM_Core_SelectValues::date(NULL, 'M d'));
        break;

      default:
        $element = $this->$elementClass($field['name'], ts($field['title']));
    }
    return $element;
  }

  function addHelp($type, $key, $text){
    $this->help[$type][$key] = $text;
  }

  function addSaveMessage($entity, $id, $text){
    $this->saveMessages[$entity][$id] = $text;
  }

  function getSaveMessage($entity, $id){
    return $this->saveMessages[$entity][$id];
  }

  function getButtons(){
    return [
      [
        'type' => 'next',
        'name' => ts('Save'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ]
    ];
  }

  function setDestination($destination){
    $this->controller->_destination = $destination;
  }

  function getHelp(){
    return $this->help;
  }

  function bootstrapify(){
    foreach ($this->_elements as $element) {
      if($element->_type == 'text'){
        if(isset($element->_attributes['class'])){
          $element->_attributes['class'] .= ' form-control';
        }else{
          $element->_attributes['class'] = 'form-control';
        }
      }
    }
  }

  function getFormGroups(){
    $formGroups = [];
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $formGroups[] = $element->getName();
      }
    }
    return $formGroups;
  }

  function setDefaultValues() {
    foreach ($this->fields as $field['field'] => $field) {
      if(isset($this->defaultValueCallbacks[$field['field']])){
        call_user_func([$this, $this->defaultValueCallbacks[$field['field']]]);
      }
      $defaults[$field['field']] = $this->fields[$field['field']]['value'];
    }
    return $defaults;
  }

  function postProcess(){

    $submitted = $this->exportValues();

    foreach($this->fields as $field){
      if(isset($field['entity'])){
        $create[$field['entity']][$field['id']][$field['field']] = $submitted[$field['field']];
      }
    }

    $session = CRM_Core_Session::singleton();

    foreach($create as $entity => $instance){
      foreach($instance as $id => $params){
        if($id){
          $params['id'] = $id;
        }
        civicrm_api3($entity, 'create', $params);
        $session->setStatus($this->getSaveMessage($entity, $id));
      }
    }
  }

}
