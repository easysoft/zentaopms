#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-11');
$execution->name->setFields(array(
    array('field' => 'name1', 'range' => '项目{2},执行{3},迭代{2},阶段{2},看板{2}'),
    array('field' => 'name2', 'range' => '1-3'),
));
$execution->code->setFields(array(
    array('field' => 'name1', 'range' => '项目{2},执行{3},迭代{2},阶段{2},看板{2}'),
    array('field' => 'name2', 'range' => '1-3'),
));
$execution->type->range('project{2},sprint{5},waterfall{2},kanban{2}');
$execution->status->range('doing{11}');
$execution->parent->range('0');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(11);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

su('admin');

/**

title=测试executionModel->update();
cid=1
pid=1

测试重复迭代code           >> 『迭代代号』已经有『执行2』这条记录了。
测试修改迭代名称           >> name,执行3,迭代名修改
测试修改迭代项目为瀑布项目 >> project,0,1
测试修改迭代项目为看板项目 >> project,1,2
测试修改迭代code           >> code,执行1,code修改
测试修改迭代工作日         >> days,0,5
测试修改迭代类型           >> lifetime,,long
测试修改迭代状态为wait     >> status,doing,wait
测试修改迭代状态为closed   >> status,wait,closed
测试修改名称为空           >> 『迭代名称』不能为空。
测试修改code为空           >> 『迭代代号』不能为空。
测试无修改                 >> 没有数据更新

*/

$executionIDList = array('3','4','5','6','7','8','9','10','11');
$productIDList   = array('2','1','3');

$changeName     = array('name' => '迭代名修改', 'code' => '迭代名修改code');
$changeStage    = array('project' =>'1', 'code' => '修改code');
$changeKanban   = array('project' =>'2', 'code' => '修改code2');
$changeKanban   = array('project' =>'2', 'code' => '修改code3');
$changeCode     = array('code' => 'code修改');
$changeDays     = array('days' => '5');
$changeLifetime = array('lifetime' => 'long');
$changeDoing    = array('status' => 'wait');
$changeClosed   = array('status' => 'closed');
$noChange       = array();
$noName         = array('name' => '');
$noCode         = array('code' => '');
$repeatcode     = array('name' => '迭代1', 'code' => '执行2');

$execution = new executionTest();
r($execution->updateObject($executionIDList[0], $repeatcode))     && p('code:0')          && e('『迭代代号』已经有『执行2』这条记录了。'); // 测试重复迭代code
r($execution->updateObject($executionIDList[0], $changeName))     && p('0:field,old,new') && e('name,执行3,迭代名修改');                   // 测试修改迭代名称
r($execution->updateObject($executionIDList[0], $changeStage))    && p('0:field,old,new') && e('project,0,1');                             // 测试修改迭代项目为瀑布项目
r($execution->updateObject($executionIDList[0], $changeKanban))   && p('0:field,old,new') && e('project,1,2');                             // 测试修改迭代项目为看板项目
r($execution->updateObject($executionIDList[1], $changeCode))     && p('0:field,old,new') && e('code,执行1,code修改');                     // 测试修改迭代code
r($execution->updateObject($executionIDList[1], $changeDays))     && p('0:field,old,new') && e('days,0,5');                                // 测试修改迭代工作日
r($execution->updateObject($executionIDList[1], $changeLifetime)) && p('0:field,old,new') && e('lifetime,,long');                          // 测试修改迭代类型
r($execution->updateObject($executionIDList[1], $changeDoing))    && p('0:field,old,new') && e('status,doing,wait');                       // 测试修改迭代状态为wait
r($execution->updateObject($executionIDList[1], $changeClosed))   && p('0:field,old,new') && e('status,wait,closed');                      // 测试修改迭代状态为closed
r($execution->updateObject($executionIDList[1], $noName))         && p('name:0')          && e('『迭代名称』不能为空。');                  // 测试修改名称为空
r($execution->updateObject($executionIDList[3], $noCode))         && p('code')            && e('『迭代代号』不能为空。');                  // 测试修改code为空
r($execution->updateObject($executionIDList[1], $noChange))       && p()                  && e('没有数据更新');                            // 测试无修改
