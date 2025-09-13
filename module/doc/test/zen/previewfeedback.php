#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewFeedback();
timeout=0
cid=0

- 步骤1：正常预览设置页面第data条的0:title属性 @反馈标题1
- 步骤2：预览列表页面第data条的0:title属性 @反馈标题1
- 步骤3：自定义搜索第data条的0:title属性 @反馈标题1
- 步骤4：空ID列表属性data @0
- 步骤5：无效参数属性data @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 简化测试，不需要zendata生成数据库数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewFeedbackTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'wait'), '')) && p('data:0:title') && e('反馈标题1'); // 步骤1：正常预览设置页面
r($docTest->previewFeedbackTest('list', array(), '1,2,3')) && p('data:0:title') && e('反馈标题1'); // 步骤2：预览列表页面
r($docTest->previewFeedbackTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('title'), 'operator' => array('include'), 'value' => array('反馈'), 'andor' => array('and')), '')) && p('data:0:title') && e('反馈标题1'); // 步骤3：自定义搜索
r($docTest->previewFeedbackTest('list', array(), '')) && p('data') && e('~~'); // 步骤4：空ID列表
r($docTest->previewFeedbackTest('invalid', array('action' => 'invalid'), '')) && p('data') && e('~~'); // 步骤5：无效参数