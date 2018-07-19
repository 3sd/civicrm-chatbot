# CiviRules integration

Chatbot provides a **Start a conversation** action that can be used to start chats based on conditions defined by a CiviRule. This is useful, for example, to start a conversation when someone registers for an event.

Note that for the common use case of starting a conversation when your chatbot hears a certain word (for example when someone says 'help' on your Facebook page), you can use the built in **Hear** functionality for a conversation (note this  s currently only exposed via the UI).

For more complex use cases you should:

1. Define a **CiviRule** with appropriate **conditions** that define the action you want to respond to.
2. Add a **Start a conversation** action and choose the conversation that you would like to start.
