<?php

$node = new stdClass();
$node->type = 'page';
node_object_prepare($node);

$node->title    = 'Welcome to the chatbot demo';
$node->language = LANGUAGE_NONE;
$node->body[$node->language][0]['value']   = '
<p>This site is running the latest version of CiviCRM with the CiviCRM chatbot integration</p>
<p>Log in with the username <i>chat</i> and password <i>chat</i>.</p>
';
$node->body[$node->language][0]['summary'] = text_summary($node->body[$node->language][0]['value']);
$node->body[$node->language][0]['format']  = 'full_html';
$node->path = array('alias' => 'welcome');

node_save($node);
