#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->appendCountParams();
cid=1
pid=1

测试新增count param >> count:{name:数量,default:20,control:input};
测试新增count param 和 param test >> test:test;count:{name:数量,default:20,control:input};
测试新增count param 和 param par >> par:par;count:{name:数量,default:20,control:input};
测试新增count param 和 param append >> append:append;count:{name:数量,default:20,control:input};
测试新增count param 和 param 空 >> count:{name:数量,default:20,control:input};

*/
$param1 = new stdclass();
$param1->test = 'test';

$param2 = new stdclass();
$param2->par = 'par';

$param3 = new stdclass();
$param3->append = 'append';

$param4 = new stdclass();

$block = new blockTest();

r($block->appendCountParamsTest())        && p() && e('count:{name:数量,default:20,control:input};');               // 测试新增count param
r($block->appendCountParamsTest($param1)) && p() && e('test:test;count:{name:数量,default:20,control:input};');     // 测试新增count param 和 param test
r($block->appendCountParamsTest($param2)) && p() && e('par:par;count:{name:数量,default:20,control:input};');       // 测试新增count param 和 param par
r($block->appendCountParamsTest($param3)) && p() && e('append:append;count:{name:数量,default:20,control:input};'); // 测试新增count param 和 param append
r($block->appendCountParamsTest($param4)) && p() && e('count:{name:数量,default:20,control:input};');               // 测试新增count param 和 param 空