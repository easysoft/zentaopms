#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->parseItemParam();
cid=1
pid=1

测试参数为空 >> ,,,,
测试参数为lang >> zh-cn,,,,
测试参数为lang,module >> zh-cn,custom,,,
测试参数为lang,key,section >> zh-cn,,URSRList,1,
测试参数为lang,key,section,module >> zh-cn,custom,URSRList,1,
测试参数为lang,key,section,module,vision >> zh-cn,custom,URSRList,1,rnd

*/

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$custom = new customTest();

r($custom->parseItemParamTest($paramString[0])) && p('lang,module,section,key,vision') && e(',,,,');                        //测试参数为空
r($custom->parseItemParamTest($paramString[1])) && p('lang,module,section,key,vision') && e('zh-cn,,,,');                   //测试参数为lang
r($custom->parseItemParamTest($paramString[2])) && p('lang,module,section,key,vision') && e('zh-cn,custom,,,');             //测试参数为lang,module
r($custom->parseItemParamTest($paramString[3])) && p('lang,module,section,key,vision') && e('zh-cn,,URSRList,1,');          //测试参数为lang,key,section
r($custom->parseItemParamTest($paramString[4])) && p('lang,module,section,key,vision') && e('zh-cn,custom,URSRList,1,');    //测试参数为lang,key,section,module
r($custom->parseItemParamTest($paramString[5])) && p('lang,module,section,key,vision') && e('zh-cn,custom,URSRList,1,rnd'); //测试参数为lang,key,section,module,vision