#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateTaskRelationPriv();
cid=19559

- 测试添加任务关系权限属性createrelation @createrelation
- 测试维护任务关系权限属性editrelation @editrelation
- 测试批量维护任务关系权限属性batcheditrelation @batcheditrelation
- 测试添加任务关系权限属性createrelation @createrelation
- 测试维护任务关系权限属性editrelation @editrelation

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1');
$grouppriv->module->range('execution');
$grouppriv->method->range('maintainrelation');
$grouppriv->gen(1);

$upgrade = new upgradeTest();
r($upgrade->updateTaskRelationPrivTest()) && p('createrelation')    && e('createrelation');    //测试添加任务关系权限
r($upgrade->updateTaskRelationPrivTest()) && p('editrelation')      && e('editrelation');      //测试维护任务关系权限
r($upgrade->updateTaskRelationPrivTest()) && p('batcheditrelation') && e('batcheditrelation'); //测试批量维护任务关系权限
r($upgrade->updateTaskRelationPrivTest()) && p('createrelation')    && e('createrelation');    //测试添加任务关系权限
r($upgrade->updateTaskRelationPrivTest()) && p('editrelation')      && e('editrelation');      //测试维护任务关系权限
