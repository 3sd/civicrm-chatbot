{include file="CRM/Chat/Parts/Header.tpl}

{ts}Number of contacts with chat accounts{/ts}
<ul>
  {foreach from=$serviceUsers item=count key=service}
  <li>
    {$service}: {$count|@count}
  </li>
  {/foreach}
</ul>
{foreach from=$fields item=field}

<div class="form-group {$form.$field.name}">
  {$form.$field.label}
  <div class="content">{$form.$field.html}</div>
  <p class="help-block">{$help.field.$field.text}</p>
</div>
{/foreach}

{if $help.form.bottom}
  <div class="alert alert-{$help.form.bottom.level}">{$help.form.bottom.text}</div>
{/if}

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
  {if $delete}
  <span class="crm-button">
    <a class="btn btn-primary" href="{crmURL p="`$delete.path`" q="`$delete.query`"}">Delete</a>
  </span>
  {/if}
</div>

{include file="CRM/Chat/Parts/Footer.tpl}
