#!/usr/bin/env php
<?php

/**

title=测试 cneModel->appDBList();
timeout=0
cid=1

- 获取第一个应用的数据库信息
 - 第gitlab-20231226133115-gitlabhq-production条的db_type属性 @postgresql
 - 第gitlab-20231226133115-gitlabhq-production条的db_name属性 @gitlabhq_production
- 获取第二个应用的数据库信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();
r($cneModel->appDBListTest(1)) && p('gitlab-20231226133115-gitlabhq-production:db_type,db_name') && e('postgresql,gitlabhq_production'); // 获取第一个应用的数据库信息
r($cneModel->appDBListTest(2)) && p()                                                            && e('0');                              // 获取第二个应用的数据库信息