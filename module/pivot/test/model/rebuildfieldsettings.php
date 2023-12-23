#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 pivotModel->rebuildFieldSettings().
timeout=0
cid=1

- 测试name字段的重建，校验重构后的值是否正确。
 - 第name条的object属性 @username
 - 第name条的field属性 @name
 - 第name条的type属性 @string
- 测试age字段的重建，校验重构后的值是否正确。
 - 第age条的name属性 @age
 - 第age条的field属性 @age
 - 第age条的type属性 @age1
 - 第age条的object属性 @userage

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$fieldPairs = array();
$fieldPairs['name'] = 'name';
$fieldPairs['age'] = 'age';
$fieldPairs['score'] = 'score';

$columns = new stdclass();
$columns->name = 'name1';
$columns->age = 'age1';
$columns->score = 'score';

$relatedObject = array();
$relatedObject['name'] = 'username';
$relatedObject['age'] = 'userage';
$relatedObject['score'] = 'userscore';

$fieldSettings = new stdclass();
$fieldSettings->name = new stdclass();
$fieldSettings->score = new stdclass();
$fieldSettings->score->object = false;

$objectFields = array();
$objectFields['username']['name'] = array();
$objectFields['username']['name']['name'] = 'testname';
$objectFields['username']['name']['type'] = 'object';

$return = $pivot->rebuildFieldSettings($fieldPairs, $columns, $relatedObject, $fieldSettings, $objectFields);
r($return) && p('name:object,field,type') && e('username,name,string');     //测试name字段的重建，校验重构后的值是否正确。
r($return) && p('age:name,field,type,object') && e('age,age,age1,userage'); //测试age字段的重建，校验重构后的值是否正确。
