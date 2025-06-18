#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

/**

title=测试 testcaseModel->preProcessScenesForBrowse();
timeout=0
cid=1

- 无用例的场景
 - 第0条的name属性 @场景1
 - 第0条的hasCase属性 @0
- 包含用例的场景
 - 第2条的name属性 @场景2
 - 第2条的hasCase属性 @1
- 包含无用例的子场景的场景
 - 第1条的name属性 @场景3
 - 第1条的hasCase属性 @0
- 包含有用例的子场景的场景
 - 第2条的name属性 @场景4
 - 第2条的hasCase属性 @1

*/

su('admin');

$case1 = new stdclass();
$case1->id   = 1;
$case1->name = '用例1';

$case2 = new stdclass();
$case2->id   = 2;
$case2->name = '用例2';

$case3 = new stdclass();
$case3->id   = 3;
$case3->name = '用例3';

$childScene1 = new stdclass();
$childScene1->name     = '子场景1';
$childScene1->cases    = array();
$childScene1->children = array();

$childScene2 = new stdclass();
$childScene2->name  = '子场景2';
$childScene2->cases = array(1 => $case1);

$scene1 = new stdclass();
$scene1->name     = '场景1';
$scene1->cases    = array();
$scene1->children = array();

$scene2 = new stdclass();
$scene2->name     = '场景2';
$scene2->cases    = array(1 => $case1, 2 => $case2);
$scene2->children = array();

$scene3 = new stdclass();
$scene3->name     = '场景3';
$scene3->cases    = array();
$scene3->children = array(1 => $childScene1);

$scene4 = new stdclass();
$scene4->name     = '场景4';
$scene4->cases    = array();
$scene4->children = array(1 => $childScene2);

$scenes1 = array(1 => $scene1);
$scenes2 = array(2 => $scene2);
$scenes3 = array(3 => $scene3);
$scenes4 = array(4 => $scene4);

$caseTester = new testcaseTest();

r($caseTester->preProcessScenesForBrowseTest($scenes1)) && p('0:name,hasCase') && e('场景1,0'); // 无用例的场景
r($caseTester->preProcessScenesForBrowseTest($scenes2)) && p('2:name,hasCase') && e('场景2,1'); // 包含用例的场景
r($caseTester->preProcessScenesForBrowseTest($scenes3)) && p('1:name,hasCase') && e('场景3,0'); // 包含无用例的子场景的场景
r($caseTester->preProcessScenesForBrowseTest($scenes4)) && p('2:name,hasCase') && e('场景4,1'); // 包含有用例的子场景的场景