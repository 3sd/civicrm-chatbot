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

  function getTitle(){
      return 'Delete';
  }

  function buildQuickForm() {
    $label = reset($this->entities)['before'][$this->deleteEntityLabelField];
    $this->addHelp('form', 'top', "Are you sure you want to delete {$this->deleteEntityText} '{$label}'", 'warning');
    parent::buildQuickForm();
  }

  function postProcess() {

    foreach($this->entities as &$entity) {
      $result = civicrm_api3($entity['type'], 'delete', ['id' => $entity['before']['id']]);
    }

    $this->controller->_destination = $this->getDestination();

  }

}
