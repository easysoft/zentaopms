#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::includeTable();
timeout=0
cid=1

- 获取bug表的配置项。
 - 属性primaryTable @bug
 - 第tables条的bug属性 @zt_bug
- 获取产品表的配置项。
 - 属性primaryTable @product
 - 第tables条的product属性 @zt_product

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->includeTable('bug'))     && p('primaryTable;tables:bug') && e('bug,zt_bug');              //获取bug表的配置项。
r($tester->dataview->includeTable('product')) && p('primaryTable;tables:product') && e('product,zt_product');  //获取产品表的配置项。
