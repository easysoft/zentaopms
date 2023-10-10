#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getStoryForExport();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

zdTable('story')->config('story')->gen(10);
zdTable('user')->gen(5);
su('admin');

$conditions[0] = 'select * from ' . TABLE_STORY .'where id in (1, 2, 3, 4, 5)';
$conditions[1] = 'select * from ' . TABLE_STORY .'where product=1';
$conditions[2] = 'select * from ' . TABLE_STORY .'where title like "%1%" and product=1';
$orderBy       = 'id DESC';

$releaseTester = new releaseTest();
r($releaseTester->getStoryForExportTest($conditions[0], $orderBy)) && p('0:title') && e('需求5');  // 根据条件获取需求列表信息
r($releaseTester->getStoryForExportTest($conditions[1], $orderBy)) && p('0:title') && e('需求10'); // 根据条件获取需求列表信息
r($releaseTester->getStoryForExportTest($conditions[2], $orderBy)) && p('0:title') && e('需求10'); // 根据条件获取需求列表信息
