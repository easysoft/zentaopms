#!/usr/bin/env php
<?php

/**

title=测试 docModel::initDocDefaultSpaces();
timeout=0
cid=16138

- 步骤1：正常情况创建父级空间
 - 属性type @doctemplate
 - 属性vision @rnd
 - 属性parent @0
 - 属性acl @open
 - 属性addedBy @system
- 步骤2：创建子空间
 - 属性type @doctemplate
 - 属性vision @rnd
 - 属性parent @1
 - 属性acl @open
 - 属性addedBy @system
- 步骤3：验证空间名称正确设置属性name @设计模板
- 步骤4：验证父子关系和名称
 - 属性parent @2
 - 属性name @测试模板
- 步骤5：测试不同父级ID
 - 属性type @doctemplate
 - 属性parent @99
 - 属性name @API模板

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 设置语言配置
global $lang;
$lang->doctemplate = new stdclass();
$lang->doctemplate->plan = '计划模板';
$lang->doctemplate->requirement = '需求模板';
$lang->doctemplate->design = '设计模板';
$lang->doctemplate->test = '测试模板';
$lang->doctemplate->api = 'API模板';

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->initDocDefaultSpacesTest('plan', 0)) && p('type,vision,parent,acl,addedBy') && e('doctemplate,rnd,0,open,system'); // 步骤1：正常情况创建父级空间
r($docTest->initDocDefaultSpacesTest('requirement', 1)) && p('type,vision,parent,acl,addedBy') && e('doctemplate,rnd,1,open,system'); // 步骤2：创建子空间
r($docTest->initDocDefaultSpacesTest('design', 0)) && p('name') && e('设计模板'); // 步骤3：验证空间名称正确设置
r($docTest->initDocDefaultSpacesTest('test', 2)) && p('parent,name') && e('2,测试模板'); // 步骤4：验证父子关系和名称
r($docTest->initDocDefaultSpacesTest('api', 99)) && p('type,parent,name') && e('doctemplate,99,API模板'); // 步骤5：测试不同父级ID