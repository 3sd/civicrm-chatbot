{include file="CRM/Chatbot/Parts/Header.tpl}

{if $help.form.top}
  <p class="help">{$help.form.top}</p>
{/if}

{foreach from=$formGroups item=formGroup}

<div class="form-group">
  {$form.$formGroup.label}
  <div class="content">{$form.$formGroup.html}</div>
  <p class="help-block">{$help.field.$formGroup}</p>
</div>
{/foreach}

{if $help.form.bottom}
  <p>{$help.form.bottom}</p>
{/if}

<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

{include file="CRM/Chatbot/Parts/Footer.tpl}
