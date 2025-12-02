#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getNoProductList();
timeout=0
cid=17835

- 查询所有没有产品的项目数量 @5
- 查询没有产品的ID为1的项目详情
 - 第1条的name属性 @敏捷项目1
 - 第1条的code属性 @code1
- 查询没有产品的ID为3的项目详情
 - 第3条的name属性 @看板项目3
 - 第3条的code属性 @code3
- 查询没有产品的ID为5的项目详情
 - 第5条的name属性 @瀑布项目5
 - 第5条的code属性 @code5
- 查询没有产品的ID为7的项目详情
 - 第7条的name属性 @敏捷项目7
 - 第7条的code属性 @code7
- 查询没有产品的ID为9的项目详情
 - 第9条的name属性 @看板项目9
 - 第9条的code属性 @code9

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(10);

$projectproduct = zenData('projectproduct');
$projectproduct->product->range('1-50');
$projectproduct->project->range('1-50');
$projectproduct->gen(10);

global $tester;
$tester->loadModel('project');
$result = $tester->project->getNoProductList();

r(count($result)) && p()              && e('5');               // 查询所有没有产品的项目数量
r($result)        && p('1:name,code') && e('敏捷项目1,code1'); // 查询没有产品的ID为1的项目详情
r($result)        && p('3:name,code') && e('看板项目3,code3'); // 查询没有产品的ID为3的项目详情
r($result)        && p('5:name,code') && e('瀑布项目5,code5'); // 查询没有产品的ID为5的项目详情
r($result)        && p('7:name,code') && e('敏捷项目7,code7'); // 查询没有产品的ID为7的项目详情
r($result)        && p('9:name,code') && e('看板项目9,code9'); // 查询没有产品的ID为9的项目详情
