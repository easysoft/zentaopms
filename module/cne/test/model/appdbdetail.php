#!/usr/bin/env php
<?php

/**

title=测试 cneModel->appDBDetail();
timeout=0
cid=1

- 没有数据库的应用 @0
- 数据库名错误 @0
- 正确的应用及数据库名
 - 属性username @gitlab
 - 属性namespace @quickon-app
 - 属性database @gitlabhq_production

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();
r($cneModel->appDBDetailTest(2, 'test')) && p() && e('0'); // 没有数据库的应用
r($cneModel->appDBDetailTest(1, 'test')) && p() && e('0'); // 数据库名错误

r($cneModel->appDBDetailTest(1, 'gitlab-20231226133115-gitlabhq-production')) && p('username,namespace,database') && e('gitlab,quickon-app,gitlabhq_production'); // 正确的应用及数据库名