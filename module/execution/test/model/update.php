#!/usr/bin/env php
<?php

/**

title=测试executionModel->update();
timeout=0
cid=16375

- 测试重复迭代code第code条的0属性 @『项目代号』已经有『执行2』这条记录了。
- 测试修改迭代名称
 - 第0条的field属性 @name
 - 第0条的old属性 @执行3
 - 第0条的new属性 @迭代名修改
- 测试修改迭代项目为瀑布项目
 - 第0条的field属性 @project
 - 第0条的old属性 @0
 - 第0条的new属性 @1
- 测试修改迭代项目为看板项目
 - 第0条的field属性 @project
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 测试修改迭代code
 - 第0条的field属性 @code
 - 第0条的old属性 @执行1
 - 第0条的new属性 @code修改
- 测试修改迭代工作日
 - 第0条的field属性 @days
 - 第0条的old属性 @0
 - 第0条的new属性 @5
- 测试修改迭代类型
 - 第0条的field属性 @lifetime
 - 第0条的old属性 @~~
 - 第0条的new属性 @long
- 测试修改迭代状态为wait
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @wait
- 测试修改迭代状态为closed
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @closed
- 测试修改名称为空第name条的0属性 @『迭代名称』不能为空。
- 测试无修改 @没有数据更新

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
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

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

zenData('lang')->gen(0);

su('admin');

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
$repeatcode     = array('name' => '迭代1', 'code' => '执行2');

$execution = new executionModelTest();
$execution->executionModel->lang->projectCommon   = '项目';
$execution->executionModel->lang->executionCommon = '迭代';
$execution->executionModel->config->setCode       = 1;
include($execution->executionModel->app->getBasePath() . 'module/execution/lang/zh-cn.php');

r($execution->updateObject($executionIDList[0], $repeatcode))     && p('code:0')          && e('『项目代号』已经有『执行2』这条记录了。'); // 测试重复迭代code
r($execution->updateObject($executionIDList[0], $changeName))     && p('0:field,old,new') && e('name,执行3,迭代名修改');                   // 测试修改迭代名称
r($execution->updateObject($executionIDList[0], $changeStage))    && p('0:field,old,new') && e('project,0,1');                             // 测试修改迭代项目为瀑布项目
r($execution->updateObject($executionIDList[0], $changeKanban))   && p('0:field,old,new') && e('project,1,2');                             // 测试修改迭代项目为看板项目
r($execution->updateObject($executionIDList[1], $changeCode))     && p('0:field,old,new') && e('code,执行1,code修改');                     // 测试修改迭代code
r($execution->updateObject($executionIDList[1], $changeDays))     && p('0:field,old,new') && e('days,0,5');                                // 测试修改迭代工作日
r($execution->updateObject($executionIDList[1], $changeLifetime)) && p('0:field,old,new') && e('lifetime,~~,long');                        // 测试修改迭代类型
r($execution->updateObject($executionIDList[1], $changeDoing))    && p('0:field,old,new') && e('status,doing,wait');                       // 测试修改迭代状态为wait
r($execution->updateObject($executionIDList[1], $changeClosed))   && p('0:field,old,new') && e('status,wait,closed');                      // 测试修改迭代状态为closed
r($execution->updateObject($executionIDList[1], $noName))         && p('name:0')          && e('『迭代名称』不能为空。');                  // 测试修改名称为空
r($execution->updateObject($executionIDList[1], $noChange))       && p()                  && e('没有数据更新');                            // 测试无修改