#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildSearchFormForTrack();
timeout=0
cid=17568

- 执行productTest模块的buildSearchFormForTrackTest方法，参数是1, '', 0, 'all', 0, 'story'
 - 属性productID @1
 - 属性searchModule @productTrack
- 执行productTest模块的buildSearchFormForTrackTest方法，参数是1, '', 0, 'all', 0, 'requirement'
 - 属性productID @1
 - 属性searchModule @productTrack
 - 属性hasRoadmapField @0
- 执行productTest模块的buildSearchFormForTrackTest方法，参数是1, '', 0, 'all', 0, 'story'
 - 属性productID @1
 - 属性searchModule @productTrack
 - 属性hasRoadmapField @0
- 执行productTest模块的buildSearchFormForTrackTest方法，参数是1, '', 11, 'bysearch', 1, 'story'
 - 属性productID @1
 - 属性searchModule @projectstoryTrack
- 执行productTest模块的buildSearchFormForTrackTest方法，参数是1, '1', 0, 'all', 0, 'story'
 - 属性productID @1
 - 属性searchModule @productTrack

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->gen(10);
zendata('branch')->gen(5);

su('admin');

$productTest = new productZenTest();

r($productTest->buildSearchFormForTrackTest(1, '', 0, 'all', 0, 'story')) && p('productID,searchModule') && e('1,productTrack');
r($productTest->buildSearchFormForTrackTest(1, '', 0, 'all', 0, 'requirement')) && p('productID,searchModule,hasRoadmapField') && e('1,productTrack,0');
r($productTest->buildSearchFormForTrackTest(1, '', 0, 'all', 0, 'story')) && p('productID,searchModule,hasRoadmapField') && e('1,productTrack,0');
r($productTest->buildSearchFormForTrackTest(1, '', 11, 'bysearch', 1, 'story')) && p('productID,searchModule') && e('1,projectstoryTrack');
r($productTest->buildSearchFormForTrackTest(1, '1', 0, 'all', 0, 'story')) && p('productID,searchModule') && e('1,productTrack');