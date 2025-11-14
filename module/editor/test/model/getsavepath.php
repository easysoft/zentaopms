#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getSavePath();
timeout=0
cid=16239

- 步骤1：测试extendModel操作生成正确路径属性pathMatch @1
- 步骤2：测试extendControl操作生成正确路径属性pathMatch @1
- 步骤3：测试override操作生成正确路径属性pathMatch @1
- 步骤4：测试newJS操作生成正确路径属性pathMatch @1
- 步骤5：测试newCSS操作生成正确路径属性pathMatch @1
- 步骤6：测试extendOther配置文件操作属性pathMatch @1
- 步骤7：测试extendOther语言文件操作属性pathMatch @1
- 步骤8：测试空文件名错误处理属性hasError @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$editorTest = new editorTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($editorTest->getSavePathTest()) && p('pathMatch') && e('1');                                      // 步骤1：测试extendModel操作生成正确路径
r($editorTest->getSavePathExtendControlTest()) && p('pathMatch') && e('1');                         // 步骤2：测试extendControl操作生成正确路径
r($editorTest->getSavePathOverrideTest()) && p('pathMatch') && e('1');                              // 步骤3：测试override操作生成正确路径
r($editorTest->getSavePathNewJSTest()) && p('pathMatch') && e('1');                                 // 步骤4：测试newJS操作生成正确路径
r($editorTest->getSavePathNewCSSTest()) && p('pathMatch') && e('1');                                // 步骤5：测试newCSS操作生成正确路径
r($editorTest->getSavePathExtendOtherConfigTest()) && p('pathMatch') && e('1');                     // 步骤6：测试extendOther配置文件操作
r($editorTest->getSavePathExtendOtherLangTest()) && p('pathMatch') && e('1');                       // 步骤7：测试extendOther语言文件操作
r($editorTest->getSavePathEmptyFileNameTest()) && p('hasError') && e('1');                          // 步骤8：测试空文件名错误处理