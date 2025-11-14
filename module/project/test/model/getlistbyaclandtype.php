#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->gen(100);
su('admin');

/**

title=测试 projectModel->getlistbyaclandtype();
timeout=0
cid=17831

- 获取权限为公开类型为项目ID为100的项目属性。
 - 第100条的id属性 @100
 - 第100条的project属性 @10
 - 第100条的type属性 @project
- 获取权限为公开类型为项目集ID为10的项目集属性。
 - 第10条的id属性 @10
 - 第10条的project属性 @0
 - 第10条的type属性 @program
- 获取权限为私有类型为项目ID为98的项目属性。
 - 第98条的id属性 @98
 - 第98条的project属性 @8
 - 第98条的type属性 @project
- 获取权限为私有类型为项目ID为11的项目属性。
 - 第11条的id属性 @11
 - 第11条的project属性 @1
 - 第11条的type属性 @project
- 获取权限为私有类型为项目集ID为8的项目集属性。
 - 第8条的id属性 @8
 - 第8条的project属性 @0
 - 第8条的type属性 @program

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getListByAclAndType('open', 'project'))    && p('100:id,project,type') && e('100,10,project'); // 获取权限为公开类型为项目ID为100的项目属性。
r($tester->project->getListByAclAndType('open', 'program'))    && p('10:id,project,type')  && e('10,0,program');   // 获取权限为公开类型为项目集ID为10的项目集属性。
r($tester->project->getListByAclAndType('private', 'project')) && p('98:id,project,type')  && e('98,8,project');   // 获取权限为私有类型为项目ID为98的项目属性。
r($tester->project->getListByAclAndType('private', 'project')) && p('11:id,project,type')  && e('11,1,project');   // 获取权限为私有类型为项目ID为11的项目属性。
r($tester->project->getListByAclAndType('private', 'program')) && p('8:id,project,type')   && e('8,0,program');    // 获取权限为私有类型为项目集ID为8的项目集属性。
