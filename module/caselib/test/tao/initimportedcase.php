#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

su('admin');

/**

title=测试 caselibTao->insertImportedCase();
timeout=0
cid=1

- 测试初始化一条导入的用例第0条的title属性 @用例1
- 测试初始化一条导入的用例第title条的0属性 @1行的“用例名称”是必填字段，不能为空

*/

$data1 = new stdclass();
$data1->lib[]          = 1;
$data1->module[]       = 1;
$data1->title[]        = '用例1';
$data1->pri[]          = 3;
$data1->type[]         = 'feature';
$data1->stage[]        = array('');
$data1->keywords[]     = '关键词1';
$data1->precondition[] = '前置条件1';

$data2 = new stdclass();
$data2->lib[]          = 1;
$data2->module[]       = 1;
$data2->title[]        = '';
$data2->pri[]          = 3;
$data2->type[]         = 'feature';
$data2->stage[]        = array('');
$data2->keywords[]     = '关键词2';
$data2->precondition[] = '前置条件2';

$caselib = new caselibTest();
r($caselib->initImportedCaseTest($data1)) && p('0:title') && e('用例1');                                 // 测试初始化一条导入的用例
r($caselib->initImportedCaseTest($data2)) && p('title:0') && e('1行的“用例名称”是必填字段，不能为空'); // 测试初始化一条导入的用例
