#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getVersionsToUpdate();
cid=1

- 测试获取开源版版本 18_8 来源版本 open 的升级版本 @pro:0;biz:0;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 pro 的升级版本 @pro:pro8_7;biz:0;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 biz 的升级版本 @pro:pro8_7;biz:biz3_6;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 max 的升级版本 @pro:pro8_7;biz:biz3_6;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 ipd 的升级版本 @pro:pro8_7;biz:biz3_6;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 open 的升级版本 @pro:0;biz:0;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 pro 的升级版本 @pro:0;biz:0;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 biz 的升级版本 @pro:0;biz:biz8_9;max:0;ipd:0
- 测试获取开源版版本 18_8 来源版本 max 的升级版本 @pro:0;biz:biz8_9;max:max4_9;ipd:0
- 测试获取开源版版本 18_8 来源版本 ipd 的升级版本 @pro:0;biz:biz8_9;max:max4_9;ipd:ipd1_1_1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$openVersion = array('12_0', '18_9');
$fromEdition = array('open', 'pro', 'biz', 'max', 'ipd');

$upgrade = new upgradeTest();
r($upgrade->getVersionsToUpdateTest($openVersion[0], $fromEdition[0])) && p() && e('pro:0;biz:0;max:0;ipd:0');           // 测试获取开源版版本 18_8 来源版本 open 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[0], $fromEdition[1])) && p() && e('pro:pro8_7;biz:0;max:0;ipd:0');      // 测试获取开源版版本 18_8 来源版本 pro 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[0], $fromEdition[2])) && p() && e('pro:pro8_7;biz:biz3_6;max:0;ipd:0'); // 测试获取开源版版本 18_8 来源版本 biz 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[0], $fromEdition[3])) && p() && e('pro:pro8_7;biz:biz3_6;max:0;ipd:0'); // 测试获取开源版版本 18_8 来源版本 max 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[0], $fromEdition[4])) && p() && e('pro:pro8_7;biz:biz3_6;max:0;ipd:0'); // 测试获取开源版版本 18_8 来源版本 ipd 的升级版本

r($upgrade->getVersionsToUpdateTest($openVersion[1], $fromEdition[0])) && p() && e('pro:0;biz:0;max:0;ipd:0');                  // 测试获取开源版版本 18_8 来源版本 open 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[1], $fromEdition[1])) && p() && e('pro:0;biz:0;max:0;ipd:0');                  // 测试获取开源版版本 18_8 来源版本 pro 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[1], $fromEdition[2])) && p() && e('pro:0;biz:biz8_9;max:0;ipd:0');             // 测试获取开源版版本 18_8 来源版本 biz 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[1], $fromEdition[3])) && p() && e('pro:0;biz:biz8_9;max:max4_9;ipd:0');        // 测试获取开源版版本 18_8 来源版本 max 的升级版本
r($upgrade->getVersionsToUpdateTest($openVersion[1], $fromEdition[4])) && p() && e('pro:0;biz:biz8_9;max:max4_9;ipd:ipd1_1_1'); // 测试获取开源版版本 18_8 来源版本 ipd 的升级版本
