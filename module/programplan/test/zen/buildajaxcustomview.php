#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildAjaxCustomView();
timeout=0
cid=17785

- 步骤1：正常情况属性customFields @2
- 步骤2：空用户参数属性stageCustom @~~
- 步骤3：空模块参数属性stageCustom @~~
- 步骤4：空自定义字段参数属性customFields @0
- 步骤5：不存在的用户配置属性customFields @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$programplanTest = new programplanTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($programplanTest->buildAjaxCustomViewTest('admin', 'programplan', array('name' => '阶段名称', 'percent' => '工作量'))) && p('customFields') && e('2'); // 步骤1：正常情况
r($programplanTest->buildAjaxCustomViewTest('', 'programplan', array('name' => '阶段名称'))) && p('stageCustom') && e('~~'); // 步骤2：空用户参数
r($programplanTest->buildAjaxCustomViewTest('admin', '', array('name' => '阶段名称'))) && p('stageCustom') && e('~~'); // 步骤3：空模块参数
r($programplanTest->buildAjaxCustomViewTest('admin', 'programplan', array())) && p('customFields') && e('0'); // 步骤4：空自定义字段参数
r($programplanTest->buildAjaxCustomViewTest('nonexistuser', 'programplan', array('begin' => '开始日期', 'end' => '结束日期'))) && p('customFields') && e('2'); // 步骤5：不存在的用户配置