#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->deleteItems();
cid=1
pid=1

测试参数为空 >> 0
测试参数为lang >> 0
测试参数为lang,module >> 0
测试参数为lang,key,section >> 0
测试参数为lang,key,section,module >> 0
测试参数为lang,key,section,module,vision >> 0

*/

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$custom = new customTest();

r($custom->deleteItemsTest($paramString[0])) && p() && e('0');  //测试参数为空
r($custom->deleteItemsTest($paramString[1])) && p() && e('0');  //测试参数为lang
r($custom->deleteItemsTest($paramString[2])) && p() && e('0');  //测试参数为lang,module
r($custom->deleteItemsTest($paramString[3])) && p() && e('0');  //测试参数为lang,key,section
r($custom->deleteItemsTest($paramString[4])) && p() && e('0');  //测试参数为lang,key,section,module
r($custom->deleteItemsTest($paramString[5])) && p() && e('0');  //测试参数为lang,key,section,module,vision
