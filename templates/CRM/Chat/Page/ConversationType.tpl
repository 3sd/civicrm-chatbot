{include file="CRM/Chat/Parts/Header.tpl}

<p>{ts}A conversation type is a framework for conversations with your contacts.{/ts}</p>

{foreach item=conversationType from=$conversationTypes}
<h2><a href="{crmURL p='civicrm/chat/conversationType/view' q="id=`$conversationType.id`"}">{$conversationType.name}</a></h2>

{foreachelse}

  <div class="alert alert-warning" role="alert">{ts}No conversation types have been defined.{/ts}</div>

{/foreach}

<br/>
<a class="btn btn-primary" href="{crmURL p='civicrm/chat/conversationType/add'}" role="button">{ts}Add a conversation type{/ts}</a>
{include file="CRM/Chat/Parts/Footer.tpl}
