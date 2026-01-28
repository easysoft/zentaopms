#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

/**

title=jobModel->getServerAndPipeline();
timeout=0
cid=16857

- 有构建和代码库的情况
 - 属性server @0
 - 属性name @这是一个Job1
- 有构建没有代码库的情况
 - 属性server @1
 - 属性name @这是一个Job2
- 有构建和代码库的情况
 - 属性server @0
 - 属性name @这是一个Job3
- 有构建和代码库的情况
 - 属性server @1
 - 属性name @这是一个Job4
- 有构建和代码库的情况
 - 属性server @0
 - 属性name @这是一个Job5

*/

zenData('job')->loadYaml('job')->gen(5);
zenData('repo')->gen(5);

$job = new jobTaoTest();

r($job->getServerAndPipelineTest(1, 1)) && p('server,name')&& e('0,这是一个Job1'); // 有构建和代码库的情况
r($job->getServerAndPipelineTest(2, 0)) && p('server,name')&& e('1,这是一个Job2'); // 有构建没有代码库的情况
r($job->getServerAndPipelineTest(3, 3)) && p('server,name')&& e('0,这是一个Job3'); // 有构建和代码库的情况
r($job->getServerAndPipelineTest(4, 4)) && p('server,name')&& e('1,这是一个Job4'); // 有构建和代码库的情况
r($job->getServerAndPipelineTest(5, 5)) && p('server,name')&& e('0,这是一个Job5'); // 有构建和代码库的情况
