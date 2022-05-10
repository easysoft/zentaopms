#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->saveLog();
cid=1
pid=1



*/

$webhook = new webhookTest();

//r($webhook->saveLogTest()) && p() && e();