#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

/**

title=测试 fileModel->setPathName();
cid=1
pid=1

*/

$file = new fileTest();

r($file->setPathNameTest(0, ''))    && p('reg') && e('1'); //检查可以正则匹配 pathName
r($file->setPathNameTest(1, 'txt')) && p('reg') && e('1'); //检查可以正则匹配 pathName
