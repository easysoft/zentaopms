#!/usr/bin/env php
<?php

/**

title=测试 mrZen::buildLinkBugSearchForm();
timeout=0
cid=0

- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性actionURL @buildlinkbugsearchform.php?m=mr&f=linkBug&MRID=1&repoID=1&browseType=bySearch&param=myQueryID&orderBy=id_desc
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 5 属性queryID @5
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是2, 3, 'title_asc', 0 属性actionURL @buildlinkbugsearchform.php?m=mr&f=linkBug&MRID=2&repoID=3&browseType=bySearch&param=myQueryID&orderBy=title_asc
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性style @simple
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasProduct @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasPlan @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasModule @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasExecution @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasOpenedBuild @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性hasResolvedBuild @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('linkBug');

zenData('mr')->gen(0);
zenData('repo')->gen(0);

su('admin');

$mrTest = new mrZenTest();

r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('actionURL') && e('buildlinkbugsearchform.php?m=mr&f=linkBug&MRID=1&repoID=1&browseType=bySearch&param=myQueryID&orderBy=id_desc');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 5)) && p('queryID') && e('5');
r($mrTest->buildLinkBugSearchFormTest(2, 3, 'title_asc', 0)) && p('actionURL') && e('buildlinkbugsearchform.php?m=mr&f=linkBug&MRID=2&repoID=3&browseType=bySearch&param=myQueryID&orderBy=title_asc');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('style') && e('simple');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasProduct') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasPlan') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasModule') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasExecution') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasOpenedBuild') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('hasResolvedBuild') && e('0');