#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::isWebhookExists();
timeout=0
cid=16662

- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=1'  @1
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=999'  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, ''  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是999, 'http://api.php/v1/gitlab/webhook?repoID=1'  @0
- 执行gitlabTest模块的isWebhookExistsTest方法，参数是1, 'http://api.php/v1/gitlab/webhook?repoID=1&param=test%20value'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('1');
r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=999')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(1, '')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(999, 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest(1, 'http://api.php/v1/gitlab/webhook?repoID=1&param=test%20value')) && p() && e('0');