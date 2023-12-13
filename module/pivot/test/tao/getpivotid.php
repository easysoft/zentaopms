#!/usr/bin/env php
<?php

/**
title=测试 pivotTao->getpivotid();
cid=1
pid=1

获取groupID=60的第一张透视表的id  >> 1027
获取groupID=100的第一张透视表的id  >> 1002
获取groupID=85的第一张透视表的id  >> 1001
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();
$groupIDList = array(60, 100, 85);

r($pivot->getPivotID($groupIDList[0])) && p('') && e('1027');             //测试获取groupID=60的第一张透视表的id
r($pivot->getPivotID($groupIDList[1])) && p('') && e('1002');             //测试获取groupID=100的第一张透视表的id
r($pivot->getPivotID($groupIDList[2])) && p('') && e('1001');             //测试获取groupID=85的第一张透视表的id
