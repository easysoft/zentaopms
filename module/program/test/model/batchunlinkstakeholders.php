#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
/**

title=测试 programModel::batchUnlinkStakeholders();
timeout=0
cid=17673

- 测试批量解除项目集1与干系人的关联 @1
- 测试批量解除项目集1与干系人的关联 @1
- 测试批量解除项目集2与干系人的关联 @1
- 测试批量解除项目集2与干系人的关联 @1
- 测试批量解除项目集3与干系人的关联 @1
- 测试批量解除项目集3与干系人的关联 @1

*/

zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(20);
zenData('product')->loadYaml('product')->gen(20);
zenData('stakeholder')->loadYaml('stakeholder')->gen(20);
su('admin');

$programIdList     = array(1, 2, 3);
$stakeholderIdList = array(array(1, 2, 3, 4, 5), array(9, 10), array(17, 18, 19));

global $tester;
$tester->loadModel('program');
r($tester->program->batchUnlinkStakeholders($programIdList[0], $stakeholderIdList[0])) && p() && e('1'); // 测试批量解除项目集1与干系人的关联
r($tester->program->batchUnlinkStakeholders($programIdList[0], $stakeholderIdList[1])) && p() && e('1'); // 测试批量解除项目集1与干系人的关联
r($tester->program->batchUnlinkStakeholders($programIdList[1], $stakeholderIdList[0])) && p() && e('1'); // 测试批量解除项目集2与干系人的关联
r($tester->program->batchUnlinkStakeholders($programIdList[1], $stakeholderIdList[1])) && p() && e('1'); // 测试批量解除项目集2与干系人的关联
r($tester->program->batchUnlinkStakeholders($programIdList[2], $stakeholderIdList[0])) && p() && e('1'); // 测试批量解除项目集3与干系人的关联
r($tester->program->batchUnlinkStakeholders($programIdList[2], $stakeholderIdList[2])) && p() && e('1'); // 测试批量解除项目集3与干系人的关联
