#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->prepareSQL();
cid=1
pid=1

测试method为select，paramString参数为空 >> zh-cn,custom
测试method为select，paramString参数为lang >> zh-cn,custom
测试method为select，paramString参数为lang,module >> zh-cn,custom
测试method为select，paramString参数为lang,key,section >> zh-cn,custom
测试method为select，paramString参数为lang,key,section,module >> zh-cn,custom
测试method为select，paramString参数为lang,key,section,module,vision >> zh-cn,custom
测试method为delete，paramString参数为空 >> 14
测试method为delete，paramString参数为lang >> 0
测试method为delete，paramString参数为lang,module >> 0
测试method为delete，paramString参数为lang,key,section >> 0
测试method为delete，paramString参数为lang,key,section,module >> 0
测试method为delete，paramString参数为lang,key,section,module,vision >> 0

*/

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);
$method      = array('select', 'delete');

$custom = new customTest();

r($custom->prepareSQLTest($paramString[0], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为空
r($custom->prepareSQLTest($paramString[1], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang
r($custom->prepareSQLTest($paramString[2], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,module
r($custom->prepareSQLTest($paramString[3], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section
r($custom->prepareSQLTest($paramString[4], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section,module
r($custom->prepareSQLTest($paramString[5], $method[0])) && p('1:lang,module') && e('zh-cn,custom');  //测试method为select，paramString参数为lang,key,section,module,vision
r($custom->prepareSQLTest($paramString[0], $method[1])) && p()                && e('14');            //测试method为delete，paramString参数为空
r($custom->prepareSQLTest($paramString[1], $method[1])) && p()                && e('0');             //测试method为delete，paramString参数为lang
r($custom->prepareSQLTest($paramString[2], $method[1])) && p()                && e('0');             //测试method为delete，paramString参数为lang,module
r($custom->prepareSQLTest($paramString[3], $method[1])) && p()                && e('0');             //测试method为delete，paramString参数为lang,key,section
r($custom->prepareSQLTest($paramString[4], $method[1])) && p()                && e('0');             //测试method为delete，paramString参数为lang,key,section,module
r($custom->prepareSQLTest($paramString[5], $method[1])) && p()                && e('0');             //测试method为delete，paramString参数为lang,key,section,module,vision
