#!/usr/bin/env php
<?php

/**

title=测试 docZen::initLibForTeamSpace();
timeout=0
cid=16193

- 步骤1:验证创建了团队空间库 @1
- 步骤2:验证类型属性type @custom
- 步骤3:验证acl属性acl @open
- 步骤4:验证只有一个团队空间库 @1
- 步骤5:验证user1创建了团队空间库 @1
- 步骤6:验证addedBy属性addedBy @user1
- 步骤7:验证vision属性vision @rnd

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('doclib')->loadYaml('initlibforteamspace', false, 2)->gen(10);

su('admin');

global $tester;
$docTest = new docZenTest();

$tester->dao->delete()->from(TABLE_DOCLIB)->where('type')->eq('custom')->exec();
$docTest->initLibForTeamSpaceTest();

r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch('count')) && p() && e('1'); // 步骤1:验证创建了团队空间库
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch()) && p('type') && e('custom'); // 步骤2:验证类型
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch()) && p('acl') && e('open'); // 步骤3:验证acl
$docTest->initLibForTeamSpaceTest();
r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch('count')) && p() && e('1'); // 步骤4:验证只有一个团队空间库

su('user1');
$tester->dao->delete()->from(TABLE_DOCLIB)->where('type')->eq('custom')->exec();
$docTest->initLibForTeamSpaceTest();

r($tester->dao->select('count(*) as count')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch('count')) && p() && e('1'); // 步骤5:验证user1创建了团队空间库
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch()) && p('addedBy') && e('user1'); // 步骤6:验证addedBy
r($tester->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('custom')->fetch()) && p('vision') && e('rnd'); // 步骤7:验证vision