#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('release')->gen(20);
zenData('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->getLast();
cid=17969

- 测试获取项目 11 的最后一次发布属性id @9
- 测试获取项目 12 的最后一次发布属性id @10
- 测试获取项目 13 的最后一次发布属性id @0
- 测试获取项目 空 的最后一次发布属性id @8
- 测试获取项目 不存在 的最后一次发布 @0

*/

$projectID = array(11, 12, 13, 0, 1000);

$projectrelease = new projectreleaseModelTest();

r($projectrelease->getLastTest($projectID[0])) && p('id') && e('9');  // 测试获取项目 11 的最后一次发布
r($projectrelease->getLastTest($projectID[1])) && p('id') && e('10'); // 测试获取项目 12 的最后一次发布
r($projectrelease->getLastTest($projectID[2])) && p('id') && e('0');  // 测试获取项目 13 的最后一次发布
r($projectrelease->getLastTest($projectID[3])) && p('id') && e('8');  // 测试获取项目 空 的最后一次发布
r($projectrelease->getLastTest($projectID[4])) && p()     && e('0');  // 测试获取项目 不存在 的最后一次发布
