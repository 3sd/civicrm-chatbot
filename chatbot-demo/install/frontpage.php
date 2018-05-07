<?php

$node = new stdClass();
$node->type = 'page';
node_object_prepare($node);

$node->title    = 'Welcome to the CiviCRM chatbot demo';
$node->language = LANGUAGE_NONE;
$node->body[$node->language][0]['value'] = '<p>This site is running CiviCRM with the CiviCRM chatbot extension</p>';
$node->body[$node->language][0]['summary'] = text_summary($node->body[$node->language][0]['value']);
$node->body[$node->language][0]['format'] = 'full_html';
$node->path = array('alias' => 'welcome');

node_save($node);
