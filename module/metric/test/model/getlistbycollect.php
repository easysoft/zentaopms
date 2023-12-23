#!/usr/bin/env php
<?php
/**
title=getListByCollect
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();
su('admin');

r($metric->getListByCollect()) && p('0:id,code') && e('10,count_of_annual_closed_top_program');
