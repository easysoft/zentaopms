#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 gitlabModel::apiUpdateGroup();
timeout=0
cid=16628

- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $emptyGroup  @0
- 执行gitlab模块的apiUpdateGroup方法，参数是0, $invalidGroup  @0
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $invalidGroup 属性description @apiUpdatedGroup
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $validGroup 属性name @Updated Group Name
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $nonExistentGroup  @~~

*/

$gitlab   = new gitlabModelTest();
$gitlabID = 1;
$groupID  = 14;

/* Test cases. */
$emptyGroup = new stdclass();
$emptyGroup->description = 'test description';

$invalidGroup = new stdclass();
$invalidGroup->id = $groupID;
$invalidGroup->description = 'apiUpdatedGroup';

$validGroup = new stdclass();
$validGroup->id = $groupID;
$validGroup->name = 'Updated Group Name';
$validGroup->description = 'Updated description';

$nonExistentGroup = new stdclass();
$nonExistentGroup->id = 888888;
$nonExistentGroup->description = 'Non-existent group';

r($gitlab->apiUpdateGroupTest($gitlabID, $emptyGroup)) && p() && e('0');
r($gitlab->apiUpdateGroupTest(0, $invalidGroup)) && p() && e('0');
r($gitlab->apiUpdateGroupTest($gitlabID, $invalidGroup)) && p('description') && e('apiUpdatedGroup');
r($gitlab->apiUpdateGroupTest($gitlabID, $validGroup)) && p('name') && e('Updated Group Name');
r($gitlab->apiUpdateGroupTest($gitlabID, $nonExistentGroup)) && p() && e('0');
