#!/usr/bin/env php
<?php

/**

title=测试 screenZen::prepareCardList();
cid=0

- 测试步骤1：正常screens数组包含完整cover和published状态 >> 期望src使用cover值
- 测试步骤2：测试draft状态的screen >> 期望src使用默认图片
- 测试步骤3：测试空cover的screen >> 期望src使用默认图片
- 测试步骤4：测试builtin=1的screen >> 期望actions不包含设计和删除操作
- 测试步骤5：测试开源版（edition=open）的screen >> 期望actions为空数组

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('screen');
$table->id->range('1-10');
$table->name->range('测试大屏{1-5},年度报告{1-3},数据看板{1-2}');
$table->cover->range('/cover/image1.png,/cover/image2.png,/cover/image3.png,[]{5}');
$table->status->range('draft{4},published{6}');
$table->builtin->range('0{7},1{3}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$screenTest = new screenTest();

// 5. 准备测试数据
$screens1 = array(
    1 => (object)array('id' => 1, 'cover' => '/cover/test.png', 'status' => 'published', 'builtin' => '0'),
    2 => (object)array('id' => 2, 'cover' => '/cover/test2.png', 'status' => 'published', 'builtin' => '0')
);

$screens2 = array(
    3 => (object)array('id' => 3, 'cover' => '', 'status' => 'draft', 'builtin' => '0')
);

$screens3 = array(
    4 => (object)array('id' => 4, 'cover' => '', 'status' => 'published', 'builtin' => '0')
);

$screens4 = array(
    5 => (object)array('id' => 5, 'cover' => '/cover/builtin.png', 'status' => 'published', 'builtin' => '1')
);

// 模拟开源版配置
global $config;
$originalEdition = $config->edition;

// 测试步骤1：正常screens数组包含完整cover和published状态
r($screenTest->prepareCardListTest($screens1)) && p('1:src') && e('/cover/test.png');

// 测试步骤2：测试draft状态的screen
r($screenTest->prepareCardListTest($screens2)) && p('3:src') && e('static/images/screen_draft.png');

// 测试步骤3：测试空cover的screen
r($screenTest->prepareCardListTest($screens3)) && p('4:src') && e('static/images/screen_published.png');

// 测试步骤4：测试builtin=1的screen（不包含设计和删除操作）
$result4 = $screenTest->prepareCardListTest($screens4);
r(count($result4[5]->actions)) && p() && e(0);

// 测试步骤5：测试开源版（edition=open）的screen
$config->edition = 'open';
$result5 = $screenTest->prepareCardListTest($screens1);
r(count($result5[1]->actions)) && p() && e(0);

// 恢复原始配置
$config->edition = $originalEdition;