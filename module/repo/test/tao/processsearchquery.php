#!/usr/bin/env php
<?php

/**

title=测试 repoTao::processSearchQuery();
timeout=0
cid=18122

- 步骤1：空queryID的情况 @ 1 = 1
- 步骤2：有效queryID @name LIKE aa
- 步骤3：无效queryID保持现有session @name LIKE aa
- 步骤4：另一个有效queryID @status = active
- 步骤5：负数queryID @ 1 = 1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$table = zenData('userquery');
$table->id->range('1-5');
$table->sql->range('name LIKE aa,status = active,type = repo,user = admin,deleted = 0');
$table->form->range('{"name":"aa"},{"status":"active"},{"type":"repo"},{"user":"admin"},{"deleted":"0"}');
$table->module->range('repo{5}');
$table->account->range('admin{3},user{2}');
$table->title->range('搜索条件1,搜索条件2,搜索条件3,搜索条件4,搜索条件5');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTaoTest();

// 5. 测试步骤 - 每步之间清理session确保独立性
$tester->session->set('repoQuery', '');
r($repoTest->processSearchQueryTest(0)) && p() && e(' 1 = 1'); // 步骤1：空queryID的情况

$tester->session->set('repoQuery', '');
r($repoTest->processSearchQueryTest(1)) && p() && e("name LIKE aa"); // 步骤2：有效queryID

// 已设置session，测试无效queryID不改变现有session
r($repoTest->processSearchQueryTest(999)) && p() && e("name LIKE aa"); // 步骤3：无效queryID保持现有session

$tester->session->set('repoQuery', '');
r($repoTest->processSearchQueryTest(2)) && p() && e("status = active"); // 步骤4：另一个有效queryID

$tester->session->set('repoQuery', '');
r($repoTest->processSearchQueryTest(-1)) && p() && e(' 1 = 1'); // 步骤5：负数queryID