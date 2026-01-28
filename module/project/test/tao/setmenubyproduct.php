#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->gen(50);
zenData('projectproduct')->gen(50);
su('admin');

/**

title=测试 projectModel::setMenuByProduct();
cid=17917
pid=1

*/

$projectTester = new projectTaoTest();

r($projectTester->setMenuByProductTest(0, 'scrum'))         && p() && e('scrum|projectplan|settings'); // 敏捷无产品项目
r($projectTester->setMenuByProductTest(0, 'waterfall'))     && p() && e('waterfall||settings');        // 瀑布无产品项目
r($projectTester->setMenuByProductTest(0, 'kanban'))        && p() && e('kanban||settings');           // 看板无产品项目
r($projectTester->setMenuByProductTest(1, 'scrum'))         && p() && e('scrum||');                    // 敏捷有产品项目
r($projectTester->setMenuByProductTest(1, ''))              && p() && e('||');                         // 项目不存在的情况
r($projectTester->setMenuByProductTest(1, 'error'))         && p() && e('error||');                    // 项目模式错误的情况
r($projectTester->setMenuByProductTest(1, 'waterfall'))     && p() && e('waterfall||');                // 瀑布有产品项目
r($projectTester->setMenuByProductTest(1, 'waterfallplus')) && p() && e('waterfallplus||');            // 融合瀑布有产品项目
r($projectTester->setMenuByProductTest(1, 'kanban'))        && p() && e('kanban||');                   // 看板有产品项目
