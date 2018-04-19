<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_Page_ConversationType_View extends CRM_Core_Page {

  public function run() {

    // TODO Implement paging so we can display more that 25 conversation types :)

    $id = CRM_Utils_Request::retrieve('id', 'Positive', $this);

    $conversationType = civicrm_api3('ChatConversationType', 'getsingle', ['id' => $id]);

    CRM_Utils_System::setTitle(E::ts('Conversation type: %1', [$conversationType['name']]));

    $this->assign('conversationType', $conversationType);

    $questions = civicrm_api3('ChatQuestion', 'get', [
      'conversation_type_id' => $id,
    ])['values'];
    $this->assign('questions', $questions);

    if(count($questions)) {

      $actionParams = [
        'question_id' => ['IN' => array_keys($questions)],
        'options' => [ 'sort' => 'weight ASC' ]
      ];

      // Group actions by question, order by type, then weight (for those where weight is significant)
      $groupActions = civicrm_api3('ChatAction', 'get', array_merge($actionParams, ['type' => 'group']))['values'];
      $fieldActions = civicrm_api3('ChatAction', 'get', array_merge($actionParams, ['type' => 'field']))['values'];
      $sayActions = civicrm_api3('ChatAction', 'get', array_merge($actionParams, ['type' => 'say']))['values'];
      $conversationActions = civicrm_api3('ChatAction', 'get', array_merge($actionParams, ['type' => 'conversation']))['values'];
      $nextActions = civicrm_api3('ChatAction', 'get', array_merge($actionParams, ['type' => 'next']))['values'];

      foreach($nextActions as $key => $nextAction) {
        if(!in_array($nextAction['action_data'], array_keys($questions))){
          unset($nextActions[$key]);
        }
      }

      $actions = [];

      foreach(array_merge(
        $groupActions,
        $fieldActions,
        $sayActions,
        $conversationActions,
        $nextActions
      ) as $action){
        $actionCheck = unserialize($action['check_object']);
        if($actionCheck instanceof CRM_Chat_Check_Anything){
          $action['check_text'] = '';
        }else{
          $action['check_text'] = $actionCheck->summarise();
        }
        $actions[$action['question_id']][$action['id']] = $action;
      }

      if(count($actions)) {
        $this->assign('actions', $actions);
      }

      // Order questions by creating and flattening a question tree
      $tree[$conversationType['first_question_id']] = [];
      $questionOrder = [];
      $this->addBranches($tree, $nextActions);
      $this->order($questions, $tree, $orderedQuestions);

      $this->assign('orderedQuestions', $orderedQuestions);

      if($conversationActions){
        $this->assign('conversations', civicrm_api3('ChatConversationType', 'get', ['id' => ['IN' => array_column($conversationActions, 'action_data')]])['values']);
      }
      if($groupActions){
        $this->assign('groups', civicrm_api3('Group', 'get', ['id' => ['IN' => array_column($groupActions, 'action_data')]])['values']);
      }


      $this->assign('questionMap', array_column($orderedQuestions, 'number', 'id'));
      $this->assign('missingQuestions', array_diff(array_column($questions, 'id'), array_column($orderedQuestions, 'id')));
    }
    parent::run();

  }

  // Creates a tree based on likely routes through the questions
  function addBranches(&$root, &$nextActions) {
    foreach($root as $questionId => &$child) {
      foreach($nextActions as $actionId => &$action){
        if($action['question_id'] == $questionId){
          $child[$action['action_data']] = [];
          unset($action);
          $this->addBranches($child, $nextActions);
        }
      }
    }
  }

  // Orders questions based on the tree, adding a consecutive number for each so
  // they can be cross referenced.
  function order($questions, &$tree, &$orderedQuestions, $number = 0) {
    foreach($tree as $key => $branch) {
      $number++;
      $orderedQuestions[$number] = $questions[$key];
      $orderedQuestions[$number]['number'] = $number;
      if(count($branch)){
        $this->order($questions, $branch, $orderedQuestions, $number);
        $number++;
      }
    }
  }
}
