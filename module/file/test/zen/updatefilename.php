#!/usr/bin/env php
<?php

/**

title=测试 fileZen::updateFileName();
timeout=0
cid=0

- 执行fileTest模块的updateFileNameZenTest方法，参数是1, 'newfile', 'txt' 属性result @success
- 执行fileTest模块的updateFileNameZenTest方法，参数是2, '', 'doc' 属性result @fail
- 执行fileTest模块的updateFileNameZenTest方法，参数是3, str_repeat 属性result @fail
- 执行fileTest模块的updateFileNameZenTest方法，参数是999, 'notfound', 'txt' 属性result @fail
- 执行fileTest模块的updateFileNameZenTest方法，参数是5, 'validname', 'zip' 属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/filezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('file')->loadYaml('file_updatefilename', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$fileTest = new fileZenTest();

r($fileTest->updateFileNameZenTest(1, 'newfile', 'txt')) && p('result') && e('success');
r($fileTest->updateFileNameZenTest(2, '', 'doc')) && p('result') && e('fail'); 
r($fileTest->updateFileNameZenTest(3, str_repeat('a', 81), 'pdf')) && p('result') && e('fail');
r($fileTest->updateFileNameZenTest(999, 'notfound', 'txt')) && p('result') && e('fail');
r($fileTest->updateFileNameZenTest(5, 'validname', 'zip')) && p('result') && e('success');