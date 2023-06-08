<?php
$config->ai->openai = new stdclass();

$config->ai->openai->api = new stdclass();
$config->ai->openai->api->version    = 'v1';                           // OpenAI API version, required.
$config->ai->openai->api->format     = 'https://api.openai.com/%s/%s'; // OpenAI API format, args: API version, API name.
$config->ai->openai->api->authFormat = 'Authorization: Bearer %s';     // OpenAI API auth header format.
$config->ai->openai->api->methods    = array('chat' => 'chat/completions', 'completion' => 'completions', 'edit' => 'edits');

$config->ai->openai->params = new stdclass();
$config->ai->openai->params->chat       = new stdclass();
$config->ai->openai->params->completion = new stdclass();
$config->ai->openai->params->edit       = new stdclass();
$config->ai->openai->params->chat->required = array('messages');
$config->ai->openai->params->chat->optional = array('max_tokens', 'temperature', 'top_p', 'n', 'stream', 'stop', 'presence_penalty', 'frequency_penalty', 'logit_bias', 'user');
$config->ai->openai->params->completion->required = array('prompt', 'max_tokens');
$config->ai->openai->params->completion->optional = array('suffix', 'temperature', 'top_p', 'n', 'stream', 'logprobs', 'echo', 'stop', 'presence_penalty', 'frequency_penalty', 'best_of', 'logit_bias', 'user');
$config->ai->openai->params->edit->required = array('input', 'instruction');
$config->ai->openai->params->edit->optional = array('temperature', 'top_p', 'n');

$config->ai->openai->model = new stdclass();
$config->ai->openai->model->chat       = 'gpt-3.5-turbo';
$config->ai->openai->model->completion = 'text-davinci-003';
$config->ai->openai->model->edit       = 'text-davinci-edit-001';

$config->ai->openai->contentTypeMapping = array('Content-Type: application/json' => array('', 'chat', 'completion', 'edit'), 'Content-Type: multipart/form-data' => array());
$config->ai->openai->contentType = array();
foreach($config->ai->openai->contentTypeMapping as $contentType => $apis)
{
    foreach($apis as $api) $config->ai->openai->contentType[$api] = $contentType;
}
