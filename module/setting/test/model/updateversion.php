#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

/**

title=测试 settingModel->updateVersion();
timeout=0
cid=1

- 测试version值为16.5，添加数据第1条的value属性 @16.5
- 测试version值为max3.0，更新数据第1条的value属性 @max3.0
- 测试version值为max2.0，更新数据第1条的value属性 @max2.0
- 测试version值为biz6.5，更新数据第1条的value属性 @biz6.5
- 测试version值为0，更新数据第1条的value属性 @0

*/

$versionList = array('16.5', 'max3.0', 'max2.0', 'biz6.5', '0');

$setting = new settingTest();

r($setting->updateVersionTest($versionList[0])) && p('1:value') && e('16.5');   //测试version值为16.5，添加数据
r($setting->updateVersionTest($versionList[1])) && p('1:value') && e('max3.0'); //测试version值为max3.0，更新数据
r($setting->updateVersionTest($versionList[2])) && p('1:value') && e('max2.0'); //测试version值为max2.0，更新数据
r($setting->updateVersionTest($versionList[3])) && p('1:value') && e('biz6.5'); //测试version值为biz6.5，更新数据
r($setting->updateVersionTest($versionList[4])) && p('1:value') && e('0');      //测试version值为0，更新数据