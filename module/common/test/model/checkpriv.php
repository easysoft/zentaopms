#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);

/**

title=测试 commonModel::checkPriv();
timeout=0
cid=15660

- 用户没有权限时，返回跳转的URL @{"load":"user-deny-user-create.html"}没有权限
- 用户没有权限时，返回跳转的URL @{"load":"user-deny-productplan-view.html"}没有权限
- 判断工作流方法的权限，用户没有权限时，返回跳转的URL @{"load":"user-deny-flowModule-flowMethod.html"}没有权限
- 用户有权限时，返回TRUE @1
- 不需要判断权限的，返回TRUE @1
- GitFox空间管理员数据准备正确 @manager
- 空间权限中包含代码库review权限 @1
- GitFox空间管理员在空间内鉴权应合并空间权限 @1
- GitFox空间普通成员没有问题权限时鉴权拒绝 @{"load":"user-deny-repo-review.html"}没有权限

*/

global $tester, $app, $config;

su('user1');
$app->moduleName     = 'user';
$app->methodName     = 'create';
$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('{"load":"user-deny-user-create.html"}没有权限'); // 用户没有权限时，返回跳转的URL

$app->moduleName = 'productplan';
$app->methodName = 'bug';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('{"load":"user-deny-productplan-view.html"}没有权限'); // 用户没有权限时，返回跳转的URL

$app->isFlow = true;
$app->rawModule = 'flowModule';
$app->rawMethod = 'flowMethod';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('{"load":"user-deny-flowModule-flowMethod.html"}没有权限'); // 判断工作流方法的权限，用户没有权限时，返回跳转的URL

$app->isFlow = false;
$app->moduleName = 'user';
$app->methodName = 'login';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('1'); // 用户有权限时，返回TRUE

$app->moduleName = 'block';
$app->methodName = 'dashboard';

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('1'); // 不需要判断权限的，返回TRUE

// 6. GitFox独立版空间管理员在空间内的鉴权：空间管理员隐式拥有空间内全部权限，鉴权结果应与菜单保持一致。
zenData('company')->gen(1);
zenData('user')->gen(2);
zenData('ops_repouser')->gen(0);
unset($_SESSION['devopsSpace']);

$group = zenData('group');
$group->id->range('1');
$group->vision->range('gitfox');
$group->project->range('0');
$group->devopsSpace->range('0');
$group->gen(1);

$usergroup = zenData('usergroup');
$usergroup->account->range('user1');
$usergroup->group->range('1');
$usergroup->gen(1);

$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1');
$grouppriv->module->range('repo');
$grouppriv->method->range('browse');
$grouppriv->gen(1);

$space = zenData('ops_space');
$space->id->range('1');
$space->acl->range('private');
$space->auth->range('extend');
$space->gen(1);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1');
$spaceUser->space->range('1');
$spaceUser->account->range('user1');
$spaceUser->role->range('manager');
$spaceUser->gen(1);

$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->spaceID->range('1');
$repo->acl->range('open');
$repo->gen(1);

$config->vision = 'gitfox';
su('user1');

$app->tab        = 'repo';
$app->moduleName = 'repo';
$app->methodName = 'review';
$app->params     = array('repoID' => 1);

/* 前置校验：user1是空间1的管理员，且空间权限中包含代码库模块权限。 */
r($tester->dao->select('role')->from(TABLE_DEVOPSSPACEUSER)->where('space')->eq(1)->andWhere('account')->eq('user1')->fetch('role')) && p() && e('manager'); // GitFox空间管理员数据准备正确
r(isset($tester->loadModel('space')->getDevOpsAllPrivs()['repo']['review'])) && p() && e('1'); // 空间权限中包含代码库review权限

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('1'); // GitFox空间管理员在空间内鉴权应合并空间权限

// 7. 空间普通成员在分组没有问题权限时，鉴权仍然拒绝。
$tester->dao->update(TABLE_DEVOPSSPACEUSER)->set('role')->eq('member')->where('id')->eq(1)->exec();
su('user1');

$app->tab        = 'repo';
$app->moduleName = 'repo';
$app->methodName = 'review';
$app->params     = array('repoID' => 1);

try
{
    $result = $tester->loadModel('common')->checkPriv();
}
catch (Exception $e)
{
    $result = '没有权限';
}

r($result) && p() && e('{"load":"user-deny-repo-review.html"}没有权限'); // GitFox空间普通成员没有问题权限时鉴权拒绝

unset($_SERVER['HTTP_X_REQUESTED_WITH']);
