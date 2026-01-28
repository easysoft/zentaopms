#!/usr/bin/env php
<?php

/**

title=测试 userModel->getProductMembers();
timeout=0
cid=19633

- 查看user1可访问的执行 @1
- 查看user2可访问的执行 @1
- 查看user3可访问的执行 @2
- 查看user4可访问的执行 @2
- 查看user5可访问的执行 @1
- 查看user6可访问的执行 @2
- 查看user7可访问的执行 @1
- 查看user8可访问的执行 @2
- 查看user9可访问的执行 @1
- 查看user10可访问的执行 @2
- 查看admin可访问的执行 @1,2,3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$allSprints = array(
    1 => (object)array('id' => 1, 'program' => 0, 'acl' => 'private', 'PO' => 'admin', 'PM' => 'projectManager', 'QD' => 'QD', 'RD' => 'RD', 'type' => 'sprint', 'project' => 0),
    2 => (object)array('id' => 2, 'program' => 0, 'acl' => 'private', 'PO' => 'admin', 'PM' => 'projectManager', 'QD' => 'QD', 'RD' => 'RD', 'type' => 'sprint', 'project' => 0),
    3 => (object)array('id' => 3, 'program' => 0, 'acl' => 'private', 'PO' => 'admin', 'PM' => 'projectManager', 'QD' => 'QD', 'RD' => 'RD', 'type' => 'sprint', 'project' => 0)
);

$teams = array(
    'execution' => array(
        1 => array('user1' => 'user1', 'user2' => 'user2'),
        2 => array('user3' => 'user3', 'user4' => 'user4'),
        3 => array()
    )
);

$stakeholders = array(
    'execution' => array(
        1 => array('user5' => 'user5'),
        2 => array('user6' => 'user6'),
        3 => array()
    )
);

$whiteList = array(
    'sprint' => array(
        1 => array('user7' => 'user7'),
        2 => array('user8' => 'user8'),
        3 => array()
    )
);

$executionStakeholderGroup = array(
    1 => array('user9' => 'user9'),
    2 => array('user10' => 'user10'),
    3 => array()
);

global $tester;
$userModel = $tester->loadModel('user');
$ref = new ReflectionMethod($userModel, 'getSprintView');
$ref->setAccessible(true);

$result = array();
foreach(array('user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7', 'user8', 'user9', 'user10', 'admin') as $account)
{
    $result[$account] = $ref->invokeArgs($userModel, array($account, $allSprints, array(), $teams, $stakeholders, $whiteList, $executionStakeholderGroup));
}

r($result['user1'])  && p() && e(1); // 查看user1可访问的执行
r($result['user2'])  && p() && e(1); // 查看user2可访问的执行
r($result['user3'])  && p() && e(2); // 查看user3可访问的执行
r($result['user4'])  && p() && e(2); // 查看user4可访问的执行
r($result['user5'])  && p() && e(1); // 查看user5可访问的执行
r($result['user6'])  && p() && e(2); // 查看user6可访问的执行
r($result['user7'])  && p() && e(1); // 查看user7可访问的执行
r($result['user8'])  && p() && e(2); // 查看user8可访问的执行
r($result['user9'])  && p() && e(1); // 查看user9可访问的执行
r($result['user10']) && p() && e(2); // 查看user10可访问的执行
r($result['admin'])  && p() && e('1,2,3'); // 查看admin可访问的执行