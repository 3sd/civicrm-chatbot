<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
abstract class CRM_Chat_Form_Good_Delete extends CRM_Chat_Form_Good {

  var $entities = [];

  var $fields = [];

  var $submitText = 'Delete';

  function buildQuickForm() {
    $description = $this->getDescription();
    $this->addHelp('form', 'top', "Are you sure you want to delete the {$this->deleteEntityText} '{$description}'", 'warning');
    parent::buildQuickForm();
  }

  function getDescription(){
    return reset($this->entities)['before'][$this->deleteEntityLabelField];
  }

  function postProcess() {

    foreach($this->entities as &$entity) {

      if(isset($entity['process']) && $entity['process'] === false){
        continue;
      }

      $result = civicrm_api3($entity['type'], 'delete', ['id' => $entity['before']['id']]);
    }

    $this->controller->_destination = $this->getDestination();

  }

}
