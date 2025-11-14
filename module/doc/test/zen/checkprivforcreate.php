#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForCreate();
timeout=0
cid=16187

- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclibOpen, 'custom'  @1
- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclibCustomWithUser, 'custom'  @1
- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclibPrivateNoAccess, 'custom'  @0
- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclibCustomWithGroup, 'custom'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doclib')->loadYaml('checkprivforcreate/doclib', false, 2)->gen(10);
zenData('product')->loadYaml('checkprivforcreate/product', false, 2)->gen(5);
zenData('project')->loadYaml('checkprivforcreate/project', false, 2)->gen(5);

$userGroupTable = zenData('usergroup');
$userGroupTable->account->range('admin,admin,user1,user1,user2,user2');
$userGroupTable->group->range('1,2,1,2,3,4');
$userGroupTable->project->range('``');
$userGroupTable->gen(6);

global $tester;
$tester->app->user = new stdclass();
$tester->app->user->account = 'user1';
$tester->app->user->admin = false;

$docTest = new docZenTest();

$doclibOpen = new stdclass();
$doclibOpen->id = 1;
$doclibOpen->type = 'custom';
$doclibOpen->acl = 'open';
$doclibOpen->groups = '';
$doclibOpen->users = '';
$doclibOpen->addedBy = 'admin';

$doclibCustomWithUser = new stdclass();
$doclibCustomWithUser->id = 2;
$doclibCustomWithUser->type = 'custom';
$doclibCustomWithUser->acl = 'custom';
$doclibCustomWithUser->groups = '';
$doclibCustomWithUser->users = 'user1';
$doclibCustomWithUser->addedBy = 'admin';

$doclibPrivateNoAccess = new stdclass();
$doclibPrivateNoAccess->id = 3;
$doclibPrivateNoAccess->type = 'custom';
$doclibPrivateNoAccess->acl = 'private';
$doclibPrivateNoAccess->groups = '';
$doclibPrivateNoAccess->users = '';
$doclibPrivateNoAccess->addedBy = 'admin';

$doclibCustomWithGroup = new stdclass();
$doclibCustomWithGroup->id = 4;
$doclibCustomWithGroup->type = 'custom';
$doclibCustomWithGroup->acl = 'custom';
$doclibCustomWithGroup->groups = '1,2';
$doclibCustomWithGroup->users = '';
$doclibCustomWithGroup->addedBy = 'admin';

$tester->app->user->account = 'admin';
$tester->app->user->admin = true;
$doclibPrivateAdmin = new stdclass();
$doclibPrivateAdmin->id = 5;
$doclibPrivateAdmin->type = 'custom';
$doclibPrivateAdmin->acl = 'private';
$doclibPrivateAdmin->groups = '';
$doclibPrivateAdmin->users = '';
$doclibPrivateAdmin->addedBy = 'user2';

$doclibProduct = new stdclass();
$doclibProduct->id = 6;
$doclibProduct->type = 'product';
$doclibProduct->product = 1;
$doclibProduct->acl = 'open';

$doclibProject = new stdclass();
$doclibProject->id = 7;
$doclibProject->type = 'project';
$doclibProject->project = 1;
$doclibProject->acl = 'open';

$tester->app->user->account = 'user1';
$tester->app->user->admin = false;

r($docTest->checkPrivForCreateTest($doclibOpen, 'custom')) && p() && e('1');
r($docTest->checkPrivForCreateTest($doclibCustomWithUser, 'custom')) && p() && e('1');
r($docTest->checkPrivForCreateTest($doclibPrivateNoAccess, 'custom')) && p() && e('0');
r($docTest->checkPrivForCreateTest($doclibCustomWithGroup, 'custom')) && p() && e('1');
$tester->app->user->account = 'admin'; $tester->app->user->admin = true; r($docTest->checkPrivForCreateTest($doclibPrivateAdmin, 'custom')) && p() && e('1');
$tester->app->user->account = 'admin'; $tester->app->user->admin = true; r($docTest->checkPrivForCreateTest($doclibProduct, 'product')) && p() && e('1');
$tester->app->user->account = 'admin'; $tester->app->user->admin = true; r($docTest->checkPrivForCreateTest($doclibProject, 'project')) && p() && e('1');