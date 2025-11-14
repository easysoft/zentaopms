#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getColLabel();
timeout=0
cid=17375

- 步骤1：字段存在客户端语言标签 @标题字段
- 步骤2：在langs中存在标签 @状态标签
- 步骤3：字段有object且存在语言包 @指派给%s
- 步骤4：字段有name属性 @名称字段
- 步骤5：返回key本身 @unknownField

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getColLabelTest('title', array('title' => array('zh-cn' => '标题字段')), array())) && p() && e('标题字段'); // 步骤1：字段存在客户端语言标签
r($pivotTest->getColLabelTest('status', array('status' => array('object' => 'task')), array('status' => array('zh-cn' => '状态标签')))) && p() && e('状态标签'); // 步骤2：在langs中存在标签
r($pivotTest->getColLabelTest('assignedTo', array('assignedTo' => array('object' => 'user', 'name' => '')), array())) && p() && e('指派给%s'); // 步骤3：字段有object且存在语言包
r($pivotTest->getColLabelTest('name', array('name' => array('name' => '名称字段')), array())) && p() && e('名称字段'); // 步骤4：字段有name属性
r($pivotTest->getColLabelTest('unknownField', array('unknownField' => array()), array())) && p() && e('unknownField'); // 步骤5：返回key本身