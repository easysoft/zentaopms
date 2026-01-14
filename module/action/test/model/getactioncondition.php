#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 actionModel->getActionCondition();
timeout=0
cid=14888

- 超级管理员的动态 @0
- 没有设置动态权限 @0
- 没有任何动态权限 @1 != 1
- 没有模块动态权限 @1 != 1
- 所有条件动态权限 @(`objectType` = 'bug' AND `action` IN ('create','edit')) OR (`objectType` = 'product' AND `action` IN ('create','edit'))
- Bug模块动态权限 @(`objectType` = 'bug' AND `action` IN ('create','edit'))

*/

$action = new actionModelTest();

global $app;

$app->user->admin = true;
r($action->getActionConditionTest()) && p() && e('0');  // 超级管理员的动态

$app->user->admin = false;
unset($app->user->rights['acls']);
r($action->getActionConditionTest()) && p() && e('0');  // 没有设置动态权限

$app->user->rights['acls']['actions'] = array();
r($action->getActionConditionTest()) && p() && e('1 != 1');  // 没有任何动态权限

$app->user->rights['acls']['actions'] = array('bug' => array('create' => '1', 'edit' => '1'), 'product' => array('create' => '1', 'edit' => 1));
r($action->getActionConditionTest('project')) && p() && e('1 != 1');  // 没有模块动态权限

r($action->getActionConditionTest())      && p() && e("(`objectType` = 'bug' AND `action` IN ('create','edit')) OR (`objectType` = 'product' AND `action` IN ('create','edit'))");  // 所有条件动态权限
r($action->getActionConditionTest('bug')) && p() && e("(`objectType` = 'bug' AND `action` IN ('create','edit'))");  // Bug模块动态权限