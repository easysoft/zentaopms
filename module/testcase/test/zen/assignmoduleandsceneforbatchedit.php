#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModuleAndSceneForBatchEdit();
timeout=0
cid=19072

- 步骤1：正常情况属性executed @1
- 步骤2：空用例数组属性executed @1
- 步骤3：用例库案例属性executed @1
- 步骤4：不同分支案例属性executed @1
- 步骤5：缺失模块数据属性executed @1
- 步骤6：多个用例属性executed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{5}');
$product->gen(5);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-5');
$case->module->range('1-5');
$case->scene->range('1-5');
$case->story->range('1-10,0{5}');
$case->lib->range('0{8},1{2}');
$case->branch->range('0{10}');
$case->title->range('用例1,用例2,用例3,用例4,用例5,用例6,用例7,用例8,用例9,用例10');
$case->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5');
$module->branch->range('0{10}');
$module->type->range('case{5},story{5}');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->gen(10);

$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1-5');
$scene->branch->range('0{10}');
$scene->module->range('1-5');
$scene->title->range('场景1,场景2,场景3,场景4,场景5,场景6,场景7,场景8,场景9,场景10');
$scene->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 5. 准备测试数据
// 测试数据1：正常情况
$productID1 = 1;
$branch1 = '0';
$branches1 = array('0' => '无分支');
$cases1 = array(
    (object)array('id' => 1, 'product' => 1, 'module' => 1, 'scene' => 1, 'story' => 1, 'lib' => 0, 'branch' => 0),
    (object)array('id' => 2, 'product' => 1, 'module' => 2, 'scene' => 2, 'story' => 2, 'lib' => 0, 'branch' => 0)
);
$modules1 = array(
    'case' => array(
        1 => array(
            0 => array(
                array('text' => '模块1', 'value' => 1),
                array('text' => '模块2', 'value' => 2)
            )
        )
    )
);

// 测试数据2：空用例数组
$productID2 = 1;
$branch2 = '0';
$branches2 = array('0' => '无分支');
$cases2 = array();
$modules2 = array();

// 测试数据3：用例库案例
$productID3 = 1;
$branch3 = '0';
$branches3 = array('0' => '无分支');
$cases3 = array(
    (object)array('id' => 3, 'product' => 1, 'module' => 3, 'scene' => 3, 'story' => '', 'lib' => 1, 'branch' => 0)
);
$modules3 = array(
    'lib' => array(
        1 => array(
            0 => array(
                array('text' => '库模块1', 'value' => 3)
            )
        )
    )
);

// 测试数据4：不同分支案例
$productID4 = 2;
$branch4 = 'all';
$branches4 = array('0' => '无分支', '1' => '分支1');
$cases4 = array(
    (object)array('id' => 4, 'product' => 2, 'module' => 4, 'scene' => 4, 'story' => 4, 'lib' => 0, 'branch' => 1),
    (object)array('id' => 5, 'product' => 2, 'module' => 5, 'scene' => 5, 'story' => 5, 'lib' => 0, 'branch' => 0)
);
$modules4 = array(
    'case' => array(
        2 => array(
            1 => array(
                array('text' => '分支模块', 'value' => 4)
            ),
            0 => array(
                array('text' => '主干模块', 'value' => 5)
            )
        )
    )
);

// 测试数据5：缺失模块数据
$productID5 = 3;
$branch5 = '0';
$branches5 = array('0' => '无分支');
$cases5 = array(
    (object)array('id' => 6, 'product' => 3, 'module' => 6, 'scene' => 6, 'story' => 6, 'lib' => 0, 'branch' => 0)
);
$modules5 = array(); // 空模块数据

// 测试数据6：多个用例
$productID6 = 1;
$branch6 = '0';
$branches6 = array('0' => '无分支');
$cases6 = array(
    (object)array('id' => 7, 'product' => 1, 'module' => 1, 'scene' => 1, 'story' => 7, 'lib' => 0, 'branch' => 0),
    (object)array('id' => 8, 'product' => 1, 'module' => 2, 'scene' => 2, 'story' => 8, 'lib' => 0, 'branch' => 0),
    (object)array('id' => 9, 'product' => 1, 'module' => 1, 'scene' => 1, 'story' => '', 'lib' => 0, 'branch' => 0)
);
$modules6 = array(
    'case' => array(
        1 => array(
            0 => array(
                array('text' => '模块1', 'value' => 1),
                array('text' => '模块2', 'value' => 2)
            )
        )
    )
);

// 6. 执行测试步骤（强制要求：必须包含至少5个测试步骤）
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID1, $branch1, $branches1, $cases1, $modules1)) && p('executed') && e('1'); // 步骤1：正常情况
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID2, $branch2, $branches2, $cases2, $modules2)) && p('executed') && e('1'); // 步骤2：空用例数组
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID3, $branch3, $branches3, $cases3, $modules3)) && p('executed') && e('1'); // 步骤3：用例库案例
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID4, $branch4, $branches4, $cases4, $modules4)) && p('executed') && e('1'); // 步骤4：不同分支案例
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID5, $branch5, $branches5, $cases5, $modules5)) && p('executed') && e('1'); // 步骤5：缺失模块数据
r($testcaseZenTest->assignModuleAndSceneForBatchEditTest($productID6, $branch6, $branches6, $cases6, $modules6)) && p('executed') && e('1'); // 步骤6：多个用例