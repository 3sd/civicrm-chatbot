# Data model

***Developer documentation is work in progress - please feel free to contribute***

Chatbot is based on the following data model:

## New entities

Entities that Chatbot defines (see `xml/schema/CRM/Chat/*.xml`).

### Incoming chat

Messages received from a chat service.

These have a one-to-one relationship to an activity of type `Incoming chat`

* Activity ID
* Conversation ID
* Driver ID?

### Outgoing chat

Messages sent to a chat service.

These have a one-to-one relationship to an activity of type `Outgoing chat`

* Activity ID
* Conversation ID
* Driver ID?

### Conversation

* Contact ID
* Activity ID
* Conversation type ID
* Cache?

### Conversation type

?

### Chat user

Contains IDs for users of a chat service along with associated CiviCRM IDs.

*

## Types

New types of existing entities

### Activity Types

* Incoming chat
* Outgoing chat
* Conversation

Activity tp

### Outgoing message

### Conversation?
