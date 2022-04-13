#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->getItems();
cid=1
pid=1

测试参数为空 >> zh-cn,custom,URSRList,1,1,rnd
测试参数为lang >> zh-cn,custom,URSRList,1,1,rnd
测试参数为lang,module >> zh-cn,custom,URSRList,1,1,rnd
测试参数为lang,key,section >> zh-cn,custom,URSRList,1,1,rnd
测试参数为lang,key,section,module >> zh-cn,custom,URSRList,1,1,rnd
测试参数为lang,key,section,module,vision >> zh-cn,custom,URSRList,1,1,rnd

*/
$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$custom = new customTest();

r($custom->getItemsTest($paramString[0])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为空
r($custom->getItemsTest($paramString[1])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang
r($custom->getItemsTest($paramString[2])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,module
r($custom->getItemsTest($paramString[3])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section
r($custom->getItemsTest($paramString[4])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section,module
r($custom->getItemsTest($paramString[5])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section,module,vision