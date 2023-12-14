#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::checkAccessLevel();
timeout=0
cid=1

- 使用空的权限信息查询 @40
- 使用只有维护者权限信息查询 @40
- 使用包含维护者和开发者权限信息查询 @30
- 使用包含禁止权限信息查询 @0
- 使用包含禁止权限信息的二维数组查询 @0

*/

$gitlab = $tester->loadModel('gitlab');

$accessLevels = array();

$result = $gitlab->checkAccessLevel($accessLevels);
r($result) && p() && e('40'); //使用空的权限信息查询

$accessLevels[0] = new stdClass();
$accessLevels[0]->access_level = 40;
$accessLevels[0]->access_level_description = 'Maintainers';
r($gitlab->checkAccessLevel($accessLevels)) && p() && e('40'); //使用只有维护者权限信息查询

$accessLevels[1] = new stdClass();
$accessLevels[1]->access_level = 30;
$accessLevels[1]->access_level_description = 'Developers + Maintainers';
r($gitlab->checkAccessLevel($accessLevels)) && p() && e('30'); //使用包含维护者和开发者权限信息查询

$accessLevels[2] = new stdClass();
$accessLevels[2]->access_level = 0;
$accessLevels[2]->access_level_description = 'No one';
r($gitlab->checkAccessLevel($accessLevels)) && p() && e('0'); //使用包含禁止权限信息查询

$accessLevelArray = array(
    array(
        'access_level' => 40,
        'access_level_description' => 'Maintainers'
    ),
    array(
        'access_level' => 0,
        'access_level_description' => 'No one'
    )
);
r($gitlab->checkAccessLevel($accessLevelArray)) && p() && e('0'); //使用包含禁止权限信息的二维数组查询