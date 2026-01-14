#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 customModel->deleteItems();
timeout=0
cid=15893

- 测试参数为空 @1
- 测试参数为lang @1
- 测试参数为lang,module @1
- 测试参数为lang,key,section @1
- 测试参数为lang,key,section,module @1
- 测试参数为lang,key,section,module,vision @1

*/

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$customTester = new customModelTest();

r($customTester->deleteItemsTest($paramString[0])) && p() && e('1');  //测试参数为空
r($customTester->deleteItemsTest($paramString[1])) && p() && e('1');  //测试参数为lang
r($customTester->deleteItemsTest($paramString[2])) && p() && e('1');  //测试参数为lang,module
r($customTester->deleteItemsTest($paramString[3])) && p() && e('1');  //测试参数为lang,key,section
r($customTester->deleteItemsTest($paramString[4])) && p() && e('1');  //测试参数为lang,key,section,module
r($customTester->deleteItemsTest($paramString[5])) && p() && e('1');  //测试参数为lang,key,section,module,vision
