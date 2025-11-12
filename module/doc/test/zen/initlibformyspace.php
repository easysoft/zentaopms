#!/usr/bin/env php
<?php

/**

title=测试 docZen::initLibForMySpace();
timeout=0
cid=0

- 步骤1:验证创建了库 @1
- 步骤2:验证类型属性type @mine
- 步骤3:验证main属性属性main @1
- 步骤4:验证acl属性acl @private
- 步骤5:验证只有一个库 @1
- 步骤6:验证user1创建了库 @1
- 步骤7:验证addedBy属性addedBy @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doclib')->loadYaml('initlibformyspace', false, 2)->gen(10);

su('admin');

global $tester;
$docTest = new docZenTest();

$tester->dao->delete()->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('addedBy')->eq('admin')->exec();
$docTest->initLibForMySpaceTest();

r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('admin')->fetch('count')) && p() && e('1'); // 步骤1:验证创建了库
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('admin')->fetch()) && p('type') && e('mine'); // 步骤2:验证类型
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('admin')->fetch()) && p('main') && e('1'); // 步骤3:验证main属性
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('admin')->fetch()) && p('acl') && e('private'); // 步骤4:验证acl
$docTest->initLibForMySpaceTest();
r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('admin')->fetch('count')) && p() && e('1'); // 步骤5:验证只有一个库

su('user1');
$tester->dao->delete()->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('addedBy')->eq('user1')->exec();
$docTest->initLibForMySpaceTest();

r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('user1')->fetch('count')) && p() && e('1'); // 步骤6:验证user1创建了库
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('mine')->andWhere('main')->eq(1)->andWhere('addedBy')->eq('user1')->fetch()) && p('addedBy') && e('user1'); // 步骤7:验证addedBy