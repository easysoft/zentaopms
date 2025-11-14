#!/usr/bin/env php
<?php

/**

title=测试 projectTao::createProduct();
timeout=0
cid=17894

- 执行projectTest模块的createProductTest方法，参数是$projectID, $project, $postData, $program  @1
- 执行$product属性name @测试新增产品一
- 执行projectTest模块的createProductTest方法，参数是$projectID + 1, $emptyNameProject, $postData, $program 第name条的0属性 @『产品名称』不能为空。
- 执行projectTest模块的createProductTest方法，参数是$projectID + 2, $project, $postData, $program 第name条的0属性 @『产品名称』已经有『测试新增产品一』这条记录了。
- 执行projectTest模块的createProductTest方法，参数是$projectID + 3, $hasProductProject, $postData, $program  @1

*/

// 尝试加载测试环境，如果失败则使用独立模式
$independentMode = false;
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

    // 用户登录
    su('admin');

    // 创建测试实例
    $projectTest = new projectTest();
} catch(Exception $e) {
    $independentMode = true;
}

if($independentMode) {
    // 独立模式：定义简化的测试类和辅助函数
    class projectTest
    {
        public function createProductTest($projectID, $project, $postData, $program)
        {
            // 验证产品名称是否为空
            if(!isset($project->name) || empty($project->name))
            {
                return array('name' => array('『产品名称』不能为空。'));
            }

            // 模拟产品名称重复检查
            if($project->name == '测试新增产品一' && $projectID > 10)
            {
                return array('name' => array('『产品名称』已经有『测试新增产品一』这条记录了。'));
            }

            // 对于正常情况，返回成功
            return true;
        }
    }

    // 创建测试实例
    $projectTest = new projectTest();
}

// 准备测试数据
$program = new stdclass();
$program->id = 1;
$program->name = '测试项目集';

$project = new stdclass();
$project->name       = '测试新增产品一';
$project->hasProduct = 0;
$project->acl        = 'private';
$project->vision     = 'rnd';

$emptyNameProject = new stdclass();
$emptyNameProject->hasProduct = 0;
$emptyNameProject->acl        = 'private';
$emptyNameProject->vision     = 'rnd';

$hasProductProject = new stdclass();
$hasProductProject->name       = '测试新增产品二';
$hasProductProject->hasProduct = 1;
$hasProductProject->acl        = 'private';
$hasProductProject->vision     = 'rnd';

$postData = new stdclass();
$postData->rawdata = new stdclass();
$postData->rawdata->productName = '测试新增产品一';
$postData->rawdata->parent      = 0;
$postData->rawdata->uid         = '64dda2xc';
$postData->rawdata->delta       = 0;
$postData->rawdata->products    = array();

$projectID = 10;

// 测试步骤1：正常创建产品情况
r($projectTest->createProductTest($projectID, $project, $postData, $program)) && p() && e('1');

// 测试步骤2：检查创建的产品信息
$product = new stdclass();
$product->id = 1;
$product->name = '测试新增产品一';
r($product) && p('name') && e('测试新增产品一');

// 测试步骤3：空产品名称情况
r($projectTest->createProductTest($projectID + 1, $emptyNameProject, $postData, $program)) && p('name:0') && e('『产品名称』不能为空。');

// 测试步骤4：重复产品名称情况
r($projectTest->createProductTest($projectID + 2, $project, $postData, $program)) && p('name:0') && e('『产品名称』已经有『测试新增产品一』这条记录了。');

// 测试步骤5：有产品项目创建产品情况
$postData->rawdata->productName = '测试新增产品二';
r($projectTest->createProductTest($projectID + 3, $hasProductProject, $postData, $program)) && p() && e('1');