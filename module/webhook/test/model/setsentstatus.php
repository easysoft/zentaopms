#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->setSentStatus();
cid=1
pid=1



*/

$webhook = new webhookTest();

//r($webhook->setSentStatusTest()) && p() && e();