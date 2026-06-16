#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSinglePipeline();
timeout=0
cid=16619

- 执行gitlabTest模块的apiGetSinglePipelineTest方法，参数是1, 2, 8 属性status @failed
- 执行gitlabTest模块的apiGetSinglePipelineTest方法，参数是0, 2, 8  @0
- 执行gitlabTest模块的apiGetSinglePipelineTest方法，参数是1, 0, 8 属性message @404 Project Not Found
- 执行gitlabTest模块的apiGetSinglePipelineTest方法，参数是1, 2, 10001 属性message @404 Not found
- 执行gitlabTest模块的apiGetSinglePipelineTest方法，参数是-1, -1, -1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetSinglePipelineTest(1, 2, 8)) && p('status') && e('failed');
r($gitlabTest->apiGetSinglePipelineTest(0, 2, 8)) && p() && e('0');
r($gitlabTest->apiGetSinglePipelineTest(1, 0, 8)) && p('message') && e('404 Project Not Found');
r($gitlabTest->apiGetSinglePipelineTest(1, 2, 10001)) && p('message') && e('404 Not found');
r($gitlabTest->apiGetSinglePipelineTest(-1, -1, -1)) && p() && e('0');
