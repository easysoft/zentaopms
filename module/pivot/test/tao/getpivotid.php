#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getPivotID();
timeout=0
cid=0

- 步骤1：管理员获取分组最新可用透视表 @824
- 步骤2：创建者与白名单用户获取同分组最新可用透视表 @824
- 步骤3：普通用户获取当前环境允许的分组透视表 @1
- 步骤4：其他分组返回当前环境允许的结果 @1
- 步骤5：仅有草稿或删除数据时返回 0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $tester;
$dao = $tester->dao;

$pivotIDList = array(1, 821, 822, 823, 824, 825, 826, 827);
$dao->delete()->from(TABLE_PIVOT)->where('id')->in($pivotIDList)->exec();

$pivotList = array(
    array('id' => 1,   'name' => '权限守卫透视表', 'version' => '1', 'group' => '0',  'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 821, 'name' => '开放透视表',     'version' => '1', 'group' => '31', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 822, 'name' => '草稿透视表',     'version' => '1', 'group' => '31', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'draft',     'deleted' => '0'),
    array('id' => 823, 'name' => '用户私有透视表', 'version' => '1', 'group' => '31', 'createdBy' => 'user1', 'acl' => 'private', 'whitelist' => '',      'stage' => 'published', 'deleted' => '0'),
    array('id' => 824, 'name' => '白名单透视表',   'version' => '1', 'group' => '31', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'user1', 'stage' => 'published', 'deleted' => '0'),
    array('id' => 825, 'name' => '管理员私有表',   'version' => '1', 'group' => '32', 'createdBy' => 'admin', 'acl' => 'private', 'whitelist' => 'admin', 'stage' => 'published', 'deleted' => '0'),
    array('id' => 826, 'name' => '草稿分组透视表', 'version' => '1', 'group' => '33', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'draft',     'deleted' => '0'),
    array('id' => 827, 'name' => '删除分组透视表', 'version' => '1', 'group' => '33', 'createdBy' => 'admin', 'acl' => 'open',    'whitelist' => '',      'stage' => 'published', 'deleted' => '1')
);

foreach($pivotList as $pivot) $dao->insert(TABLE_PIVOT)->data($pivot)->exec();

$pivotTest = new pivotTaoTest();

su('admin');
r($pivotTest->getPivotIDTest(31)) && p() && e('824');

su('user1');
r($pivotTest->getPivotIDTest(31)) && p() && e('824');

su('user2');
$result31 = $pivotTest->getPivotIDTest(31);
r(in_array($result31, array(821, 824)) ? '1' : '0') && p() && e('1');

su('user1');
$result32 = $pivotTest->getPivotIDTest(32);
r(in_array($result32, array(0, 825)) ? '1' : '0') && p() && e('1');

su('admin');
r($pivotTest->getPivotIDTest(33)) && p() && e('0');
