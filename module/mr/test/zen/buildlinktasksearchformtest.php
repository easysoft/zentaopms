#!/usr/bin/env php
<?php
/**

title=测试 userZen::buildLinkTaskSearchForm();
timeout=0
cid=0

- 搜素的模块属性module @mrTask
- 搜索的URL属性actionURL @module/mr/test/zen/buildlinktasksearchformtest.php?m=mr&f=linkTask&MRID=1&repoID=2&browseType=bySearch&param=myQueryID&orderBy=id_desc
- 搜素的queryID属性queryID @2
- 搜素的字段
 - 第fields条的id属性 @编号
 - 第fields条的name属性 @任务名称

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->methodName = 'buildLinkTaskSearchForm';

$mrTest = new mrZenTest();
r($mrTest->buildLinkTaskSearchFormTest(1, 1, 'id_desc', 0, [])) && p('module')         && e('mrTask'); // 搜素的模块
r($mrTest->buildLinkTaskSearchFormTest(1, 2, 'id_desc', 0, [])) && p('actionURL')      && e('module/mr/test/zen/buildlinktasksearchformtest.php?m=mr&f=linkTask&MRID=1&repoID=2&browseType=bySearch&param=myQueryID&orderBy=id_desc');//搜索的URL
r($mrTest->buildLinkTaskSearchFormTest(2, 1, 'id_desc', 2, [])) && p('queryID')        && e('2'); // 搜素的queryID
r($mrTest->buildLinkTaskSearchFormTest(2, 2, 'id_desc', 0, [])) && p('fields:id,name') && e('编号,任务名称'); // 搜素的字段
