#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraDataFromFile();
timeout=0
cid=15777

- 根据接口获取issue数据。 @0
- 根据接口获取project数据。 @0
- 根据接口获取file数据。 @0
- 根据接口获取action数据。 @0
- 根据接口获取user数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$tester->loadModel('convert');

r($tester->convert->getJiraDataFromApi('issue'))   && p() && e('0'); // 根据接口获取issue数据。
r($tester->convert->getJiraDataFromApi('project')) && p() && e('0'); // 根据接口获取project数据。
r($tester->convert->getJiraDataFromApi('file'))    && p() && e('0'); // 根据接口获取file数据。
r($tester->convert->getJiraDataFromApi('action'))  && p() && e('0'); // 根据接口获取action数据。
r($tester->convert->getJiraDataFromApi('user'))    && p() && e('0'); // 根据接口获取user数据。
