#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->checkActionClickable().
timeout=0
cid=14939

- 测试模块名和方法名为空时，返回false @0
- 测试没有没有权限时，返回false @0
- 测试操作用户数据，该用户不属于当前用户的部门用户，并且当前用户不是管理员，返回false @0
- 测试用户的登录操作，不需要打印链接，返回false @0
- 测试合并请求的删除操作，不需要打印链接，返回false @0
- 测试产品相关操作，打印产品详情，返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen(10);

su('user1');

$actionTest = new actionTaoTest();

$action = new stdclass();

$deptUsers = array();

$moduleName = '';
$methodName = 'view';

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('0'); //测试模块名和方法名为空时，返回false

$moduleName = 'user';
$methodName = 'create';

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('0'); //测试没有没有权限时，返回false

$moduleName = 'user';
$methodName = 'view';
$action->objectType = 'user';
$action->objectID   = 1;

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('0'); //测试操作用户数据，该用户不属于当前用户的部门用户，并且当前用户不是管理员，返回false

$action->objectType = 'user';
$action->action     = 'login';

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('0'); //测试用户的登录操作，不需要打印链接，返回false

$moduleName = 'mr';
$action->objectType = 'mr';
$action->action     = 'deleted';

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('0'); //测试合并请求的删除操作，不需要打印链接，返回false

su('admin');

$moduleName = 'product';
$action->objectType = 'product';
$action->action     = 'edit';

r($actionTest->checkActionClickableTest($action, $deptUsers, $moduleName, $methodName)) && p() && e('1'); //测试产品相关操作，打印产品详情，返回true
