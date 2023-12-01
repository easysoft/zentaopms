#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

/**

title=测试 settingModel->snNeededUpdate();
timeout=0
cid=1

- 测试sn值为空，返回true @1
- 测试sn值为281602d8ff5ee7533eeafd26eda4e776，返回true @1
- 测试sn值为9bed3108092c94a0db2b934a46268b4a，返回true @1
- 测试sn值为8522dd4d76762a49d02261ddbe4ad432，返回true @1
- 测试sn值为13593e340ee2bdffed640d0c4eed8bec，返回true @1
- 测试sn值为error，返回false @0

*/

$snList = array('', '281602d8ff5ee7533eeafd26eda4e776', '9bed3108092c94a0db2b934a46268b4a', '8522dd4d76762a49d02261ddbe4ad432', '13593e340ee2bdffed640d0c4eed8bec', 'error');

$setting = new settingTest();

r($setting->snNeededUpdateTest($snList[0])) && p() && e('1'); //测试sn值为空，返回true
r($setting->snNeededUpdateTest($snList[1])) && p() && e('1'); //测试sn值为281602d8ff5ee7533eeafd26eda4e776，返回true
r($setting->snNeededUpdateTest($snList[2])) && p() && e('1'); //测试sn值为9bed3108092c94a0db2b934a46268b4a，返回true
r($setting->snNeededUpdateTest($snList[3])) && p() && e('1'); //测试sn值为8522dd4d76762a49d02261ddbe4ad432，返回true
r($setting->snNeededUpdateTest($snList[4])) && p() && e('1'); //测试sn值为13593e340ee2bdffed640d0c4eed8bec，返回true
r($setting->snNeededUpdateTest($snList[5])) && p() && e('0'); //测试sn值为error，返回false