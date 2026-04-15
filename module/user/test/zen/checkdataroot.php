#!/usr/bin/env php
<?php
$_SERVER['SCRIPT_FILENAME'] = dirname(__FILE__, 5) . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'index.php';

/**

title=测试 userZen::checkDataRoot();
timeout=0
cid=19691

- 执行userTest模块的checkDataRootTest方法  @1
- 执行userTest模块的checkDataRootTest方法，参数是$writableDir . DS  @1
- 执行userTest模块的checkDataRootTest方法，参数是$missingDir  @0
- 执行userTest模块的checkDataRootTest方法  @1
- 执行userTest模块的checkDataRootTest方法，参数是$fileAsPath  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userTest = new userZenTest();

$tmpBase = sys_get_temp_dir() . DS . 'zentao_userzen_checkdataroot_' . uniqid('', true);
$writableDir = $tmpBase . DS . 'writable';
$missingDir = $tmpBase . DS . 'not_existed' . DS;
$fileAsPath = $tmpBase . DS . 'file_not_dir';

mkdir($tmpBase, 0777, true);
mkdir($writableDir, 0777, true);
file_put_contents($fileAsPath, 'zentao');

r($userTest->checkDataRootTest()) && p() && e(1);
r($userTest->checkDataRootTest($writableDir . DS)) && p() && e(1);
r($userTest->checkDataRootTest($missingDir)) && p() && e(0);
r($userTest->checkDataRootTest()) && p() && e(1);
r($userTest->checkDataRootTest($fileAsPath)) && p() && e(0);