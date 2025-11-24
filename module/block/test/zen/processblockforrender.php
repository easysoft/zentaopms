#!/usr/bin/env php
<?php

/**

title=测试 blockZen::processBlockForRender();
timeout=0
cid=0

- 步骤1：正常情况，验证宽度设置第0条的width属性 @1
- 步骤3：参数处理，验证默认高度第0条的height属性 @3
- 步骤4：边界值，空数组 @0
- 步骤5：位置设置，top自动计算第0条的top属性 @-1
- 步骤6：验证返回区块数量 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 4. 构建测试数据
$normalBlocks = array();
$block1 = new stdClass();
$block1->id = 1;
$block1->module = 'welcome';
$block1->code = 'welcome';
$block1->width = 1;
$block1->height = 3;
$block1->left = '';
$block1->top = 0;
$block1->params = '{"num":5}';
$normalBlocks[] = $block1;

$paramBlocks = array();
$block2 = new stdClass();
$block2->id = 2;
$block2->module = 'welcome';
$block2->code = 'welcome';
$block2->width = 2;
$block2->height = '';
$block2->left = '';
$block2->top = '';
$block2->params = '{"num":8}';
$paramBlocks[] = $block2;

$emptyBlocks = array();

$positionBlocks = array();
$block3 = new stdClass();
$block3->id = 3;
$block3->module = 'welcome';
$block3->code = 'welcome';
$block3->width = 1;
$block3->height = 3;
$block3->left = '';
$block3->top = 0;
$block3->params = '{"count":5}';
$positionBlocks[] = $block3;

$multiBlocks = array();
$block4 = new stdClass();
$block4->id = 4;
$block4->module = 'welcome';
$block4->code = 'welcome';
$block4->width = 2;
$block4->height = 4;
$block4->left = 1;
$block4->top = -1;
$block4->params = '{"count":10}';
$multiBlocks[] = $block4;

$block5 = new stdClass();
$block5->id = 5;
$block5->module = 'welcome';
$block5->code = 'welcome';
$block5->width = 2;
$block5->height = '';
$block5->left = '';
$block5->top = '';
$block5->params = '{"num":3}';
$multiBlocks[] = $block5;

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->processBlockForRenderTest($normalBlocks, 1)) && p('0:width') && e('1'); // 步骤1：正常情况，验证宽度设置
r($blockTest->processBlockForRenderTest($paramBlocks, 1)) && p('0:height') && e('3'); // 步骤3：参数处理，验证默认高度
r($blockTest->processBlockForRenderTest($emptyBlocks, 1)) && p() && e('0'); // 步骤4：边界值，空数组
r($blockTest->processBlockForRenderTest($positionBlocks, 1)) && p('0:top') && e('-1'); // 步骤5：位置设置，top自动计算
r(count($blockTest->processBlockForRenderTest($multiBlocks, 1))) && p() && e('2'); // 步骤6：验证返回区块数量