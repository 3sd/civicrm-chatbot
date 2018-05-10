<div id="sms-conversations-tab" class="view-content">
<div class="action-link">
  <a accesskey="N" href='{crmURL p="civicrm/sms/conversation/schedule" q="cid=`$contactId`"}' class="button medium-popup"><span><i class="crm-i fa-comment"></i> Schedule Conversation</span></a>
</div>

<table class="crm-chat-selector crm-ajax-table">
  <thead>
  <tr>
    <th data-data="subject" cell-class="crm-chat-subject crmf-title" class='crm-chat-subject'>{ts}Conversation{/ts}</th>
    <th data-data="source_contact" cell-class="crm-chat-source_contact" class='crm-chat-source_contact'>{ts}Started By{/ts}</th>
    <th data-data="date" cell-class="crm-chat-date" class='crm-chat-date'>{ts}Date{/ts}</th>
    <th data-data="status" cell-class="crm-chat-status" class='crm-chat-status'>{ts}Status{/ts}</th>
    <th data-data="links" data-orderable="false" cell-class="crm-chat-chat_links" class='crm-chat-chat_links'>&nbsp;</th>
  </tr>
  </thead>
</table>
</div>

{literal}
<script type="text/javascript">
    (function($) {
        var ZeroRecordText = {/literal}'{ts escape="js"}<div class="status messages">No SMS Conversations for this contact.{/ts}</div>'{literal};
        $('table.crm-chat-selector').data({
            "ajax": {
                "url": {/literal}'{crmURL p="civicrm/ajax/chat/list" h=0 q="snippet=4&cid=`$contactId`"}'{literal},
                "data": function (d) {
                }
            },
            "language": {
                "zeroRecords": ZeroRecordText,
                "emptyTable": ZeroRecordText
            },
            "drawCallback": function(settings) {
                //Add data attributes to cells
                $('thead th', settings.nTable).each( function( index ) {
                    $.each(this.attributes, function() {
                        if(this.name.match("^cell-")) {
                            var cellAttr = this.name.substring(5);
                            var cellValue = this.value;
                            $('tbody tr', settings.nTable).each( function() {
                                $('td:eq('+ index +')', this).attr( cellAttr, cellValue );
                            });
                        }
                    });
                });
                //Reload table after draw
                $(settings.nTable).trigger('crmLoad');
            }
        });
        $('#crm-container')
            .on('click', 'a.button, a.action-item[href*="action=update"], a.action-item[href*="action=delete"]', CRM.popup)
            .on('crmPopupFormSuccess', 'a.button, a.action-item[href*="action=update"], a.action-item[href*="action=delete"]', function() {
                // Refresh datatable when form completes
                $('table.crm-chat-selector').DataTable().draw();
            });
    })(CRM.$);
</script>
{/literal}
