#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';
su('admin');

/**

title=测试 customModel->parseItemParam();
timeout=0
cid=15919

- 测试参数为lang,key,section,module,vision
 - 属性lang @zh-cn
 - 属性module @custom
 - 属性section @URSRList
 - 属性key @1
 - 属性vision @rnd
- 测试参数为lang,key,section,module
 - 属性lang @zh-cn
 - 属性module @custom
 - 属性section @URSRList
 - 属性key @1
 - 属性vision @~~
- 测试参数为lang,key,section
 - 属性lang @zh-cn
 - 属性module @~~
 - 属性section @URSRList
 - 属性key @1
 - 属性vision @~~
- 测试参数为lang,module
 - 属性lang @zh-cn
 - 属性module @custom
 - 属性section @~~
 - 属性key @~~
 - 属性vision @~~
- 测试参数为lang
 - 属性lang @zh-cn
 - 属性module @~~
 - 属性section @~~
 - 属性key @~~
 - 属性vision @~~
- 测试参数为空
 - 属性lang @~~
 - 属性module @~~
 - 属性section @~~
 - 属性key @~~
 - 属性vision @~~

*/

zenData('lang')->loadYaml('lang')->gen(5);

$lang        = 'lang=zh-cn';
$module      = 'module=custom';
$section     = 'section=URSRList';
$key         = 'key=1';
$vision      = 'vision=rnd';
$paramString = array('', $lang, $lang.'&'.$module, $lang.'&'.$key.'&'.$section, $lang.'&'.$key.'&'.$section.'&'.$module, $lang.'&'.$key.'&'.$section.'&'.$module.'&'.$vision);

$customTester = new customTest();
r($customTester->parseItemParamTest($paramString[5])) && p('lang,module,section,key,vision') && e('zh-cn,custom,URSRList,1,rnd'); // 测试参数为lang,key,section,module,vision
r($customTester->parseItemParamTest($paramString[4])) && p('lang,module,section,key,vision') && e('zh-cn,custom,URSRList,1,~~');  // 测试参数为lang,key,section,module
r($customTester->parseItemParamTest($paramString[3])) && p('lang,module,section,key,vision') && e('zh-cn,~~,URSRList,1,~~');      // 测试参数为lang,key,section
r($customTester->parseItemParamTest($paramString[2])) && p('lang,module,section,key,vision') && e('zh-cn,custom,~~,~~,~~');       // 测试参数为lang,module
r($customTester->parseItemParamTest($paramString[1])) && p('lang,module,section,key,vision') && e('zh-cn,~~,~~,~~,~~');           // 测试参数为lang
r($customTester->parseItemParamTest($paramString[0])) && p('lang,module,section,key,vision') && e('~~,~~,~~,~~,~~');              // 测试参数为空
