#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel::getParis();
timeout=0
cid=1

- 获取repo为1且engine为jenkins的name属性1 @这是一个Job1
- 获取repo为1且engine为gitlab的name属性1 @0
- 获取repo为2且engine为gitlab的name属性2 @这是一个Job2
- 获取repo为2且engine为jenkins的name属性2 @0

*/

zdTable('job')->gen(2);

$job = new jobTest();

$repoID = '1';
$engine = 'jenkins';

r($job->getPairsTest($repoID, $engine))  && p('1') && e('这是一个Job1'); // 获取repo为1且engine为jenkins的name
r($job->getPairsTest($repoID, 'gitlab')) && p('1') && e('0');            // 获取repo为1且engine为gitlab的name
r($job->getPairsTest(2, 'gitlab'))       && p('2') && e('这是一个Job2'); // 获取repo为2且engine为gitlab的name
r($job->getPairsTest(2, 'jenkins'))      && p('2') && e('0');            // 获取repo为2且engine为jenkins的name