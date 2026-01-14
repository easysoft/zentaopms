#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::updateDownloadingTagFile();
timeout=0
cid=15218

- 执行$biTest->objectModel, 'updateDownloadingTagFile' @1
- 执行biTest模块的updateDownloadingTagFileTest方法  @1
- 执行biTest模块的updateDownloadingTagFileTest方法，参数是'file', 'create'), array  @1
- 执行biTest模块的updateDownloadingTagFileTest方法，参数是'file', 'check'), array  @1
- 执行biTest模块的updateDownloadingTagFileTest方法，参数是'file', 'remove'), array  @1
- 执行biTest模块的updateDownloadingTagFileTest方法，参数是'extension_dm', 'create'  @1
- 执行biTest模块的updateDownloadingTagFileTest方法，参数是'extension_mysql', 'create'  @1

*/

$biTest = new biModelTest();

r(method_exists($biTest->objectModel, 'updateDownloadingTagFile')) && p() && e('1');
r(is_string($biTest->updateDownloadingTagFileTest())) && p() && e('1');
r(in_array($biTest->updateDownloadingTagFileTest('file', 'create'), array('ok', 'fail'))) && p() && e('1');
r(in_array($biTest->updateDownloadingTagFileTest('file', 'check'), array('ok', 'fail', 'loading'))) && p() && e('1');
r(in_array($biTest->updateDownloadingTagFileTest('file', 'remove'), array('ok', 'fail'))) && p() && e('1');
r(is_string($biTest->updateDownloadingTagFileTest('extension_dm', 'create'))) && p() && e('1');
r(is_string($biTest->updateDownloadingTagFileTest('extension_mysql', 'create'))) && p() && e('1');