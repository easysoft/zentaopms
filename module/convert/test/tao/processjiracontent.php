#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraContent();
cid=0

- 测试步骤1：正常Jira图片标记转换 >> 期望正确转换为HTML img标签
- 测试步骤2：多个图片标记转换 >> 期望全部正确转换
- 测试步骤3：部分匹配文件转换 >> 期望已匹配的转换，未匹配的保持原样
- 测试步骤4：空内容输入 >> 期望返回空字符串
- 测试步骤5：无图片标记的文本内容 >> 期望返回空字符串

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->processJiraContentTest('内容包含分隔符 !avatar.jpg|width=100! 的图片', array('avatar.jpg' => (object)array('id' => 3, 'extension' => 'jpg')))) && p() && e('内容包含分隔符 <img src="{3.jpg}" alt="index.php?m=file&f=read&t=jpg&fileID=3"/> 的图片');
r($convertTest->processJiraContentTest('多个图片 !image.png|thumb! 和 !document.pdf|preview! 在同一内容中', array('image.png' => (object)array('id' => 1, 'extension' => 'png'), 'document.pdf' => (object)array('id' => 2, 'extension' => 'pdf')))) && p() && e('多个图片 <img src="{1.png}" alt="index.php?m=file&f=read&t=png&fileID=1"/> 和 <img src="{2.pdf}" alt="index.php?m=file&f=read&t=pdf&fileID=2"/> 在同一内容中');
r($convertTest->processJiraContentTest('混合文件 !screenshot.png|preview! 和不存在的 !missing.jpg|thumb! 文件', array('screenshot.png' => (object)array('id' => 4, 'extension' => 'png')))) && p() && e('混合文件 <img src="{4.png}" alt="index.php?m=file&f=read&t=png&fileID=4"/> 和不存在的 !missing.jpg|thumb! 文件');
r($convertTest->processJiraContentTest('', array())) && p() && e('');
r($convertTest->processJiraContentTest('没有图片标记的普通文本内容', array())) && p() && e('');