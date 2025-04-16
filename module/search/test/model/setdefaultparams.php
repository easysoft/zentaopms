#!/usr/bin/env php
<?php

/**

title=测试 searchModel->setDefaultParams();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zenData('user')->gen(5);
zenData('project')->gen(30);
zenData('projectproduct')->gen(10);
zenData('product')->gen(10);

su('admin');