<?php
/**
 * Class for CiviRules Group Contact Action Form
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Chatbot_Rules_Actions_Form_StartConversation extends CRM_CivirulesActions_Form_Form {


  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_action_id');
    $this->addEntityRef('conversationTypeId', ts('Conversation type'), [
      'entity' => 'ChatConversationType',
      'api' => ['label_field' => 'name'],
      'placeholder' => ts('- Select conversation -'),
      'select' => ['minimumInputLength' => 0]
    ], TRUE);
    $this->add( 'select', 'service', 'Chat service', CRM_Chat_Botman::getAllServices(), true, ['class' => 'form-control crm-form-select']);
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $defaultValues = parent::setDefaultValues();
    $data = unserialize($this->ruleAction->action_params);
    if (!empty($data['conversation_type_id'])) {
      $defaultValues['conversationTypeId'] = $data['conversation_type_id'];
    }
    if (!empty($data['service'])) {
      $defaultValues['service'] = $data['service'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to process form data after submitting
   *
   * @access public
   */
  public function postProcess() {
    $data['conversation_type_id'] = $this->_submitValues['conversationTypeId'];
    $data['service'] = $this->_submitValues['service'];
    $this->ruleAction->action_params = serialize($data);
    $this->ruleAction->save();
    parent::postProcess();
  }

}
