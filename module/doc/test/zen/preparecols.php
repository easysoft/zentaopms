#!/usr/bin/env php
<?php

/**

title=测试 docZen::prepareCols();
timeout=0
cid=0

- 执行$result1['actions'] @1
- 执行$result2['id']['name'] @id
- 执行$result3['id']['sortType'] === false @1
- 执行$result4['title']['link'] @1
- 执行$result5['name']['nestedToggle'] @1
- 执行$result6) === 0 @1
- 执行$result7) === 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

$cols1 = array(
    'id'      => array('title' => 'ID', 'width' => 60),
    'title'   => array('title' => 'Title', 'width' => 200, 'link' => array('url' => '/doc-view-{id}.html')),
    'actions' => array('title' => 'Actions', 'width' => 100)
);

$cols2 = array(
    'name'    => array('title' => 'Name', 'width' => 100, 'nestedToggle' => true),
    'status'  => array('title' => 'Status', 'width' => 80)
);

$cols3 = array();

$cols4 = array(
    'actions' => array('title' => 'Actions', 'width' => 100)
);

$result1 = $docTest->prepareColsTest($cols1);
$result2 = $docTest->prepareColsTest($cols1);
$result3 = $docTest->prepareColsTest($cols1);
$result4 = $docTest->prepareColsTest($cols1);
$result5 = $docTest->prepareColsTest($cols2);
$result6 = $docTest->prepareColsTest($cols3);
$result7 = $docTest->prepareColsTest($cols4);

r(!isset($result1['actions'])) && p() && e('1');
r($result2['id']['name']) && p() && e('id');
r($result3['id']['sortType'] === false) && p() && e('1');
r(!isset($result4['title']['link'])) && p() && e('1');
r(!isset($result5['name']['nestedToggle'])) && p() && e('1');
r(count($result6) === 0) && p() && e('1');
r(count($result7) === 0) && p() && e('1');