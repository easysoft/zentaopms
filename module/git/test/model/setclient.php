#!/usr/bin/env php
<?php

/**

title=gitModel->setClient();
timeout=0
cid=1

- 设置客户端为git @git
- 设置客户端为http @http://https-test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$git = $tester->loadModel('git');

$repo = new stdclass();
$repo->client = 'git';
$git->setClient($repo);
r($git->client) && p() && e('git'); // 设置客户端为git

$repo->client = 'http://https-test';
$git->setClient($repo);
r($git->client) && p() && e('http://https-test'); // 设置客户端为http