<?php
$config->url = new stdclass();
$config->url->community = 'http://www.zentao.net';
$config->url->ask       = 'http://www.zentao.net/ask-browse.html';
$config->url->document  = 'http://www.zentao.net/help-book-zentaopmshelp.html';
$config->url->feedback  = 'http://www.zentao.net/forum-board-1074.html';
$config->url->faq       = 'http://www.zentao.net/ask-faq.html';
$config->url->extension = 'http://www.zentao.net/extension-browse.html';
$config->url->donation  = 'http://www.zentao.net/help-donation.html';
$config->url->service   = 'http://www.cnezsoft.com/article-browse-1078.html';

$config->admin->apiRoot = 'http://www.zentao.net';

if(!isset($config->safe)) $config->safe = new stdclass();
if(!isset($config->safe->weak)) $config->safe->weak = '123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123';
