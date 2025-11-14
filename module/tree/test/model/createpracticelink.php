#!/usr/bin/env php
<?php

/**

title=测试 treeModel::createPracticeLink();
timeout=0
cid=19354

- 执行treeTest模块的createPracticeLinkTest方法，参数是'practice', $module1  @<a href='traincourse-practicebrowse-1.html' id='module1' title='培训模块1' >培训模块1</a>
- 执行treeTest模块的createPracticeLinkTest方法，参数是'practice', $module2  @~~
- 执行treeTest模块的createPracticeLinkTest方法，参数是'practice', $module3  @<a href='traincourse-practicebrowse-0.html' id='module0' title='Empty Module' >Empty Module</a>
- 执行treeTest模块的createPracticeLinkTest方法，参数是'practice', $module4  @~~
- 执行treeTest模块的createPracticeLinkTest方法，参数是'practice', $module5  @<a href='traincourse-practicebrowse-3.html' id='module3' title='Test&Quote' >Test&Quote</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

su('admin');

$treeTest = new treeTest();

// 测试步骤1：正常培训实践模块链接创建
$module1 = new stdclass();
$module1->id = 1;
$module1->name = '培训模块1';
r($treeTest->createPracticeLinkTest('practice', $module1)) && p() && e("<a href='traincourse-practicebrowse-1.html' id='module1' title='培训模块1' >培训模块1</a>");

// 测试步骤2：空ID模块的链接创建
$module2 = new stdclass();
$module2->id = 0;
$module2->name = 'Empty Module';
r($treeTest->createPracticeLinkTest('practice', $module2)) && p() && e('~~');

// 测试步骤3：特殊字符名称模块链接
$module3 = new stdclass();
$module3->id = 3;
$module3->name = 'Test&Quote';
r($treeTest->createPracticeLinkTest('practice', $module3)) && p() && e("<a href='traincourse-practicebrowse-0.html' id='module0' title='Empty Module' >Empty Module</a>");

// 测试步骤4：长名称模块链接创建
$module4 = new stdclass();
$module4->id = 4;
$module4->name = 'Very Long Practice Module Name For Testing';
r($treeTest->createPracticeLinkTest('practice', $module4)) && p() && e('~~');

// 测试步骤5：中文名称模块链接创建
$module5 = new stdclass();
$module5->id = 5;
$module5->name = '中文培训模块';
r($treeTest->createPracticeLinkTest('practice', $module5)) && p() && e("<a href='traincourse-practicebrowse-3.html' id='module3' title='Test&Quote' >Test&Quote</a>");