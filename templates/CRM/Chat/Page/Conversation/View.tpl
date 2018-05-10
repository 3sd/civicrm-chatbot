{include file="CRM/Chat/Parts/Header.tpl}

<p>Status: {$conversation.status}</p>
<p>Started by: {$conversation.source_contact}</p>
<p>Date: {$conversation.date}</p>

{foreach item=chat from=$chats}

<div class="chat chat-{$chat.class}">{$chat.details}</div>

{/foreach}

{include file="CRM/Chat/Parts/Footer.tpl}
