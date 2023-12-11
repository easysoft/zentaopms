#!/usr/bin/env php
<?php

/**

title=测试 fileModel->setPathName();
cid=0

- 检查可以正则匹配 pathName属性reg @1
- 检查可以正则匹配 pathName属性reg @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = new fileTest();

r($file->setPathNameTest(0, ''))    && p('reg') && e('1'); //检查可以正则匹配 pathName
r($file->setPathNameTest(1, 'txt')) && p('reg') && e('1'); //检查可以正则匹配 pathName
