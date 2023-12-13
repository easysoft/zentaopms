#!/usr/bin/env php
<?php

/**
title=测试 pivotTao->getAllPivotByGroupID();
cid=1
pid=1

获取groupID=60的所有透视表  >> 1027;1007
获取groupID=100的所有透视表 >> 1002
获取groupID=85的所有透视表  >> 1001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();
$groupIDList = array(60, 100, 85);

r($pivot->getAllPivotByGroupID($groupIDList[0])) && p('0:id;14:id') && e('1027;1007');  //测试获取groupID=60的所有透视表
r($pivot->getAllPivotByGroupID($groupIDList[1])) && p('0:id') && e('1002');             //测试获取groupID=100的所有透视表
r($pivot->getAllPivotByGroupID($groupIDList[2])) && p('0:id') && e('1001');             //测试获取groupID=85的所有透视表
