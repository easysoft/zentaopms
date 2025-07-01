#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->buildComponentList();
timeout=0
cid=1

- 测试带有空元素的情况下，生成的组件列表数量是否正确。 @1

*/

$screen = new screenTest();

$screenData = $tester->dao->select('*')->from(TABLE_SCREEN)->where('id')->eq(1)->fetch();
$scheme = json_decode($screenData->scheme);
$componentList = $scheme->componentList;

r(count($componentList)) && p('') && e('44');  //测试组件列表数量是否正确。

$component = $componentList[0];
r($component->chartConfig->chartKey) && p('') && e('group');  //测试组件类型是否正确。
r($component->id) && p('') && e('5scfjzqsbzo000');  //测试组件id是否正确。
r($component->isGroup) && p('') && e('1');  //测试组件是否为组。
r(isset($component->groupList)) && p('') && e('1');  //测试组件是否为组。