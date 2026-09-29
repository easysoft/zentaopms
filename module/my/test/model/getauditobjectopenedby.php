#!/usr/bin/env php
<?php

/**

title=测试 myModel::getAuditObjectOpenedBy();
timeout=0
cid=0

- 步骤1：对象存在openedBy时返回openedBy @admin
- 步骤2：openedBy为空时返回createdBy @user1
- 步骤3：openedBy和createdBy为空时返回account @user2
- 步骤4：openedBy优先于createdBy和account @admin
- 步骤5：三个字段都为空时返回空字符串 @0
- 步骤6：对象不存在这三个字段时返回空字符串 @0
- 步骤7：字段值为null时返回空字符串 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);
su('admin');

$my = new myModelTest();

$object1 = new stdclass();
$object1->openedBy = 'admin';

$object2 = new stdclass();
$object2->openedBy  = '';
$object2->createdBy = 'user1';

$object3 = new stdclass();
$object3->openedBy  = '';
$object3->createdBy = '';
$object3->account   = 'user2';

$object4 = new stdclass();
$object4->openedBy  = 'admin';
$object4->createdBy = 'user1';
$object4->account   = 'user2';

$object5 = new stdclass();
$object5->openedBy  = '';
$object5->createdBy = '';
$object5->account   = '';

$object6 = new stdclass();
$object6->id = 1;

$object7 = new stdclass();
$object7->openedBy  = null;
$object7->createdBy = null;
$object7->account   = null;

r($my->getAuditObjectOpenedByTest($object1)) && p() && e('admin'); // 步骤1：对象存在openedBy时返回openedBy
r($my->getAuditObjectOpenedByTest($object2)) && p() && e('user1'); // 步骤2：openedBy为空时返回createdBy
r($my->getAuditObjectOpenedByTest($object3)) && p() && e('user2'); // 步骤3：openedBy和createdBy为空时返回account
r($my->getAuditObjectOpenedByTest($object4)) && p() && e('admin'); // 步骤4：openedBy优先于createdBy和account
r(strlen($my->getAuditObjectOpenedByTest($object5))) && p() && e('0'); // 步骤5：三个字段都为空时返回空字符串
r(strlen($my->getAuditObjectOpenedByTest($object6))) && p() && e('0'); // 步骤6：对象不存在这三个字段时返回空字符串
r(strlen($my->getAuditObjectOpenedByTest($object7))) && p() && e('0'); // 步骤7：字段值为null时返回空字符串
