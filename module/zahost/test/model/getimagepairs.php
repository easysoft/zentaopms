#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::getImagePairs();
timeout=0
cid=19748

- 执行zahostTest模块的getImagePairsTest方法，参数是1 属性1 @ubuntu18.04
- 执行zahostTest模块的getImagePairsTest方法，参数是2 属性4 @mysql8.0
- 执行zahostTest模块的getImagePairsTest方法，参数是3 属性6 @nginx1.18
- 执行zahostTest模块的getImagePairsTest方法，参数是999  @0
- 执行zahostTest模块的getImagePairsTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('image');
$table->host->range('1{3},2{2},3{2}');
$table->name->range('ubuntu18.04,centos7,debian10,mysql8.0,redis6.2,nginx1.18,tomcat9.0');
$table->status->range('completed{6},creating{1}');
$table->gen(7);

su('admin');

$zahostTest = new zahostModelTest();

r($zahostTest->getImagePairsTest(1)) && p('1') && e('ubuntu18.04');
r($zahostTest->getImagePairsTest(2)) && p('4') && e('mysql8.0');
r($zahostTest->getImagePairsTest(3)) && p('6') && e('nginx1.18');
r($zahostTest->getImagePairsTest(999)) && p() && e('0');
r($zahostTest->getImagePairsTest(0)) && p() && e('0');