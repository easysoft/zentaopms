#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraDataToJson();
timeout=0
cid=15777

- 根据接口获取issue数据。 @0
- 根据接口获取project数据。 @0
- 根据接口获取file数据。 @0
- 根据接口获取action数据。 @0
- 根据接口获取user数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->getJiraDataToJsonTest('issue'))   && p() && e('0'); // 根据接口获取issue数据。
r($convertTest->getJiraDataToJsonTest('project')) && p() && e('0'); // 根据接口获取project数据。
r($convertTest->getJiraDataToJsonTest('file'))    && p() && e('0'); // 根据接口获取file数据。
r($convertTest->getJiraDataToJsonTest('action'))  && p() && e('0'); // 根据接口获取action数据。
r($convertTest->getJiraDataToJsonTest('user'))    && p() && e('0'); // 根据接口获取user数据。
