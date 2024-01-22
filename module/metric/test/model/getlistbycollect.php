#!/usr/bin/env php
<?php

/**

title=getListByCollect
timeout=0
cid=1

- 执行metric模块的getListByCollect方法 
 - 第0条的id属性 @10
 - 第0条的code属性 @count_of_annual_closed_top_program

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();
su('admin');

r($metric->getListByCollect()) && p('0:id,code') && e('10,count_of_annual_closed_top_program');