#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchEpicsBySearch();
timeout=0
cid=17307

- 执行myTest模块的fetchEpicsBySearchTest方法，参数是't1.deleted = 0', 'contribute', 'id_desc', null, array  @0
- 执行myTest模块的fetchEpicsBySearchTest方法，参数是't1.deleted = 0', 'assigned', 'id_desc'  @0
- 执行myTest模块的fetchEpicsBySearchTest方法，参数是't1.deleted = 0 AND t1.id > 0', 'contribute', 'id_desc', null, array  @0
- 执行myTest模块的fetchEpicsBySearchTest方法，参数是't1.deleted = 0', 'assigned', 'pri_desc'  @0
- 执行myTest模块的fetchEpicsBySearchTest方法，参数是't1.deleted = 0', 'contribute', 'title_asc'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('story')->gen(0);
zenData('product')->gen(0);
zenData('storyreview')->gen(0);

su('admin');

$myTest = new myTaoTest();

r($myTest->fetchEpicsBySearchTest('t1.deleted = 0', 'contribute', 'id_desc', null, array(1, 2, 3))) && p() && e('0');
r($myTest->fetchEpicsBySearchTest('t1.deleted = 0', 'assigned', 'id_desc')) && p() && e('0');
r($myTest->fetchEpicsBySearchTest('t1.deleted = 0 AND t1.id > 0', 'contribute', 'id_desc', null, array())) && p() && e('0');
r($myTest->fetchEpicsBySearchTest('t1.deleted = 0', 'assigned', 'pri_desc')) && p() && e('0');
r($myTest->fetchEpicsBySearchTest('t1.deleted = 0', 'contribute', 'title_asc')) && p() && e('0');