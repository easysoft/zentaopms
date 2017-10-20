<?php
$config->webhook->create = new stdclass();
$config->webhook->create->requiredFields = 'name, url';

$config->webhook->edit = new stdclass();
$config->webhook->edit->requiredFields = 'name, url';

$config->webhook->requestType['post'] = 'post';
$config->webhook->requestType['get']  = 'get';
