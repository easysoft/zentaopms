#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraContentTest方法，参数是'', array  @0
- 执行convertTest模块的processJiraContentTest方法，参数是'This is a test !screenshot.png|thumbnail! image', $fileList1  @This is a test <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> image
- 执行convertTest模块的processJiraContentTest方法，参数是'Two images: !screenshot.png|thumb! and !image.jpg|width=100!', $fileList2  @Two images: <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> and <img src="{2.jpg}" alt="processjiracontent.php?m=file&f=read&t=jpg&fileID=2"/>
- 执行convertTest模块的processJiraContentTest方法，参数是'Missing file: !notfound.png|thumb!', $fileList3  @Missing file: !notfound.png|thumb!
- 执行convertTest模块的processJiraContentTest方法，参数是'No image markers here', $fileList1  @0
- 执行convertTest模块的processJiraContentTest方法，参数是'Image with options !screenshot.png|width=100, height=80! here', $fileList1  @Image with options <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> here

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

// 准备测试数据
$file1 = new stdClass();
$file1->id = 1;
$file1->extension = 'png';

$file2 = new stdClass();
$file2->id = 2;
$file2->extension = 'jpg';

$file3 = new stdClass();
$file3->id = 3;
$file3->extension = 'gif';

$fileList1 = array('screenshot.png' => $file1);
$fileList2 = array('screenshot.png' => $file1, 'image.jpg' => $file2);
$fileList3 = array();

r($convertTest->processJiraContentTest('', array())) && p() && e('0');
r($convertTest->processJiraContentTest('This is a test !screenshot.png|thumbnail! image', $fileList1)) && p() && e('This is a test <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> image');
r($convertTest->processJiraContentTest('Two images: !screenshot.png|thumb! and !image.jpg|width=100!', $fileList2)) && p() && e('Two images: <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> and <img src="{2.jpg}" alt="processjiracontent.php?m=file&f=read&t=jpg&fileID=2"/>');
r($convertTest->processJiraContentTest('Missing file: !notfound.png|thumb!', $fileList3)) && p() && e('Missing file: !notfound.png|thumb!');
r($convertTest->processJiraContentTest('No image markers here', $fileList1)) && p() && e('0');
r($convertTest->processJiraContentTest('Image with options !screenshot.png|width=100,height=80! here', $fileList1)) && p() && e('Image with options <img src="{1.png}" alt="processjiracontent.php?m=file&f=read&t=png&fileID=1"/> here');