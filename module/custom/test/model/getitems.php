#!/usr/bin/env php
<?php
/**

title=测试 customModel->getItems();
timeout=0
cid=15898

- 测试参数为空
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd
- 测试参数为lang
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd
- 测试参数为lang,module
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd
- 测试参数为lang,key,section
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd
- 测试参数为lang,key,section,module
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd
- 测试参数为lang,key,section,module,vision
 - 第1条的lang属性 @zh-cn
 - 第1条的module属性 @custom
 - 第1条的section属性 @URSRList
 - 第1条的key属性 @1
 - 第1条的system属性 @1
 - 第1条的vision属性 @rnd

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);
su('admin');

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$customTester = new customModelTest();
r($customTester->getItemsTest($paramString[0])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为空
r($customTester->getItemsTest($paramString[1])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang
r($customTester->getItemsTest($paramString[2])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,module
r($customTester->getItemsTest($paramString[3])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section
r($customTester->getItemsTest($paramString[4])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section,module
r($customTester->getItemsTest($paramString[5])) && p('1:lang,module,section,key,system,vision') && e('zh-cn,custom,URSRList,1,1,rnd');  //测试参数为lang,key,section,module,vision

zenData('lang')->gen(0);
