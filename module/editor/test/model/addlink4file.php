#!/usr/bin/env php
<?php

/**

title=测试 editorModel::addLink4File();
timeout=0
cid=16228

- 测试view目录下文件的链接生成
 - 属性hasOverrideLink @1
 - 属性hasNewHookLink @1
 - 属性textMatch @1
 - 属性idLength @32
- 测试control.php下方法的链接生成
 - 属性hasExtendControlLink @1
 - 属性hasApiLink @1
 - 属性actionsCount @2
- 测试model.php下方法的链接生成
 - 属性hasExtendModelLink @1
 - 属性hasApiLink @1
 - 属性actionsCount @2
- 测试ext目录下文件的链接生成
 - 属性hasEditLink @1
 - 属性hasDeleteLink @1
 - 属性confirmExists @1
- 测试lang目录下文件的链接生成
 - 属性hasExtendOtherLink @1
 - 属性hasNewLangLink @1
 - 属性actionsCount @2
- 测试config.php文件的链接生成
 - 属性hasExtendOtherLink @1
 - 属性hasNewConfigLink @1
 - 属性actionsCount @2
- 测试空文件路径的异常处理
 - 属性hasBasicStructure @1
 - 属性idExists @1
 - 属性actionsExists @1
- 测试特殊字符路径的处理
 - 属性hasValidId @1
 - 属性namePreserved @1
 - 属性textPreserved @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->addLink4FileViewTest()) && p('hasOverrideLink,hasNewHookLink,textMatch,idLength') && e('1,1,1,32'); // 测试view目录下文件的链接生成
r($editor->addLink4FileControlTest()) && p('hasExtendControlLink,hasApiLink,actionsCount') && e('1,1,2'); // 测试control.php下方法的链接生成
r($editor->addLink4FileModelTest()) && p('hasExtendModelLink,hasApiLink,actionsCount') && e('1,1,2'); // 测试model.php下方法的链接生成
r($editor->addLink4FileExtTest()) && p('hasEditLink,hasDeleteLink,confirmExists') && e('1,1,1'); // 测试ext目录下文件的链接生成
r($editor->addLink4FileLangTest()) && p('hasExtendOtherLink,hasNewLangLink,actionsCount') && e('1,1,2'); // 测试lang目录下文件的链接生成
r($editor->addLink4FileConfigTest()) && p('hasExtendOtherLink,hasNewConfigLink,actionsCount') && e('1,1,2'); // 测试config.php文件的链接生成
r($editor->addLink4FileEmptyTest()) && p('hasBasicStructure,idExists,actionsExists') && e('1,1,1'); // 测试空文件路径的异常处理
r($editor->addLink4FileSpecialCharsTest()) && p('hasValidId,namePreserved,textPreserved') && e('1,1,1'); // 测试特殊字符路径的处理