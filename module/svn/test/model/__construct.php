#!/usr/bin/env php
<?php

/**

title=测试 svnModel::__construct();
timeout=0
cid=18711

- 执行svnTest模块的__constructTest方法，参数是'', ''  @svnModel
- 执行svnTest模块的__constructTest方法，参数是'testModule', ''  @svnModel
- 执行svnTest模块的__constructTest方法，参数是'', 'testMethod'  @svnModel
- 执行svnTest模块的__constructTest方法，参数是'testModule', 'testMethod'  @svnModel
- 执行 @C

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

su('admin');

$svnTest = new svnTest();

r(get_class($svnTest->__constructTest('', ''))) && p() && e('svnModel');
r(get_class($svnTest->__constructTest('testModule', ''))) && p() && e('svnModel');
r(get_class($svnTest->__constructTest('', 'testMethod'))) && p() && e('svnModel');
r(get_class($svnTest->__constructTest('testModule', 'testMethod'))) && p() && e('svnModel');
r(getenv('LC_ALL')) && p() && e('C');