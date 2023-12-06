#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->config('acl')->gen(100);
zdTable('project')->config('project')->gen(120);
zdTable('product')->gen(50);
zdTable('userview')->gen(50);
zdTable('user')->gen(20);

/**

title=测试 personnelModel->updateWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$user = array();
$user[0] = array('dev10');
$user[1] = array('test1', 'test2');
$user[2] = array();

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'program';
$objectType[2] = 'product';
$objectType[3] = 'sprint';

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 2;

$type = array();
$type[0] = 'whitelist';
$type[1] = 'blacklist';

$source = array();
$source[0] = 'update';
$source[1] = 'add';
$source[2] = 'sync';

$updateType = array();
$updateType[0] = 'increase';
$updateType[1] = 'replace';

r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[0], $type[0], $source[0], $updateType[0])) && p() && e('acls: user10:add,user30:upgrade,user50:sync,user70:add,user90:upgrade,dev10:update;'); // 测试更新 admin dev10 到 project 1 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;');                                                                 // 测试更新 admin dev10 到 project 1 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('acls: dev10:update;');                                                                 // 测试更新 admin dev10 到 project 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('acls: dev10:update;');                                                                 // 测试更新 admin dev10 到 project 1 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[0], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,11,test2:,12;'); // 测试更新 test1 test2 到 project 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,test2:,12;');              // 测试更新 test1 test2 到 project 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,test2:,12;');              // 测试更新 test1 test2 到 project 1 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,test2:,12;');              // 测试更新 test1 test2 到 project 1 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[0], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,11,test2:,12;'); // 测试更新 空的人员列表 到 project 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                     // 测试更新 空的人员列表 到 project 1 whitelist，source为 sync   数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('0');                                                     // 测试更新 空的人员列表 到 project 1 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('0');                                                     // 测试更新 空的人员列表 到 project 1 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[1], $type[0], $source[0], $updateType[0])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 project 2 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 project 2 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 project 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[0], $objectType[0], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 project 2 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[1], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,11,2,test2:,12,2;'); // 测试更新 test1 test2 到 project 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,2,test2:,12,2;');              // 测试更新 test1 test2 到 project 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,2,test2:,12,2;');              // 测试更新 test1 test2 到 project 2 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[0], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,11,2,test2:,12,2;');              // 测试更新 test1 test2 到 project 2 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[1], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,11,2,test2:,12,2;'); // 测试更新 空的人员列表 到 project 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                         // 测试更新 空的人员列表 到 project 2 whitelist，source为 sync   数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('0');                                                         // 测试更新 空的人员列表 到 project 2 whitelist，source为 update 数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[0], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('0');                                                         // 测试更新 空的人员列表 到 project 2 whitelist，source为 add    数据变更类型为 update


r($personnel->updateWhitelistTest($user[0], $objectType[1], $objectID[0], $type[0], $source[0], $updateType[0])) && p() && e('acls: admin:upgrade,user20:sync,user40:add,user60:upgrade,user80:sync,dev10:update;views: admin:2,3,5,6,8,9;'); // 测试更新 admin dev10 到 program 1 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[1], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;');                                                                                          // 测试更新 admin dev10 到 program 1 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[1], $objectID[0], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 test1 test2 到 program 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[1], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;');              // 测试更新 test1 test2 到 program 1 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[1], $objectID[0], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 空的人员列表 到 program 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[1], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                   // 测试更新 空的人员列表 到 program 1 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[0], $objectType[1], $objectID[1], $type[0], $source[0], $updateType[0])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 program 2 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[1], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 program 2 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[1], $objectID[1], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,1,2,test2:,2;'); // 测试更新 test1 test2 到 program 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[1], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,1,2,test2:,2;');              // 测试更新 test1 test2 到 program 2 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[1], $objectID[1], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,1,2,test2:,2;'); // 测试更新 空的人员列表 到 program 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[1], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                     // 测试更新 空的人员列表 到 program 2 whitelist，source为 sync   数据变更类型为 update


r($personnel->updateWhitelistTest($user[0], $objectType[2], $objectID[0], $type[0], $source[0], $updateType[0])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 product 1 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[2], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 product 1 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[2], $objectID[0], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 test1 test2 到 product 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[2], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;');              // 测试更新 test1 test2 到 product 1 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[2], $objectID[0], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 空的人员列表 到 product 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[2], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                   // 测试更新 空的人员列表 到 product 1 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[0], $objectType[2], $objectID[1], $type[0], $source[0], $updateType[0])) && p() && e('acls: user1:add,user21:upgrade,user41:sync,user61:add,user81:upgrade,dev10:update;'); // 测试更新 admin dev10 到 product 2 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[2], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;');                                                                // 测试更新 admin dev10 到 product 2 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[2], $objectID[1], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 test1 test2 到 product 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[2], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;');              // 测试更新 test1 test2 到 product 2 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[2], $objectID[1], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,1,test2:,2;'); // 测试更新 空的人员列表 到 product 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[2], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                   // 测试更新 空的人员列表 到 product 2 whitelist，source为 sync   数据变更类型为 update


r($personnel->updateWhitelistTest($user[0], $objectType[3], $objectID[0], $type[0], $source[0], $updateType[0])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 sprint 1 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[3], $objectID[0], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;'); // 测试更新 admin dev10 到 sprint 1 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[3], $objectID[0], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,101,test2:,102;'); // 测试更新 test1 test2 到 sprint 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[3], $objectID[0], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,101,test2:,102;');              // 测试更新 test1 test2 到 sprint 1 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[3], $objectID[0], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,101,test2:,102;'); // 测试更新 空的人员列表 到 sprint 1 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[3], $objectID[0], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                       // 测试更新 空的人员列表 到 sprint 1 whitelist，source为 sync   数据变更类型为 update

r($personnel->updateWhitelistTest($user[0], $objectType[3], $objectID[1], $type[0], $source[0], $updateType[0])) && p() && e('acls: user11:sync,user31:add,user51:upgrade,user71:sync,user91:add,dev10:update;'); // 测试更新 admin dev10 到 sprint 2 whitelist，source为 update 数据变更类型为increase
r($personnel->updateWhitelistTest($user[0], $objectType[3], $objectID[1], $type[0], $source[0], $updateType[1])) && p() && e('acls: dev10:update;');                                                              // 测试更新 admin dev10 到 sprint 2 whitelist，source为 update 数据变更类型为 update

r($personnel->updateWhitelistTest($user[1], $objectType[3], $objectID[1], $type[0], $source[1], $updateType[0])) && p() && e('acls: dev10:update,test1:add,test2:add;views: test1:,101,2,test2:,102,2;'); // 测试更新 test1 test2 到 sprint 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[1], $objectType[3], $objectID[1], $type[0], $source[1], $updateType[1])) && p() && e('acls: test1:add,test2:add;views: test1:,101,2,test2:,102,2;');              // 测试更新 test1 test2 到 sprint 2 whitelist，source为 add    数据变更类型为 update

r($personnel->updateWhitelistTest($user[2], $objectType[3], $objectID[1], $type[0], $source[2], $updateType[0])) && p() && e('acls: test1:add,test2:add;views: test1:,101,2,test2:,102,2;'); // 测试更新 空的人员列表 到 sprint 2 whitelist，source为 add    数据变更类型为 update
r($personnel->updateWhitelistTest($user[2], $objectType[3], $objectID[1], $type[0], $source[2], $updateType[1])) && p() && e('0');                                                           // 测试更新 空的人员列表 到 sprint 2 whitelist，source为 sync   数据变更类型为 update
