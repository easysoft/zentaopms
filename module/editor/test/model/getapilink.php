#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getAPILink();
timeout=0
cid=16232

- 执行editor模块的getAPILinkTest方法，参数是'/module/user/model.php/getById', 'extendModel'
 - 属性hasDebug @1
 - 属性hasApiModule @1
 - 属性actionMatch @1
 - 属性filePathEncoded @1
- 执行editor模块的getAPILinkTest方法，参数是'/module/task/control.php/create', 'extendControl'
 - 属性hasDebug @1
 - 属性hasAction @1
 - 属性hasApiModule @1
 - 属性isValidLink @1
- 执行editor模块的getAPILinkTest方法，参数是'/module/project/model.php/update', ''
 - 属性hasDebug @1
 - 属性hasFilePath @1
 - 属性isValidLink @1
- 执行editor模块的getAPILinkTest方法，参数是'/path/with_special_chars/model.php/method', 'extendModel'
 - 属性hasDebug @1
 - 属性hasAction @1
 - 属性filePathEncoded @1
 - 属性isValidLink @1
- 执行editor模块的getAPILinkTest方法，参数是'/very/long/deep/nested/module/path/structure/with/many/levels/test/model.php/veryLongMethodNameForTesting', 'extendModel'
 - 属性hasDebug @1
 - 属性actionMatch @1
- 执行editor模块的getAPILinkTest方法，参数是'/module/bug/model.php/close', 'extendModel'
 - 属性actionMatch @1
 - 属性hasApiModule @1
 - 属性canDecodeFilePath @1
- 执行editor模块的getAPILinkTest方法，参数是'/module/story/control.php/edit', 'extendControl'
 - 属性filePathEncoded @1
 - 属性isValidLink @1
 - 属性hasAction @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';

su('admin');

$editor = new editorTest();

r($editor->getAPILinkTest('/module/user/model.php/getById', 'extendModel')) && p('hasDebug,hasApiModule,actionMatch,filePathEncoded') && e('1,1,1,1');
r($editor->getAPILinkTest('/module/task/control.php/create', 'extendControl')) && p('hasDebug,hasAction,hasApiModule,isValidLink') && e('1,1,1,1');
r($editor->getAPILinkTest('/module/project/model.php/update', '')) && p('hasDebug,hasFilePath,isValidLink') && e('1,1,1');
r($editor->getAPILinkTest('/path/with_special_chars/model.php/method', 'extendModel')) && p('hasDebug,hasAction,filePathEncoded,isValidLink') && e('1,1,1,1');
r($editor->getAPILinkTest('/very/long/deep/nested/module/path/structure/with/many/levels/test/model.php/veryLongMethodNameForTesting', 'extendModel')) && p('hasDebug,actionMatch') && e('1,1');
r($editor->getAPILinkTest('/module/bug/model.php/close', 'extendModel')) && p('actionMatch,hasApiModule,canDecodeFilePath') && e('1,1,1');
r($editor->getAPILinkTest('/module/story/control.php/edit', 'extendControl')) && p('filePathEncoded,isValidLink,hasAction') && e('1,1,1');