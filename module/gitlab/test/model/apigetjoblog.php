#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetJobLog();
timeout=0
cid=16606

- 执行gitlabTest模块的apiGetJobLogTest方法，参数是1, 2, 8  @0
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是999, 2, 8  @0
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是1, 9999, 8 属性message @404 Project Not Found
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是1, 2, 9999 属性message @404 Not found
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是0, 2, 8  @0
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是1, -1, 8 属性message @404 Project Not Found
- 执行gitlabTest模块的apiGetJobLogTest方法，参数是1, 2, -1 属性message @404 Not found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(3);
zenData('job')->gen(10);

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetJobLogTest(1, 2, 8)) && p() && e('0');
r($gitlabTest->apiGetJobLogTest(999, 2, 8)) && p() && e('0');
r(json_decode($gitlabTest->apiGetJobLogTest(1, 9999, 8))) && p('message') && e('404 Project Not Found');
r(json_decode($gitlabTest->apiGetJobLogTest(1, 2, 9999))) && p('message') && e('404 Not found');
r($gitlabTest->apiGetJobLogTest(0, 2, 8)) && p() && e('0');
r(json_decode($gitlabTest->apiGetJobLogTest(1, -1, 8))) && p('message') && e('404 Project Not Found');
r(json_decode($gitlabTest->apiGetJobLogTest(1, 2, -1))) && p('message') && e('404 Not found');