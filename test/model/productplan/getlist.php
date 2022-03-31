#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=cid=1
cid=1
pid=1

获取product=1的所有数据 >> 3

*/

$productplan = new Productplan('admin');

$List = array();
$List[0] = 1;
$List[1] = 0;
$List[2] = 'all';
$List[3] = null;
$List[4] = 'begin_desc';
$List[5] = 'kipparent';

r($productplan->getList(1, 0, 'all', null, 'begin_desc', 'kipparent')) && p('3:id') && e('3'); //获取product=1的所有数据
?>