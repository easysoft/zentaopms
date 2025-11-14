#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processScene();
timeout=0
cid=19103

- 步骤1：正常情况
 - 属性id @scene001
 - 属性text @测试场景1
 - 属性type @root
- 步骤2：包含attached子节点
 - 属性id @scene002
 - 属性text @测试场景2
 - 属性type @root
- 步骤3：包含topics子节点
 - 属性id @scene003
 - 属性text @测试场景3
 - 属性type @root
- 步骤4：无children数据
 - 属性id @scene004
 - 属性text @测试场景4
 - 属性type @root
- 步骤5：复杂嵌套结构
 - 属性id @scene005
 - 属性text @复杂场景
 - 属性type @root

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processSceneTest(array('id' => 'scene001', 'title' => '测试场景1', 'children' => array()))) && p('id,text,type') && e('scene001,测试场景1,root'); // 步骤1：正常情况
r($testcaseTest->processSceneTest(array('id' => 'scene002', 'title' => '测试场景2', 'children' => array('attached' => array(array('id' => 'sub001', 'title' => '子场景1')))))) && p('id,text,type') && e('scene002,测试场景2,root'); // 步骤2：包含attached子节点
r($testcaseTest->processSceneTest(array('id' => 'scene003', 'title' => '测试场景3', 'children' => array('topics' => array('topic' => array(array('id' => 'topic001', 'title' => '主题1'))))))) && p('id,text,type') && e('scene003,测试场景3,root'); // 步骤3：包含topics子节点
r($testcaseTest->processSceneTest(array('id' => 'scene004', 'title' => '测试场景4', 'children' => array()))) && p('id,text,type') && e('scene004,测试场景4,root'); // 步骤4：无children数据
r($testcaseTest->processSceneTest(array('id' => 'scene005', 'title' => '复杂场景', 'children' => array('attached' => array(array('id' => 'complex001', 'title' => '复杂子场景', 'children' => array('attached' => array(array('id' => 'nested001', 'title' => '嵌套场景'))))))))) && p('id,text,type') && e('scene005,复杂场景,root'); // 步骤5：复杂嵌套结构