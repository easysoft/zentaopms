#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
$project = zenData('project')->gen(100);

/**

title=测试 projectModel::getByIdList();
timeout=0
cid=17820

- 获取projectIdList对应的项目名称
 - 第11条的name属性 @项目11
 - 第12条的name属性 @项目12
 - 第13条的name属性 @项目13

- 获取非项目类型的数据 @0

- 根据ID列表获取项目，判断数量 @6

*/

global $tester;
$tester->loadModel('project');

r(($tester->project->getByIdList(array(11,12,13)))) && p('11:name;12:name;13:name') && e('项目11;项目12;项目13'); //获取projectIdList对应的项目名称
r(count($tester->project->getByIdList(array(8,9,10))))            && p('') && e('0'); //获取非项目类型的数据
r(count($tester->project->getByIdList(array(90,91,92,93,94,95)))) && p('') && e('6'); //根据ID列表获取项目，判断数量