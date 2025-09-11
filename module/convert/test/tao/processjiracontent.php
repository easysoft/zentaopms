#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraContentTest方法，参数是'内容包含分隔符 !avatar.jpg|width=100! 的图片', array  @内容包含分隔符 <img src="{3.jpg}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=jpg&fileID=3"/> 的图片
- 执行convertTest模块的processJiraContentTest方法，参数是'多个图片 !image.png|thumb! 和 !document.pdf|preview! 在同一内容中', array  @多个图片 <img src="{1.png}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=png&fileID=1"/> 和 <img src="{2.pdf}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=pdf&fileID=2"/> 在同一内容中
- 执行convertTest模块的processJiraContentTest方法，参数是'混合文件 !screenshot.png|preview! 和不存在的 !missing.jpg|thumb! 文件', array  @混合文件 <img src="{4.png}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=png&fileID=4"/> 和不存在的 !missing.jpg|thumb! 文件
- 执行convertTest模块的processJiraContentTest方法，参数是'', array  @0
- 执行convertTest模块的processJiraContentTest方法，参数是'没有图片标记的普通文本内容', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$table = zenData('file');
$table->id->range('1-5');
$table->title->range('image.png,document.pdf,avatar.jpg,screenshot.png,data.txt');
$table->extension->range('png,pdf,jpg,png,txt');
$table->size->range('1024-102400');
$table->objectType->range('story,bug,task,ticket,story');
$table->objectID->range('1-5');
$table->gen(5);

su('admin');

$convertTest = new convertTest();

r($convertTest->processJiraContentTest('内容包含分隔符 !avatar.jpg|width=100! 的图片', array('avatar.jpg' => (object)array('id' => 3, 'extension' => 'jpg')))) && p() && e('内容包含分隔符 <img src="{3.jpg}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=jpg&fileID=3"/> 的图片');

r($convertTest->processJiraContentTest('多个图片 !image.png|thumb! 和 !document.pdf|preview! 在同一内容中', array('image.png' => (object)array('id' => 1, 'extension' => 'png'), 'document.pdf' => (object)array('id' => 2, 'extension' => 'pdf')))) && p() && e('多个图片 <img src="{1.png}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=png&fileID=1"/> 和 <img src="{2.pdf}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=pdf&fileID=2"/> 在同一内容中');

r($convertTest->processJiraContentTest('混合文件 !screenshot.png|preview! 和不存在的 !missing.jpg|thumb! 文件', array('screenshot.png' => (object)array('id' => 4, 'extension' => 'png')))) && p() && e('混合文件 <img src="{4.png}" alt="/home/z/rzto/module/convert/test/tao/processjiracontent.php?m=file&f=read&t=png&fileID=4"/> 和不存在的 !missing.jpg|thumb! 文件');

r($convertTest->processJiraContentTest('', array())) && p() && e('0');

r($convertTest->processJiraContentTest('没有图片标记的普通文本内容', array())) && p() && e('0');