#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getExtendLink();
timeout=0
cid=16234

- 执行editor模块的getExtendLinkNormalTest方法  @1
- 执行editor模块的getExtendLinkWithExtendsTest方法  @1
- 执行editor模块的getExtendLinkSpecialCharsTest方法  @1
- 执行editor模块的getExtendLinkEmptyActionTest方法  @1
- 执行editor模块的getExtendLinkComplexPathTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$editor = new editorModelTest();

r($editor->getExtendLinkNormalTest()) && p() && e(1);
r($editor->getExtendLinkWithExtendsTest()) && p() && e(1);
r($editor->getExtendLinkSpecialCharsTest()) && p() && e(1);
r($editor->getExtendLinkEmptyActionTest()) && p() && e(1);
r($editor->getExtendLinkComplexPathTest()) && p() && e(1);