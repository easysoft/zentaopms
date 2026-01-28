#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

/**

title=测试 caselibTao->insertImportedCase();
timeout=0
cid=15539

- 测试初始化一条导入的用例1
 - 第0条的title属性 @用例1
 - 第0条的pri属性 @3
 - 第1条的title属性 @用例2
 - 第1条的pri属性 @3
- 测试title为空2
 - 第title条的0属性 @1行的“用例名称”是必填字段，不能为空
 - 第title条的1属性 @2行的“用例名称”是必填字段，不能为空
- 测试type为空4
 - 第type条的0属性 @1行的“用例类型”是必填字段，不能为空
 - 第type条的1属性 @2行的“用例类型”是必填字段，不能为空

*/

$data1 = new stdclass();
$data1->lib            = array(1, 1);
$data1->module         = array(1, 1);
$data1->title          = array('用例1', '用例2');
$data1->pri            = array(3, 3);
$data1->type           = array('feature', 'feature');
$data1->stage          = array(array(''), array());
$data1->keywords       = array('关键词1', '关键词2');
$data1->precondition   = array('前置条件1', '前置条件2');
$data1->steps          = array('', '');
$data1->stepTypes      = array('', '');
$data1->expects        = array('', '');

$data2 = clone $data1;
$data2->title = array('', '');

$data3 = clone $data1;
$data3->type = array('', '');

$caselib = new caselibTaoTest();
r($caselib->initImportedCaseTest($data1)) && p('0:title,pri;1:title,pri') && e('用例1,3,用例2,3');                                                         // 测试初始化一条导入的用例1
r($caselib->initImportedCaseTest($data2)) && p('title:0,1')               && e('1行的“用例名称”是必填字段，不能为空,2行的“用例名称”是必填字段，不能为空'); // 测试title为空2
r($caselib->initImportedCaseTest($data3)) && p('type:0,1')                && e('1行的“用例类型”是必填字段，不能为空,2行的“用例类型”是必填字段，不能为空'); // 测试type为空4
