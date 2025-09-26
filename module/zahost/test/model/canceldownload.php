#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::cancelDownload();
timeout=0
cid=0

- 执行zahostTest模块的cancelDownloadTest方法，参数是1  @1
- 执行zahostTest模块的cancelDownloadTest方法，参数是999  @0
- 执行zahostTest模块的cancelDownloadTest方法  @0
- 执行zahostTest模块的cancelDownloadTest方法，参数是-1  @0
- 执行zahostTest模块的cancelDownloadTest方法，参数是3  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahost.unittest.class.php';
su('admin');

$zahostTest = new zahostTest();

r($zahostTest->cancelDownloadTest(1)) && p() && e('1');
r($zahostTest->cancelDownloadTest(999)) && p() && e('0');
r($zahostTest->cancelDownloadTest(0)) && p() && e('0');
r($zahostTest->cancelDownloadTest(-1)) && p() && e('0');
r($zahostTest->cancelDownloadTest(3)) && p() && e('0');