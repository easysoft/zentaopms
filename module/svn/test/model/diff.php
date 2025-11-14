#!/usr/bin/env php
<?php

/**

title=测试 svnModel::diff();
timeout=0
cid=18714

- 执行svnTest模块的diffTest方法，参数是'https://svn.qc.oop.cc/svn/unittest', 1  @sh: 1: svn: not found
- 执行svnTest模块的diffTest方法，参数是'https://svn.qc.oop.cc/svn/unittest', 0  @~~
- 执行svnTest模块的diffTest方法，参数是'https://svn.qc.oop.cc/svn/unittest', -1  @sh: 1: svn: not found
- 执行svnTest模块的diffTest方法，参数是'http://nonexistent.url', 1  @~~
- 执行svnTest模块的diffTest方法，参数是'', 1  @sh: 1: svn: not found
- 执行svnTest模块的diffTest方法，参数是'https://svn.qc.oop.cc/svn/unittest/file%20with%20spaces', 1  @~~
- 执行svnTest模块的diffTest方法，参数是'https://svn.qc.oop.cc/svn/unittest', 999999  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(1);

su('admin');

$svnTest = new svnTest();

r($svnTest->diffTest('https://svn.qc.oop.cc/svn/unittest', 1)) && p() && e('sh: 1: svn: not found');
r($svnTest->diffTest('https://svn.qc.oop.cc/svn/unittest', 0)) && p() && e('~~');
r($svnTest->diffTest('https://svn.qc.oop.cc/svn/unittest', -1)) && p() && e('sh: 1: svn: not found');
r($svnTest->diffTest('http://nonexistent.url', 1)) && p() && e('~~');
r($svnTest->diffTest('', 1)) && p() && e('sh: 1: svn: not found');
r($svnTest->diffTest('https://svn.qc.oop.cc/svn/unittest/file%20with%20spaces', 1)) && p() && e('~~');
r($svnTest->diffTest('https://svn.qc.oop.cc/svn/unittest', 999999)) && p() && e('0');