#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(50);
su('admin');

/**

title=测试 projectModel->getPairs();
timeout=0
cid=17837

- 查找管理员可查看的所有项目数量 @40
- 查找管理员可查看的所有vision所有项目数量 @40
- 查找管理员可查看的当前vision所有项目数量 @40
- 查找管理员可查看的所有无产品项目数量 @0
- 查找管理员可查看的所有未关闭项目数量 @35
- 查找管理员可查看的所有无迭代项目数量 @0
- 查找管理员可查看的所有有迭代项目数量 @40
- 查找管理员可查看的所有非看板项目数量 @32
- 查找管理员可查看的所有有权限项目数量 @40

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getPairs()))                     && p()     && e('40'); // 查找管理员可查看的所有项目数量
r(count($tester->project->getPairs('false')))              && p()     && e('40'); // 查找管理员可查看的所有vision所有项目数量
r(count($tester->project->getPairs('true')))               && p()     && e('40'); // 查找管理员可查看的当前vision所有项目数量
r(count($tester->project->getPairs('false', 'noproduct'))) && p()     && e('0');  // 查找管理员可查看的所有无产品项目数量
r(count($tester->project->getPairs('false', 'noclosed')))  && p()     && e('35'); // 查找管理员可查看的所有未关闭项目数量
r(count($tester->project->getPairs('false', 'nosprint')))  && p()     && e('0');  // 查找管理员可查看的所有无迭代项目数量
r(count($tester->project->getPairs('false', 'multiple')))  && p()     && e('40'); // 查找管理员可查看的所有有迭代项目数量
r(count($tester->project->getPairs('false', 'nokanban')))  && p()     && e('32'); // 查找管理员可查看的所有非看板项目数量
r(count($tester->project->getPairs('false', 'haspriv')))   && p()     && e('40'); // 查找管理员可查看的所有有权限项目数量
