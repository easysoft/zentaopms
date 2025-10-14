#!/usr/bin/env php
<?php

/**

title=测试 jobZen::buildSearchForm();
timeout=0
cid=0

- 执行jobTest模块的buildSearchFormTest方法，参数是array 第searchConfig条的module属性 @job
- 执行jobTest模块的buildSearchFormTest方法，参数是array 第searchConfig条的queryID属性 @0
- 执行jobTest模块的buildSearchFormTest方法，参数是array 第searchConfig条的actionURL属性 @/job-browse.html
- 执行jobTest模块的buildSearchFormTest方法 第searchConfig条的module属性 @job
- 执行jobTest模块的buildSearchFormTest方法，参数是array 第searchConfig条的queryID属性 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

zendata('repo')->gen(3);
zendata('product')->gen(5);

su('admin');

$jobTest = new jobTest();

r($jobTest->buildSearchFormTest(array('module' => 'job', 'fields' => array('name' => '名称'), 'params' => array('name' => array('operator' => 'include', 'control' => 'input', 'values' => ''))), 0, '/job-browse.html')) && p('searchConfig:module') && e('job');
r($jobTest->buildSearchFormTest(array('module' => 'job', 'fields' => array('repo' => '版本库'), 'params' => array('repo' => array('operator' => '=', 'control' => 'select', 'values' => array()))), 0, '/job-browse.html')) && p('searchConfig:queryID') && e('0');
r($jobTest->buildSearchFormTest(array('module' => 'job', 'fields' => array('product' => '产品'), 'params' => array('product' => array('operator' => '=', 'control' => 'select', 'values' => array()))), 0, '/job-browse.html')) && p('searchConfig:actionURL') && e('/job-browse.html');
r($jobTest->buildSearchFormTest()) && p('searchConfig:module') && e('job');
r($jobTest->buildSearchFormTest(array('module' => 'job', 'fields' => array('name' => '名称'), 'params' => array('name' => array('operator' => 'include', 'control' => 'input', 'values' => ''))), 10, '/job-browse-10.html')) && p('searchConfig:queryID') && e('10');