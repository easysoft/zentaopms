#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::checkAccess();
timeout=0
cid=0

- 执行pivotTest模块的checkAccessTest方法，参数是901  @access_granted
- 执行pivotTest模块的checkAccessTest方法，参数是902  @access_granted
- 执行pivotTest模块的checkAccessTest方法，参数是903  @access_granted
- 执行pivotTest模块的checkAccessTest方法，参数是904  @access_denied
- 执行pivotTest模块的checkAccessTest方法，参数是999  @access_denied

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

global $tester;
$dao = $tester->dao;

$pivotIDList = array(901, 902, 903, 904);
$dao->delete()->from(TABLE_PIVOT)->where('id')->in($pivotIDList)->exec();

$pivotList = array(
    array('id' => 901, 'name' => '开放透视表',   'version' => '1', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 902, 'name' => '用户私有表',   'version' => '1', 'createdBy' => 'user1', 'acl' => 'private', 'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 903, 'name' => '白名单透视表', 'version' => '1', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'user2', 'stage' => 'published', 'deleted' => '0'),
    array('id' => 904, 'name' => '受限透视表',   'version' => '1', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'admin', 'stage' => 'published', 'deleted' => '0')
);

foreach($pivotList as $pivot) $dao->insert(TABLE_PIVOT)->data($pivot)->exec();

$pivotTest = new pivotModelTest();

su('admin');
r($pivotTest->checkAccessTest(901)) && p() && e('access_granted');

su('user1');
r($pivotTest->checkAccessTest(902)) && p() && e('access_granted');

su('user2');
r($pivotTest->checkAccessTest(903)) && p() && e('access_granted');

su('user1');
r($pivotTest->checkAccessTest(904)) && p() && e('access_denied');

su('admin');
r($pivotTest->checkAccessTest(999)) && p() && e('access_denied');
