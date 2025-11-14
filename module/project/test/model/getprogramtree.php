#!/usr/bin/env php
<?php
/**

title=测试 projectModel::getProgramTree;
timeout=0
cid=17844

- 获取id为1项目集的数据
 - 第0条的id属性 @1
 - 第0条的name属性 @项目集1
 - 第0条的parent属性 @0
- 获取id为2项目集的数据
 - 第1条的id属性 @2
 - 第1条的name属性 @项目集2
 - 第1条的parent属性 @0
- 查询项目集数量 @9

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(20);
su('admin');

global $tester;
$tester->loadModel('project');
$programs = $tester->project->getProgramTree('all');

r($programs)        && p('0:id,name,parent') && e('1,项目集1,0'); // 获取id为1项目集的数据
r($programs)        && p('1:id,name,parent') && e('2,项目集2,0'); // 获取id为2项目集的数据
r(count($programs)) && p()                   && e('9');           // 查询项目集数量
