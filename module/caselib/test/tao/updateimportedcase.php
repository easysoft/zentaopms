#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

su('admin');
zdTable('case')->gen(10);

/**

title=测试 caselibTao->updateImportedCase();
timeout=0
cid=1

- 执行caselib模块的updateImportedCaseTest方法，参数是0, $caseData, $data, $forceNotReview[0]
 - 属性title @用例1更新
 - 第steps[0]条的desc属性 @步骤一更新

*/

$data = new stdclass();
$data->id = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
$data->desc[][1]     = '步骤一更新';
$data->expect[][1]   = '预期一更新';
$data->stepType[][1] = 'item';

$caseData = new stdclass();
$caseData->title = '用例1更新';
$caseData->lib   = 0;

$forceNotReview = array(true, false);

$caselib = new caselibTest();
r($caselib->updateImportedCaseTest(0, $caseData, $data, $forceNotReview[0])) && p('title;steps[0]:desc') && e('用例1更新;步骤一更新');
