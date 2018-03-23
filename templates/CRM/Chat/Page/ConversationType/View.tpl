{include file="CRM/Chat/Parts/Header.tpl}

<p>{ts}A conversation type is a defined structure for a conversation with your contacts.{/ts}</p>

<h2>{ts}Questions{/ts}</h2>

{foreach item=question from=$questions}

  <h3>{$question.number}. {$question.text}</h3>

  <ul>
  {foreach item=action from=$actions[$question.id]}
    <li class="actions">
      {if $action.check_text}
        if {$action.check_text},
      {/if}
    {if $action.type eq 'group'}
      add to group {$action.action_data}
    {elseif $action.type eq 'field'}
      add answer to field {$action.action_data}
    {elseif $action.type eq 'say'}
      say '{$action.action_data}'
    {elseif $action.type eq 'conversation'}
      start conversation {$action.action_data}
    {elseif $action.type eq 'next'}
      go to question {$action.action_data}
    {/if}

  <a href="{crmURL p='civicrm/chat/action/edit' q="id=`$action.id`"}" title="edit action"><i class="crm-i fa-pencil"></i></a>

  </li>
  {/foreach}
  </ul>
  <p><a href="{crmURL p='civicrm/chat/action/add' q="type=3&question_id=`$question.id`"}" title="add action"><i class="crm-i fa-plus"></i> add action</a></a>

{foreachelse}

  <div class="alert alert-warning" role="alert">{ts}No questions have been defined for this conversation.{/ts}</div>

{/foreach}

<p>
  <a class="btn btn-primary" href="{crmURL p='civicrm/chat/question/add' q="conversationTypeId=`$conversationType.id`"}" role="button">{ts}Add question{/ts}</a>
  <a class="btn btn-primary" href="{crmURL p='civicrm/chat/conversationType/edit' q="id=`$conversationType.id`"}" role="button">{ts}Edit{/ts}</a>
  <a class="btn btn-primary" href="{crmURL p='civicrm/chat/conversationType/edit' q="id=`$conversationType.id`"}" role="button">{ts}Delete{/ts}</a>
</p>

{include file="CRM/Chat/Parts/Footer.tpl}
