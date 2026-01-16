#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);
su('admin');

/**

title=测试 customModel->getURSRConcept();
timeout=0
cid=15904

- 获取key=1的需求概念
 - 属性SRName @软件需求
 - 属性URName @用户需求
- 获取key=2的需求概念
 - 属性SRName @研发需求
 - 属性URName @用户需求
- 获取key=3的需求概念
 - 属性SRName @软需
 - 属性URName @用需
- 获取key=4的需求概念
 - 属性SRName @故事
 - 属性URName @史诗
- 获取key=5的需求概念
 - 属性SRName @需求
 - 属性URName @用户需求

*/

$keyList = range(1, 5);

$customTester = new customModelTest();
r($customTester->getURSRConceptTest($keyList[0])) && p('SRName,URName') && e('软件需求,用户需求'); // 获取key=1的需求概念
r($customTester->getURSRConceptTest($keyList[1])) && p('SRName,URName') && e('研发需求,用户需求'); // 获取key=2的需求概念
r($customTester->getURSRConceptTest($keyList[2])) && p('SRName,URName') && e('软需,用需');         // 获取key=3的需求概念
r($customTester->getURSRConceptTest($keyList[3])) && p('SRName,URName') && e('故事,史诗');         // 获取key=4的需求概念
r($customTester->getURSRConceptTest($keyList[4])) && p('SRName,URName') && e('需求,用户需求');     // 获取key=5的需求概念