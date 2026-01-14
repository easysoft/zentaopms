#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 customModel->prepareSQL();
timeout=0
cid=15920

*/

$lang = zenData('lang');
$lang->lang->range('zh-cn');
$lang->module->range('custom');
$lang->vision->range('rnd');
$lang->gen(1, true);

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=section1';
$key         = 'key=key1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);
$method      = array('select', 'delete');

$customTester = new customModelTest();
r($customTester->prepareSQLTest($paramString[0], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为空
r($customTester->prepareSQLTest($paramString[1], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang
r($customTester->prepareSQLTest($paramString[2], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,module
r($customTester->prepareSQLTest($paramString[3], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section
r($customTester->prepareSQLTest($paramString[4], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section,module
r($customTester->prepareSQLTest($paramString[5], $method[0])) && p('key1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section,module,vision
r($customTester->prepareSQLTest($paramString[0], $method[1])) && p()                   && e('1');             //测试method为delete，paramString参数为空
r($customTester->prepareSQLTest($paramString[1], $method[1])) && p()                   && e('0');             //测试method为delete，paramString参数为lang
r($customTester->prepareSQLTest($paramString[2], $method[1])) && p()                   && e('0');             //测试method为delete，paramString参数为lang,module
r($customTester->prepareSQLTest($paramString[3], $method[1])) && p()                   && e('0');             //测试method为delete，paramString参数为lang,key,section
r($customTester->prepareSQLTest($paramString[4], $method[1])) && p()                   && e('0');             //测试method为delete，paramString参数为lang,key,section,module
r($customTester->prepareSQLTest($paramString[5], $method[1])) && p()                   && e('0');             //测试method为delete，paramString参数为lang,key,section,module,vision
