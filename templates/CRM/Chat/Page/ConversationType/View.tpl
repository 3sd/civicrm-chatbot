{include file="CRM/Chat/Parts/Header.tpl}

<h3>{ts}Questions{/ts}</h3>

{foreach item=question from=$orderedQuestions}

  <h4>
    {$question.number}.
    {$question.text}
    <a href="{crmURL p='civicrm/chat/question/edit' q="id=`$question.id`"}" title="edit action"><i class="crm-i fa-pencil"></i></a>
  </h4>

  <ul>
  {foreach item=action from=$actions[$question.id]}
    <li class="actions">
      {if $action.check_text}
        if {$action.check_text},
      {/if}
    {if $action.type eq 'group'}
      add to group <a href="{crmURL p='civicrm/chat/conversationType/view' q="id=`$groups[$action.action_data].id`"}">{$groups[$action.action_data].title}</a>
    {elseif $action.type eq 'field'}
      add answer to field {$action.action_data}
    {elseif $action.type eq 'say'}
      say '{$action.action_data}'
    {elseif $action.type eq 'conversation'}
      start conversation <a href="{crmURL p='civicrm/chat/conversationType/view' q="id=`$conversations[$action.action_data].id`"}">{$conversations[$action.action_data].name}</a>
    {elseif $action.type eq 'next'}
      go to question {$questionMap[$action.action_data]}
    {/if}

  <a href="{crmURL p='civicrm/chat/action/edit' q="id=`$action.id`"}" title="edit action"><i class="crm-i fa-pencil"></i></a>

  </li>
  {/foreach}
  <li><em>add action <a href="{crmURL p='civicrm/chat/action/add' q="questionId=`$question.id`"}" title="add action"><i class="crm-i fa-plus"></i></em></a></li>
  </ul>

{foreachelse}

  <div class="alert alert-warning" role="alert">{ts}No questions have been defined for this conversation.{/ts}</div>

{/foreach}

{if $missingQuestions}

  <div class="alert alert-warning"><strong>Warning</strong>: there is no path to the following questions defined in this conversation:
    <ul>
      {foreach item=question from=$missingQuestions}
        <li>{$questions[$question].text}</li>
      {/foreach}
    </ul>
  </div>
{/if}
<h4><em>{ts}Add question{/ts} <a href="{crmURL p='civicrm/chat/question/add' q="conversationTypeId=`$conversationType.id`"}" role="button"><i class="crm-i fa-plus"></i></a></em></h4>
<p>{ts}Conversation timeout:{/ts} {$conversationType.timeout} {ts}minutes{/ts}</p>
<br/><br/>
<p>
  <a class="btn btn-primary" href="{crmURL p='civicrm/chat/conversationType/edit' q="id=`$conversationType.id`"}" role="button">{ts}Edit{/ts}</a>
  <a class="btn btn-primary" href="{crmURL p='civicrm/chat/conversationType/delete' q="id=`$conversationType.id`"}" role="button">{ts}Delete{/ts}</a>
</p>
{include file="CRM/Chat/Parts/Footer.tpl}
