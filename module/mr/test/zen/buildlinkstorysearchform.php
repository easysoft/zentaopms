#!/usr/bin/env php
<?php

/**

title=测试 mrZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行mrTest模块的buildLinkStorySearchFormTest方法，参数是1, 1, 'id_desc', 5 属性queryID @5
- 执行mrTest模块的buildLinkStorySearchFormTest方法，参数是2, 2, 'id_asc', 0 属性queryID @0
- 执行mrTest模块的buildLinkStorySearchFormTest方法，参数是0, 1, 'id_desc', 1  @invalid_mrid
- 执行mrTest模块的buildLinkStorySearchFormTest方法，参数是1, 0, 'id_desc', 1  @invalid_repoid
- 执行mrTest模块的buildLinkStorySearchFormTest方法，参数是1, 1, '', 1  @empty_orderby

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('user');
zenData('product');
zenData('story');

su('admin');

$mrTest = new mrTest();

r($mrTest->buildLinkStorySearchFormTest(1, 1, 'id_desc', 5)) && p('queryID') && e(5);
r($mrTest->buildLinkStorySearchFormTest(2, 2, 'id_asc', 0)) && p('queryID') && e(0);
r($mrTest->buildLinkStorySearchFormTest(0, 1, 'id_desc', 1)) && p() && e('invalid_mrid');
r($mrTest->buildLinkStorySearchFormTest(1, 0, 'id_desc', 1)) && p() && e('invalid_repoid');
r($mrTest->buildLinkStorySearchFormTest(1, 1, '', 1)) && p() && e('empty_orderby');