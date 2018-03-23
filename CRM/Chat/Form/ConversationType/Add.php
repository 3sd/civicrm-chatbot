<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Chat_Form_ConversationType_Add extends CRM_Core_Form {

  function buildQuickForm() {

    $this->formFields = [
      [
        'name' => 'ChatConversationType.name',
        'html_type' => 'text',
        'title' => 'Name',
        'help_text' => 'A descriptive name for the conversation',
        'required' => true,
      ],
      [
        'name' => 'ChatQuestion.text',
        'html_type' => 'text',
        'title' => 'First question',
        'help_text' => 'The first question that will be asked when this conversation starts',
        'required' => true,
      ],
      [
        'name' => 'ChatConversationType.timeout',
        'html_type' => 'text',
        'title' => 'Timeout',
        'help_text' => 'The amount of time in minutes a recipient has to reply to the convesation.',
        'default' => '30',
        'required' => true,
      ]
    ];
    $this->addFields();
    $this->addButtons([
      array('type' => 'cancel', 'name' => 'Cancel'),
      array('type' => 'submit', 'name' => 'Add', 'isDefault' => TRUE)
    ]);
    $this->assign('formGroups', $this->getFormGroups());
    $this->assign('help', $this->help);
    parent::buildQuickForm();

  }

  function addFields(){
    foreach($this->formFields as $field){
      $this->addFormField($field);
      if(isset($field['help_text'])){
        $this->addHelp('field', $field['name'], $field['help_text']);
      }
    }

  }

  function addFormField($element) {
    switch ($element['html_type']) {
        case 'text':
          $element = $this->addElement(
            'text',
            $element['name'],
            ts($element['title']),
            1
          );
          if(isset($element->_attributes['class'])){
            $element->_attributes['class'] .= ' form-control';
          }else{
            $element->_attributes['class'] = 'form-control';
          }

          break;

        default:
          # code...
          break;
    }
  }

  function addHelp($type, $key, $text){
    $this->help[$type][$key] = $text;
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

  function postProcess(){

    $submitted = $this->exportValues();
    var_dump($submitted);
    exit;
    // Set destination to newly created conversation
  }

}
