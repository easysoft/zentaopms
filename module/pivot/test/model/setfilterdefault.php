#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->setFilterDefault();
cid=1
pid=1

测试传入的默认值为空，不对默认值进行处理   >> 1
测试传入的默认值为字符串，对默认值进行处理 >> 1
测试传入的默认值为数组，看是否对默认值了进行处理 >> 1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';
$pivot = new pivotTest();

$filter1 = array();
$filter1['default'] = '$MONDAY';

$filter2 = array();
$filter2['default'] = array('start' => '', 'end' => '');

$filterList = array(array('default' => ''), $filter1, $filter2);

$filters = $pivot->setFilterDefault($filterList);
$monday = date('Y-m-d', strtotime('monday this week'));

r($filters[0]['default'] === '') && p('') && e('1');                                                           //测试传入的默认值为空，不对默认值进行处理
r($filters[1]['default'] == $monday) && p('') && e("1");                                             //测试传入的默认值为字符串，对默认值进行处理
r($filters[2]['default']['start'] == '' && $filters[2]['default']['end'] == '') && p('') && e("1");  //测试传入的默认值为数组，看是否对默认值了进行处理
