#!/usr/bin/env php
<?php

/**

title=测试 mailTao::replaceImageURL();
timeout=0
cid=17037

- 步骤1：空图片数组保持原内容 @<img src="/test.jpg" />
- 步骤2：单个图片URL替换 @<img src="cid:file1.jpg" />
- 步骤3：多个图片URL替换 @<img src="cid:pic1.png" /><img src="cid:pic2.gif" />
- 步骤4：包含空文件路径 @<img src="cid:valid.jpg" /><img src="/empty.jpg" />
- 步骤5：复杂HTML内容 @<p>Text</p><img src="cid:logo.png" /><a href="/link.html">Link</a>
- 步骤6：不存在的URL保持不变 @<img src="/nonexist.jpg" />
- 步骤7：边界情况测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->replaceImageURLTest('<img src="/test.jpg" />', array())) && p() && e('<img src="/test.jpg" />');  // 步骤1：空图片数组保持原内容
r($mailTest->replaceImageURLTest('<img src="/image1.jpg" />', array('/image1.jpg' => 'file1.jpg'))) && p() && e('<img src="cid:file1.jpg" />');  // 步骤2：单个图片URL替换
r($mailTest->replaceImageURLTest('<img src="/img1.png" /><img src="/img2.gif" />', array('/img1.png' => 'pic1.png', '/img2.gif' => 'pic2.gif'))) && p() && e('<img src="cid:pic1.png" /><img src="cid:pic2.gif" />');  // 步骤3：多个图片URL替换
r($mailTest->replaceImageURLTest('<img src="/valid.jpg" /><img src="/empty.jpg" />', array('/valid.jpg' => 'valid.jpg', '/empty.jpg' => ''))) && p() && e('<img src="cid:valid.jpg" /><img src="/empty.jpg" />');  // 步骤4：包含空文件路径
r($mailTest->replaceImageURLTest('<p>Text</p><img src="/logo.png" /><a href="/link.html">Link</a>', array('/logo.png' => 'logo.png'))) && p() && e('<p>Text</p><img src="cid:logo.png" /><a href="/link.html">Link</a>');  // 步骤5：复杂HTML内容
r($mailTest->replaceImageURLTest('<img src="/nonexist.jpg" />', array('/other.jpg' => 'other.jpg'))) && p() && e('<img src="/nonexist.jpg" />');  // 步骤6：不存在的URL保持不变
r($mailTest->replaceImageURLTest('', array())) && p() && e('0');  // 步骤7：边界情况测试