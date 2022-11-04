<?php
$config->url = new stdclass();
$config->url->community = 'https://www.zentao.net';
$config->url->ask       = 'https://www.zentao.net/ask-browse.html';
$config->url->document  = 'https://www.zentao.net/help-book-zentaopmshelp.html';
$config->url->feedback  = 'https://www.zentao.net/forum-board-1074.html';
$config->url->faq       = 'https://www.zentao.net/ask-faq.html';
$config->url->extension = 'https://www.zentao.net/extension-browse.html';
$config->url->donation  = 'https://www.zentao.net/help-donation.html';
$config->url->service   = 'https://www.cnezsoft.com/article-browse-1078.html';

$config->admin->apiRoot = 'https://www.zentao.net';

$config->admin->log = new stdclass();
$config->admin->log->saveDays = 30;

if(!isset($config->safe))       $config->safe = new stdclass();
if(!isset($config->safe->weak)) $config->safe->weak = '123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123';
