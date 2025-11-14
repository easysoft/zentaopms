#!/usr/bin/env php
<?php

/**

title=测试 editorModel::addLink4Dir();
timeout=0
cid=16227

- 步骤1：测试control.php目录链接属性hasNewPageLink @1
- 步骤2：测试model.php目录链接属性hasNewMethodLink @1
- 步骤3：测试lang目录链接属性hasEmptyActions @1
- 步骤4：测试JS父目录链接属性hasNewJSLink @1
- 步骤5：测试CSS父目录链接属性hasNewCSSLink @1
- 步骤6：测试扩展目录链接属性hasNewExtendLink @1
- 步骤7：测试空路径属性hasBasicStructure @1
- 步骤8：测试特殊字符处理属性hasValidId @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$editor = new editorTest();

r($editor->addLink4DirControlTest()) && p('hasNewPageLink') && e('1'); // 步骤1：测试control.php目录链接
r($editor->addLink4DirModelTest()) && p('hasNewMethodLink') && e('1'); // 步骤2：测试model.php目录链接
r($editor->addLink4DirLangTest()) && p('hasEmptyActions') && e('1'); // 步骤3：测试lang目录链接
r($editor->addLink4DirJSTest()) && p('hasNewJSLink') && e('1'); // 步骤4：测试JS父目录链接
r($editor->addLink4DirCSSTest()) && p('hasNewCSSLink') && e('1'); // 步骤5：测试CSS父目录链接
r($editor->addLink4DirExtTest()) && p('hasNewExtendLink') && e('1'); // 步骤6：测试扩展目录链接
r($editor->addLink4DirEmptyTest()) && p('hasBasicStructure') && e('1'); // 步骤7：测试空路径
r($editor->addLink4DirSpecialCharsTest()) && p('hasValidId') && e('1'); // 步骤8：测试特殊字符处理