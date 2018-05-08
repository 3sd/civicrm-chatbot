<?php
use BotMan\BotMan\Users\User;
use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Messages\Incoming\Answer;
use Symfony\Component\HttpFoundation\Request;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Chat service for developers
 */
class CRM_Chat_Driver_DevChatDriver extends HttpDriver {

  const DRIVER_NAME = 'CRM_Chat_Driver_DevChat';

  /**
   * Build a payload for an incoming message from an http request
   * @param  Request $request [description]
   */

  public function buildPayload(Request $request) {

    $this->payload = new ParameterBag((array) json_decode($request->getContent(), true));

  }

  /**
  * Determine if the request is for this driver (called after buildPayload so we
  * can perform tests based on anything we have set there is required)
  */
  public function matchesRequest() {

    return true;

  }

  /**
   * Get the incoming messages from the incoming payload
   * @return [type] [description]
   */
  public function getMessages() {

    $message = $this->payload->get('text');
    $userId = 1;
    $this->messages = [new IncomingMessage($message, $userId, $userId)];

    return $this->messages;

  }

  /**
   * Gets the user from the message
   */
  public function getUser(IncomingMessage $matchingMessage) {

    // For this driver, all messages all come from Mr Dev Chat.
    return new User('1', 'Joe', 'Bloggs');

  }

  /**
   * Ensure that the driver is configured. We could check that
   * chatbot_devchat_endpoint has been set in this method.
   */
  public function isConfigured() {

    return true;

  }

  /**
   * Only useful for 'interactive' conversations but needs to be defined for all
   * drivers
   */
  public function getConversationAnswer(IncomingMessage $message) {

      return Answer::create($message->getText())->setMessage($message);

  }

  /**
   * Construct the outgoing message payload for DevChat
   */
  public function buildServicePayload($message, $matchingMessage, $additionalParameters = []) {

    return ['message' => ['text' => $message->getText()]];

  }

  /**
   * Send the outgoing message payload to DevChat
   */
  public function sendPayload($payload){

    $response = $this->http->post($this->config->get('endpoint'), [], $payload, ['Content-type: application/json'], true );

    return $response;

  }

  /**
  * Low-level method to perform driver specific API requests (not implemented)
  */
  public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage){

  }
}
