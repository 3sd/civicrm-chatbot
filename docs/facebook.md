# Integrating with Facebook

Integrate with Facebook messenger to automate chat with your contacts on a Facebook page.

The instructions on this page configure Facebook to let CiviCRM know whenever someone sends you a message on your Facebook page, and allow CiviCRM to post messages to contacts that have contacted you via Facebook (either in reply to a message they have sent, or initiated from CiviCRM).

[TODO: insert short overview of how the Facebook Page, Facebook App and CiviCRM connect to each other]

## Prerequisites

Your CiviCRM will need to be hosted on HTTPS and accessible via the internet. If you are wondering what https service to go with, we recommend *[Let’s Encrypt](https://letsencrypt.org/)*, a free, automated, and open certificate authority (CA), run for the public’s benefit.

## Set up your Facebook page

Your *Facebook page* is the place where the general public will interact with you chatbot. If you don't already have one that you want to use, you will need to create one.

1. [Create a Facebook page](https://www.facebook.com/business/help/104002523024878) for your chatbot or navigate to the page you want to use.
2. On your page, click the Send message button. This will open up the *Add a Button to Your Page* wizard:
  1. In *Step 1: Which button do you want people to see?*, choose *Contact you > Send Message*.
  2. In *step 2: Where would you like this button to send people?* choose *Facebook Messenger* (the only option available).

## Look up your Facebook configuration credentials

1. navigate to *Administer > System Settings > Chatbot*
2. Make a note of the *callback URL* and the automatically generated *Verify Token*. You will need these when configuring your Facebook app.

**Note:** The *Verify Token* is a randomly generated string. If you delete the string and save the form a new randomly generated string will be generated. Alternatively, you can update this page with a token you have created yourself.

## Create a Facebook App for your page

You need to create a *Facebook App* to integrate your *Facebook Page* with CiviCRM.

1. Sign up for a [Facebook for developers](https://developers.facebook.com/) account.
2. From the *My Apps* menu, choose *Create New App* and name your App.
3. Select your app and from the *Add a Product* menu, choose *Messenger* and click *Set Up*
4. In the *Token Generation* section, choose the page that you want to integrate with. You will need to agree to some permissions in this step. Make a note of the *Page Access Token* (you will need to add this to your CiviCRM configuration screen)
5. In the Webhooks section, click on *Setup Webhooks*
  1. Enter the *Callback URL* from the *Chatbot: Facebook settings* page in CiviCRM.
  2. Enter the *Verify Token* from the same pages
  3. Ensure that you are subscribed to *messages*, *message_deliveries*, and *message_reads*.
6. Click verify and save. This will cause CiviCRM to 
