#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkCreateFormData();
timeout=0
cid=19085

- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$validCase  @1
- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$noproductCase 属性product @『所属产品』不能为空。
- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$notitleCase 属性title @『用例名称』不能为空。
- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$notypeCase 属性type @『用例类型』不能为空。
- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$stepEmptyCase  @步骤0不能为空
- 执行testcaseTest模块的checkCreateFormDataTest方法，参数是$stageArrayCase  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况，包含所有必填字段
$validCase = new stdClass();
$validCase->product = 1;
$validCase->title = '测试用例标题';
$validCase->type = 'feature';
$validCase->steps = array('步骤1', '步骤2');
$validCase->expects = array('期望1', '期望2');
$validCase->stage = array('unittest');
r($testcaseTest->checkCreateFormDataTest($validCase)) && p() && e('1');

// 测试步骤2：缺少product必填字段
$noproductCase = new stdClass();
$noproductCase->title = '测试用例标题';
$noproductCase->type = 'feature';
$noproductCase->steps = array('步骤1');
$noproductCase->expects = array('期望1');
r($testcaseTest->checkCreateFormDataTest($noproductCase)) && p('product') && e('『所属产品』不能为空。');

// 测试步骤3：缺少title必填字段
$notitleCase = new stdClass();
$notitleCase->product = 1;
$notitleCase->type = 'feature';
$notitleCase->steps = array('步骤1');
$notitleCase->expects = array('期望1');
r($testcaseTest->checkCreateFormDataTest($notitleCase)) && p('title') && e('『用例名称』不能为空。');

// 测试步骤4：缺少type必填字段
$notypeCase = new stdClass();
$notypeCase->product = 1;
$notypeCase->title = '测试用例标题';
$notypeCase->steps = array('步骤1');
$notypeCase->expects = array('期望1');
r($testcaseTest->checkCreateFormDataTest($notypeCase)) && p('type') && e('『用例类型』不能为空。');

// 测试步骤5：期望结果不为空但对应步骤为空
$stepEmptyCase = new stdClass();
$stepEmptyCase->product = 1;
$stepEmptyCase->title = '测试用例标题';
$stepEmptyCase->type = 'feature';
$stepEmptyCase->steps = array('', '步骤2');
$stepEmptyCase->expects = array('期望1', '期望2');
r($testcaseTest->checkCreateFormDataTest($stepEmptyCase)) && p('0') && e('步骤0不能为空');

// 测试步骤6：数组类型字段stage验证（空数组情况）
$stageArrayCase = new stdClass();
$stageArrayCase->product = 1;
$stageArrayCase->title = '测试用例标题';
$stageArrayCase->type = 'feature';
$stageArrayCase->steps = array('步骤1');
$stageArrayCase->expects = array('期望1');
$stageArrayCase->stage = array();
r($testcaseTest->checkCreateFormDataTest($stageArrayCase)) && p() && e('1');