# Middleware

Chatbot takes advantage of Botman's middleware to build out functionality.

## Identification

When a message is received, `CRM_Chat_Middleware_Identify` performs a look up in ChatUser based on the service and supplied user ID. If a CiviCRM contact is found, the contact_id is added to the message.

If not, a new contact and chatuser are created and associated with this contact.

## Create conversation

## Update conversation

## Record incoming message

## Record outgoing message

## Add to group

## Add to field
