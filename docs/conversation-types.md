# Conversation types

**Conversations types** are the main building block of Chatbot. They are made up of a series of **Questions**, for which one can define **actions** to take when various **answers** are received.

View a list of Conversation types by selecting **Chat > Conversation Types** from the menu.

Click the **Add conversation type** button on this screen to create your first conversation type. You will be prompted to name the conversation and enter your first question.

## Questions

Each conversation type consist of a series of questions. Typically the contact will reply to your question with an answer, and you can carry out various **actions** depending on the answer they give you.

## Actions

There are two ways you can reply to someone when they answer you. Ask another question (**Go to Question**), or **Say** to say something without expecting an answer.

Two other actions are currently available:

* Add to group, which allows you to add the contact to a group
* Add to field, which allows you to record the answer that they have given you in any core or custom contact field.

## Matching

Often, you will only want to carry out an action if the answer matches a certain criteria. For example, if you ask the question "Would you like to be added to our newsletter?", then you would only want to add them to the newsletter if the reply was "Yes" (or something similar).

Matching is configured for each action. You can either choose to match anything, match exact text, or match text containing a phrase.

## Branching

You may want to ask different questions depending on the answer to the previous question. To do this, you configure multiple **Go to question** actions for a question. For example, if we ask someone whether the like Art or Music best, and they reply that they like music the best, we might ask who their favourite band is. But if they say they like Art better, we might ask who their favourite Artist is. For extra bonus points, we could record the answer to the question, what is your favourite band in a custom field called 'Favourite band'.
