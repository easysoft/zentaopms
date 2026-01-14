#!/usr/bin/env php
<?php
/**

title=测试 customModel->setItem();
timeout=0
cid=15925

- 测试path中.出现的个数为0，value正常存在，不能保存 @0
- 测试path中.出现的个数为1，value正常存在，不能保存 @0
- 测试path中.出现的个数为2，value正常存在，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @categoryList
 - 属性value @功能
- 测试path中.出现的个数为2，value为空，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @categoryList
 - 属性value @~~
- 测试path中.出现的个数为3，value正常存在，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @feature
 - 属性value @功能
- 测试path中.出现的个数为3，value为空，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @feature
 - 属性value @~~
- 测试path中.出现的个数为4，value正常存在，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @feature
 - 属性value @功能
- 测试path中.出现的个数为5，value为空，能保存
 - 属性lang @zh-cn
 - 属性module @story
 - 属性key @feature
 - 属性value @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->gen(0);
zenData('user')->gen(5);
su('admin');

$path  = array('zh-cn', 'zh-cn.story', 'zh-cn.story.categoryList', 'zh-cn.story.categoryList.feature', 'zh-cn.story.categoryList.feature.1');
$value = array('功能', '');

$customTester = new customModelTest();
r($customTester->setItemTest($path[0], $value[0])) && p()                        && e('0');                             // 测试path中.出现的个数为0，value正常存在，不能保存
r($customTester->setItemTest($path[1], $value[0])) && p()                        && e('0');                             // 测试path中.出现的个数为1，value正常存在，不能保存
r($customTester->setItemTest($path[2], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,categoryList,功能'); // 测试path中.出现的个数为2，value正常存在，能保存
r($customTester->setItemTest($path[2], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,categoryList,~~');   // 测试path中.出现的个数为2，value为空，能保存
r($customTester->setItemTest($path[3], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,feature,功能');      // 测试path中.出现的个数为3，value正常存在，能保存
r($customTester->setItemTest($path[3], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,feature,~~');        // 测试path中.出现的个数为3，value为空，能保存
r($customTester->setItemTest($path[4], $value[0])) && p('lang,module,key,value') && e('zh-cn,story,feature,功能');      // 测试path中.出现的个数为4，value正常存在，能保存
r($customTester->setItemTest($path[4], $value[1])) && p('lang,module,key,value') && e('zh-cn,story,feature,~~');       // 测试path中.出现的个数为5，value为空，能保存

zenData('lang')->gen(0);
