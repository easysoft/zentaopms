#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::updateVolSize();
timeout=0
cid=16825

- 执行instanceTest模块的updateVolSizeTest方法，参数是$instance, 10737418240, 'data-storage'  @调整磁盘空间失败
- 执行instanceTest模块的updateVolSizeTest方法，参数是$instance, '5', 'data-storage'  @调整磁盘空间失败
- 执行instanceTest模块的updateVolSizeTest方法，参数是$instance, 0, 'data-storage'  @调整磁盘空间失败
- 执行instanceTest模块的updateVolSizeTest方法，参数是$instance, -1073741824, 'data-storage'  @调整磁盘空间失败
- 执行instanceTest模块的updateVolSizeTest方法，参数是$instance, 5368709120, ''  @调整磁盘空间失败

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备
$table = zenData('instance');
$table->id->range('1-10');
$table->name->range('test-app{5}, demo-app{5}');
$table->appID->range('1-2');
$table->appName->range('TestApp{5}, DemoApp{5}');
$table->space->range('1-2');
$table->k8name->range('test-k8-{5}, demo-k8-{5}');
$table->status->range('running{8}, stopped{2}');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceTest();

// 5. 创建模拟实例对象
$instance = new stdclass();
$instance->id = 1;
$instance->name = 'test-app';
$instance->k8name = 'test-k8-1';
$instance->space = 1;
$instance->chart = 'test-chart';
$instance->channel = 'stable';
$instance->version = '1.0.0';
$instance->oldValue = '5Gi';

// 创建模拟的spaceData对象
$instance->spaceData = new stdclass();
$instance->spaceData->k8space = 'test-namespace';

// Mock CNE模块的updateConfig方法
global $tester;
$instanceModel = $tester->loadModel('instance');

// 创建一个CNE mock类
class cneMock {
    public function updateConfig($instance, $settings = null) {
        // 模拟CNE API失败，这样更符合实际测试环境
        return false;
    }
}

// 创建一个action mock类
class actionMock {
    public function create($type, $id, $action, $value, $extra = '') {
        return 1; // 返回一个模拟的action ID
    }
    
    public function logHistory($actionId, $changes) {
        return true; // 返回成功
    }
}

// 替换模型中的依赖
$instanceModel->cne = new cneMock();
$instanceModel->action = new actionMock();

// 测试步骤执行

// 步骤1：正常整数大小输入，但CNE失败（10GB）
$instance->oldValue = '5Gi';
r($instanceTest->updateVolSizeTest($instance, 10737418240, 'data-storage')) && p('0') && e('调整磁盘空间失败');

// 步骤2：正常字符串大小输入，但CNE失败（5GB）
$instance->oldValue = '3Gi';
r($instanceTest->updateVolSizeTest($instance, '5', 'data-storage')) && p('0') && e('调整磁盘空间失败');

// 步骤3：零大小输入（直接返回false）
$instance->oldValue = '1Gi';
r($instanceTest->updateVolSizeTest($instance, 0, 'data-storage')) && p('0') && e('调整磁盘空间失败');

// 步骤4：负数大小输入（会被转换为0，直接返回false）
$instance->oldValue = '2Gi';
r($instanceTest->updateVolSizeTest($instance, -1073741824, 'data-storage')) && p('0') && e('调整磁盘空间失败');

// 步骤5：空字符串名称输入，但CNE失败（5GB）
$instance->oldValue = '4Gi';
r($instanceTest->updateVolSizeTest($instance, 5368709120, '')) && p('0') && e('调整磁盘空间失败');