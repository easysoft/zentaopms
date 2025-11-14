#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraContent();
timeout=0
cid=15869

- 执行convertTest模块的processJiraContentTest方法，参数是'', array  @0
- 执行convertTest模块的processJiraContentTest方法，参数是'This is plain text without image markers.', array  @0
- 执行convertTest模块的processJiraContentTest方法，参数是'This has !image.png|width=100! marker but no files', array  @This has !image.png|width=100! marker but no files
- 执行convertTest模块的processJiraContentTest方法，参数是'Check this !test.png|width=100! image', array  @Check this <img src="{1.png}" alt="file-read-png-1.html"/> image
- 执行convertTest模块的processJiraContentTest方法，参数是'!image1.jpg|width=100! and !image2.png|height=200!', array  @<img src="{1.jpg}" alt="file-read-jpg-1.html"/> and <img src="{2.png}" alt="file-read-png-2.html"/>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->processJiraContentTest('', array())) && p() && e('0');
r($convertTest->processJiraContentTest('This is plain text without image markers.', array())) && p() && e('0');
r($convertTest->processJiraContentTest('This has !image.png|width=100! marker but no files', array())) && p() && e('This has !image.png|width=100! marker but no files');
r($convertTest->processJiraContentTest('Check this !test.png|width=100! image', array('test.png' => (object)array('id' => 1, 'extension' => 'png')))) && p() && e('Check this <img src="{1.png}" alt="file-read-png-1.html"/> image');
r($convertTest->processJiraContentTest('!image1.jpg|width=100! and !image2.png|height=200!', array('image1.jpg' => (object)array('id' => 1, 'extension' => 'jpg'), 'image2.png' => (object)array('id' => 2, 'extension' => 'png')))) && p() && e('<img src="{1.jpg}" alt="file-read-jpg-1.html"/> and <img src="{2.png}" alt="file-read-png-2.html"/>');