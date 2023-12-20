#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addAdminInviteField();
cid=1

- 判断需求的releasedDate是否更新成功,差值在10秒内 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('product')->config('product_for_default_program')->gen(2);
zdTable('module')->config('module_for_default_program')->gen(2);
zdTable('project')->config('project_for_default_program')->gen(2);

$upgrade = new upgradeTest();

$upgrade->relateDefaultProgram(1);

global $tester;
$projectList = $tester->dao->select('parent, path, grade')->from(TABLE_PROJECT)->fetchAll();
$moduleList  = $tester->dao->select('root')->from(TABLE_MODULE)->fetchAll();
$productList = $tester->dao->select('program')->from(TABLE_PRODUCT)->fetchAll();

r($projectList) && p('0:parent;1:parent')   && e('1;1');  //测试项目的是否被成功关联
r($moduleList)  && p('0:root;1:root')       && e('1;1');  //测试模块的是否被成功关联
r($productList) && p('0:program;1:program') && e('1;1');  //测试产品的是否被成功关联
