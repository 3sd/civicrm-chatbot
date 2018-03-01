# Integrating with Facebook messenger


To integrate CiviCRM and Facebook messenger, you will need to:

* create a *Facebook page* where people will chat with you (or use one you have already created)
* connect your *Facebook page* to a *Facebook app*
* enter credentials into your *Facebook app* and into *CiviCRM* so they can talk each other.

The following instructions walk you through how to do this.

## Before you start

Make sure that your CiviCRM site is publicly accessible and served over HTTP ([Let’s Encrypt](https://letsencrypt.org/) offer the certificates you need for HTTPS for free if you don't already have them).

## Set up your Facebook page

Your *Facebook page* is the place where the general public will interact with you chatbot. You will need to [create a Facebook page](https://www.facebook.com/business/help/104002523024878) if you don't already have one.

2. Open your Facebook page and click the *Send message* button. This will open up the *Add a Button to Your Page* wizard.
1. In *Step 1: Which button do you want people to see?*, choose *Contact you > Send Message*.
2. In *Step 2: Where would you like this button to send people?* choose *Facebook Messenger*.

## Look up your Facebook configuration credentials

1. In CiviCRM, navigate to *Administer > System Settings > Chatbot* and click on *Facebook settings*.
2. Make a note of the *Facebook callback URL* and the automatically generated *Verify Token*. You will need these when configuring your Facebook app.

**Note:** The *Verify Token* is a randomly generated string. If you delete the string and save the form, a new randomly generated string will be generated. Alternatively, you can update this page with a token you have created yourself.

## Create a Facebook App for your page

Create a *Facebook App* to integrate your *Facebook Page* with CiviCRM.

1. If you don't already have one, sign up for a [Facebook for developers account](https://developers.facebook.com/).
2. From the *My Apps* menu, choose *Create New App* and name your App.
3. Select your app, and from the *Add a Product* menu, choose *Messenger* and click *Set Up*
4. In your app's basic settings, you should see a box that contains your *App Secret*. You need to save this in CiviCRM. Copy and paste this to the *Facebook app secret* on the *Chatbot Facebook settings* page.
5. In the *Token Generation* section, choose the page that you want to integrate with to generate a *Page Access Token*. You need to save this in CiviCRM as well.  Copy and paste into to the *Facebook page access token* on the *Chatbot Facebook settings* page.
6. In the Webhooks section, click on *Setup Webhooks* and:
  1. Enter the *Callback URL* from the *Chatbot Facebook settings* page in CiviCRM.
  2. Enter the *Verify Token* from the same page
  3. Ensure that you are subscribed to *messages*, *message_deliveries*, and *message_reads*.
6. Click *verify and save*.

If all goes well, your page will be verified and you are ready to start chatting. If the webook does not vefify, make sure that you have followed the above instructions completely. If you are still having issues, [get in contact for some help](../help).
