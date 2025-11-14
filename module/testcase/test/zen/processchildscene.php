#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processChildScene();
timeout=0
cid=19101

- 执行testcaseTest模块的processChildSceneTest方法，参数是$simpleResult, 'root', 'scene'  @2
- 执行testcaseTest模块的processChildSceneTest方法，参数是$attachedResult, 'root', 'scene'  @1
- 执行testcaseTest模块的processChildSceneTest方法，参数是$topicsResult, 'root', 'scene'  @1
- 执行testcaseTest模块的processChildSceneTest方法，参数是$emptyResult, 'root', 'scene'  @0
- 执行testcaseTest模块的processChildSceneTest方法，参数是$invalidResult, 'root', 'scene'  @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 4. 测试步骤

// 步骤1：处理包含基本字段的简单场景结果
$simpleResult = array(
    array('id' => '1', 'title' => '测试场景1'),
    array('id' => '2', 'title' => '测试场景2')
);
r(count($testcaseTest->processChildSceneTest($simpleResult, 'root', 'scene'))) && p() && e('2');

// 步骤2：处理包含attached子元素的场景结果
$attachedResult = array(
    array(
        'id' => '3',
        'title' => '父场景',
        'children' => array(
            'attached' => array(
                array('id' => '4', 'title' => '子场景1')
            )
        )
    )
);
r(count($testcaseTest->processChildSceneTest($attachedResult, 'root', 'scene'))) && p() && e('1');

// 步骤3：处理包含topics子元素的场景结果
$topicsResult = array(
    array(
        'id' => '6',
        'title' => '主题场景',
        'children' => array(
            'topics' => array(
                'type' => 'topic',
                'topic' => array(
                    'id' => '7',
                    'title' => '主题内容'
                )
            )
        )
    )
);
r(count($testcaseTest->processChildSceneTest($topicsResult, 'root', 'scene'))) && p() && e('1');

// 步骤4：处理空结果数组输入
$emptyResult = array();
r(count($testcaseTest->processChildSceneTest($emptyResult, 'root', 'scene'))) && p() && e('0');

// 步骤5：处理缺少必要字段的结果数组
$invalidResult = array(
    array('title' => '无ID场景'),  // 缺少id字段
    array('id' => '8', 'title' => '正常场景')
);
r(count($testcaseTest->processChildSceneTest($invalidResult, 'root', 'scene'))) && p() && e('1');