#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkSafeFile();
timeout=0
cid=0

- 执行commonTest模块的checkSafeFileTest方法，参数是'inContainer'  @0
- 执行commonTest模块的checkSafeFileTest方法，参数是'validSafeFile'  @0
- 执行commonTest模块的checkSafeFileTest方法，参数是'upgradeModule'  @0
- 执行commonTest模块的checkSafeFileTest方法，参数是'noSafeFile'  @/home/z/repo/git/zentaopms/www/data/ok.txt
- 执行commonTest模块的checkSafeFileTest方法，参数是'expiredSafeFile'  @/home/z/repo/git/zentaopms/www/data/ok.txt

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->checkSafeFileTest('inContainer')) && p() && e('0');
r($commonTest->checkSafeFileTest('validSafeFile')) && p() && e('0');
r($commonTest->checkSafeFileTest('upgradeModule')) && p() && e('0');
r($commonTest->checkSafeFileTest('noSafeFile')) && p() && e('/home/z/repo/git/zentaopms/www/data/ok.txt');
r($commonTest->checkSafeFileTest('expiredSafeFile')) && p() && e('/home/z/repo/git/zentaopms/www/data/ok.txt');