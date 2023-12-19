#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updateProjectAdmin();
timeout=0
cid=1

- 测试项目管理员更新项目集第user1条的programs属性 @2
- 测试项目管理员更新产品第user1条的products属性 @2
- 测试项目管理员更新项目第user1条的projects属性 @2
- 测试项目管理员更新执行第user1条的executions属性 @2
- 测试项目管理员更新多人项目集第user1,user2条的programs属性 @2
- 测试项目管理员更新多人产品第user1,user2条的products属性 @2
- 测试项目管理员更新多人项目第user1,user2条的projects属性 @2
- 测试项目管理员更新多人执行第user1,user2条的executions属性 @2
- 测试项目管理员更新所有项目集第user1,user2条的programs属性 @all
- 测试项目管理员更新所有项目集第user1,user2条的products属性 @all
- 测试项目管理员更新所有项目集第user1,user2条的projects属性 @all
- 测试项目管理员更新所有项目集第user1,user2条的executions属性 @all

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';
su('admin');

$group = new groupTest();

$programs1   = array('program' => array(2), 'project' => array(),  'product' => array(),  'execution' => array(),  'accounts' => array('user1'));
$products1   = array('program' => array(),  'project' => array(),  'product' => array(2), 'execution' => array(),  'accounts' => array('user1'));
$projects1   = array('program' => array(),  'project' => array(2), 'product' => array(),  'execution' => array(),  'accounts' => array('user1'));
$executions1 = array('program' => array(),  'project' => array(),  'product' => array(),  'execution' => array(2), 'accounts' => array('user1'));

$programs2   = array('program' => array(2), 'project' => array(),  'product' => array(),  'execution' => array(),  'accounts' => array('user1,user2'));
$products2   = array('program' => array(),  'project' => array(),  'product' => array(2), 'execution' => array(),  'accounts' => array('user1,user2'));
$projects2   = array('program' => array(),  'project' => array(2), 'product' => array(),  'execution' => array(),  'accounts' => array('user1,user2'));
$executions2 = array('program' => array(),  'project' => array(),  'product' => array(),  'execution' => array(2), 'accounts' => array('user1,user2'));

$allPrograms   = array('program' => array('all'), 'project' => array(),      'product' => array(),      'execution' => array(),      'accounts' => array('user1,user2'));
$allProducts   = array('program' => array(),      'project' => array(),      'product' => array('all'), 'execution' => array(),      'accounts' => array('user1,user2'));
$allProjects   = array('program' => array(),      'project' => array('all'), 'product' => array(),      'execution' => array(),      'accounts' => array('user1,user2'));
$allExecutions = array('program' => array(),      'project' => array(),      'product' => array(),      'execution' => array('all'), 'accounts' => array('user1,user2'));

r($group->updateProjectAdminTest(1, array($programs1)))   && p('user1:programs')     && e('2'); // 测试项目管理员更新项目集
r($group->updateProjectAdminTest(1, array($products1)))   && p('user1:products')     && e('2'); // 测试项目管理员更新产品
r($group->updateProjectAdminTest(1, array($projects1)))   && p('user1:projects')     && e('2'); // 测试项目管理员更新项目
r($group->updateProjectAdminTest(1, array($executions1))) && p('user1:executions')   && e('2'); // 测试项目管理员更新执行

r($group->updateProjectAdminTest(1, array($programs2)))   && p('user1,user2:programs')   && e('2'); // 测试项目管理员更新多人项目集
r($group->updateProjectAdminTest(1, array($products2)))   && p('user1,user2:products')   && e('2'); // 测试项目管理员更新多人产品
r($group->updateProjectAdminTest(1, array($projects2)))   && p('user1,user2:projects')   && e('2'); // 测试项目管理员更新多人项目
r($group->updateProjectAdminTest(1, array($executions2))) && p('user1,user2:executions') && e('2'); // 测试项目管理员更新多人执行

r($group->updateProjectAdminTest(1, array($allPrograms)))   && p('user1,user2:programs')   && e('all'); // 测试项目管理员更新所有项目集
r($group->updateProjectAdminTest(1, array($allProducts)))   && p('user1,user2:products')   && e('all'); // 测试项目管理员更新所有项目集
r($group->updateProjectAdminTest(1, array($allProjects)))   && p('user1,user2:projects')   && e('all'); // 测试项目管理员更新所有项目集
r($group->updateProjectAdminTest(1, array($allExecutions))) && p('user1,user2:executions') && e('all'); // 测试项目管理员更新所有项目集