#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCasesForBrowse();
timeout=0
cid=0

- 步骤1:空数组输入 @0
- 步骤2:无场景的用例
 - 第0条的id属性 @case_1
 - 第0条的parent属性 @0
 - 第0条的isScene属性 @~~
- 步骤3:有场景的用例 @3
- 步骤4:HTML转义标题处理第0条的title属性 @测试用例5<html>
- 步骤5:场景被删除的边界情况
 - 第0条的id属性 @case_6
 - 第0条的parent属性 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$scene = zenData('scene');
$scene->id->range('1-5');
$scene->product->range('1{5}');
$scene->title->range('场景1,场景2,场景3,场景4,场景5');
$scene->parent->range('0,0,1,1,0');
$scene->grade->range('1,1,2,2,1');
$scene->path->range(',1,;,2,;,1,3,;,1,4,;,5,');
$scene->deleted->range('0{5}');
$scene->gen(5);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testcaseTest = new testcaseZenTest();

// 5. 强制要求:必须包含至少5个测试步骤

// 步骤1:空数组输入
$emptyCases = array();
r($testcaseTest->processCasesForBrowseTest($emptyCases)) && p() && e('0'); // 步骤1:空数组输入

// 步骤2:无场景的用例
$case1 = new stdClass();
$case1->id = 1;
$case1->title = '测试用例1';
$case1->scene = 0;
$case1->status = 'normal';
$case1->pri = 1;

$case2 = new stdClass();
$case2->id = 2;
$case2->title = '测试用例2';
$case2->scene = 0;
$case2->status = 'wait';
$case2->pri = 2;

$casesWithoutScene = array($case1, $case2);
r($testcaseTest->processCasesForBrowseTest($casesWithoutScene)) && p('0:id;0:parent;0:isScene') && e('case_1;0;~~'); // 步骤2:无场景的用例

// 步骤3:有场景的用例
$case3 = new stdClass();
$case3->id = 3;
$case3->title = '测试用例3';
$case3->scene = 1;
$case3->status = 'normal';
$case3->pri = 1;

$case4 = new stdClass();
$case4->id = 4;
$case4->title = '测试用例4';
$case4->scene = 1;
$case4->status = 'wait';
$case4->pri = 2;

$casesWithScene = array($case3, $case4);
r(count($testcaseTest->processCasesForBrowseTest($casesWithScene))) && p() && e('3'); // 步骤3:有场景的用例

// 步骤4:HTML转义标题处理
$case5 = new stdClass();
$case5->id = 5;
$case5->title = '测试用例5&lt;html&gt;';
$case5->scene = 0;
$case5->status = 'normal';
$case5->pri = 1;

$casesWithHtml = array($case5);
r($testcaseTest->processCasesForBrowseTest($casesWithHtml)) && p('0:title') && e('测试用例5<html>'); // 步骤4:HTML转义标题处理

// 步骤5:场景被删除的边界情况
$case6 = new stdClass();
$case6->id = 6;
$case6->title = '测试用例6';
$case6->scene = 99;
$case6->status = 'normal';
$case6->pri = 1;

$casesWithDeletedScene = array($case6);
r($testcaseTest->processCasesForBrowseTest($casesWithDeletedScene)) && p('0:id;0:parent') && e('case_6;0'); // 步骤5:场景被删除的边界情况