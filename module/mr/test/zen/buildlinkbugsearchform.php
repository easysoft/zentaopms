#!/usr/bin/env php
<?php

/**

title=测试 mrZen::buildLinkBugSearchForm();
timeout=0
cid=0

- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_desc', 0 属性queryID @0
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, 'id_asc', 0 属性style @simple
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是999, 888, 'priority_desc', 5 属性queryID @5
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是0, 1, 'id_desc', 0  @invalid_mrid
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 0, 'id_desc', 0  @invalid_repoid
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是1, 1, '', 0  @empty_orderby
- 执行mrTest模块的buildLinkBugSearchFormTest方法，参数是10, 20, 'title_asc', 99 属性actionURL @contains_mrid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

$mrTest = new mrTest();

r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_desc', 0)) && p('queryID') && e('0');
r($mrTest->buildLinkBugSearchFormTest(1, 1, 'id_asc', 0)) && p('style') && e('simple');
r($mrTest->buildLinkBugSearchFormTest(999, 888, 'priority_desc', 5)) && p('queryID') && e('5');
r($mrTest->buildLinkBugSearchFormTest(0, 1, 'id_desc', 0)) && p() && e('invalid_mrid');
r($mrTest->buildLinkBugSearchFormTest(1, 0, 'id_desc', 0)) && p() && e('invalid_repoid');
r($mrTest->buildLinkBugSearchFormTest(1, 1, '', 0)) && p() && e('empty_orderby');
r($mrTest->buildLinkBugSearchFormTest(10, 20, 'title_asc', 99)) && p('actionURL') && e('contains_mrid');