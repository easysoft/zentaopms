#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDoclibTypeParams();
timeout=0
cid=0

- 执行actionTest模块的getDoclibTypeParamsTest方法，参数是$action1 
 -  @api
 - 属性1 @index
- 执行actionTest模块的getDoclibTypeParamsTest方法，参数是$action2 
 -  @doc
 - 属性1 @productspace
- 执行actionTest模块的getDoclibTypeParamsTest方法，参数是$action3 
 -  @doc
 - 属性1 @projectspace
- 执行actionTest模块的getDoclibTypeParamsTest方法，参数是$action4 
 -  @doc
 - 属性1 @productspace
- 执行actionTest模块的getDoclibTypeParamsTest方法，参数是$action5 
 -  @doc
 - 属性1 @teamspace

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 手动设置doc模块配置
global $config;
if(!isset($config->doc)) $config->doc = new stdClass();
if(!isset($config->doc->spaceMethod)) {
    $config->doc->spaceMethod = array();
    $config->doc->spaceMethod['mine']      = 'myspace';
    $config->doc->spaceMethod['view']      = 'myspace';
    $config->doc->spaceMethod['collect']   = 'myspace';
    $config->doc->spaceMethod['createdby'] = 'myspace';
    $config->doc->spaceMethod['editedby']  = 'myspace';
    $config->doc->spaceMethod['product']   = 'productspace';
    $config->doc->spaceMethod['project']   = 'projectspace';
    $config->doc->spaceMethod['execution'] = 'projectspace';
    $config->doc->spaceMethod['custom']    = 'teamspace';
}

$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->type->range('api{2},product{2},project{2},execution{2},custom{2}');
$doclib->product->range('0,1,0,2,0,0,0,0,0,0');
$doclib->project->range('0,0,1,0,0,1,0,0,0,0');
$doclib->execution->range('0,0,0,0,1,0,0,0,0,0');
$doclib->deleted->range('0{8},1{2}');
$doclib->gen(10);

su('admin');

$actionTest = new actionTaoTest();

// 测试步骤1：api类型文档库（无关联，默认api模块）
$action1 = new stdClass();
$action1->objectID = 1;
r($actionTest->getDoclibTypeParamsTest($action1)) && p('0,1') && e('api,index');

// 测试步骤2：api类型文档库（有product关联）
$action2 = new stdClass();
$action2->objectID = 2;
r($actionTest->getDoclibTypeParamsTest($action2)) && p('0,1') && e('doc,productspace');

// 测试步骤3：api类型文档库（有project关联）
$action3 = new stdClass();
$action3->objectID = 6;
r($actionTest->getDoclibTypeParamsTest($action3)) && p('0,1') && e('doc,projectspace');

// 测试步骤4：product类型文档库
$action4 = new stdClass();
$action4->objectID = 3;
r($actionTest->getDoclibTypeParamsTest($action4)) && p('0,1') && e('doc,productspace');

// 测试步骤5：custom类型文档库
$action5 = new stdClass();
$action5->objectID = 9;
r($actionTest->getDoclibTypeParamsTest($action5)) && p('0,1') && e('doc,teamspace');