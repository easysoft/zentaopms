#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSingleTag();
timeout=0
cid=16621

- 执行gitlabTest模块的apiGetSingleTagTest方法，参数是1, 2, 'tag3' 属性name @tag3
- 执行gitlabTest模块的apiGetSingleTagTest方法，参数是0, 2, 'tag3'  @0
- 执行gitlabTest模块的apiGetSingleTagTest方法，参数是1, 0, 'tag3' 属性message @404 Project Not Found
- 执行gitlabTest模块的apiGetSingleTagTest方法，参数是1, 2, 'nonexistent' 属性message @404 Tag Not Found
- 执行gitlabTest模块的apiGetSingleTagTest方法，参数是1, 2, '' 属性message @404 Tag Not Found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetSingleTagTest(1, 2, 'tag3')) && p('name') && e('tag3');
r($gitlabTest->apiGetSingleTagTest(0, 2, 'tag3')) && p() && e('0');
r($gitlabTest->apiGetSingleTagTest(1, 0, 'tag3')) && p('message') && e('404 Project Not Found');
r($gitlabTest->apiGetSingleTagTest(1, 2, 'nonexistent')) && p('message') && e('404 Tag Not Found');
r($gitlabTest->apiGetSingleTagTest(1, 2, '')) && p('message') && e('404 Tag Not Found');