#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSingleJob();
timeout=0
cid=16618

- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是1, 2, 8 属性stage @deploy
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是0, 2, 8  @0
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是1, 0, 8 属性message @404 Project Not Found
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是1, 2, 10001 属性message @404 Not found
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是1, 2, -1 属性message @404 Not found
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是1, 2, 999999 属性message @404 Not found
- 执行gitlabTest模块的apiGetSingleJobTest方法，参数是999, 2, 8  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('job')->gen(5);

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetSingleJobTest(1, 2, 8)) && p('stage') && e('deploy');
r($gitlabTest->apiGetSingleJobTest(0, 2, 8)) && p() && e('0');
r($gitlabTest->apiGetSingleJobTest(1, 0, 8)) && p('message') && e('404 Project Not Found');
r($gitlabTest->apiGetSingleJobTest(1, 2, 10001)) && p('message') && e('404 Not found');
r($gitlabTest->apiGetSingleJobTest(1, 2, -1)) && p('message') && e('404 Not found');
r($gitlabTest->apiGetSingleJobTest(1, 2, 999999)) && p('message') && e('404 Not found');
r($gitlabTest->apiGetSingleJobTest(999, 2, 8)) && p() && e('0');