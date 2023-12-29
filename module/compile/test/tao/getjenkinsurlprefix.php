#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel->getJenkinsUrlPrefix().
timeout=0
cid=1

- 测试当url为http:www.baidu.com, pipeline包含/job/且在开头的情况，生成的url链接是否正确。 @http://www.baidu.com/job/Job1
- 测试当url为http:www.google.com, pipeline不包含/job/的情况，生成的url链接是否正确。 @http://www.google.com/job/Job2/
- 测试当url为http:www.sina.com.cn, pipeline包含/job/但不在开头的情况，生成的url链接是否正确。 @http://www.sina.com.cn/test3/job/Job3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('job')->gen(1);
su('admin');

$compile = new compileTest();

$urlList = array('http://www.baidu.com', 'http://www.google.com', 'http://www.sina.com.cn');
$pipelineList = array('/job/Job1', 'Job2', '/test3/job/Job3');

r($compile->getJenkinsUrlPrefix($urlList[0], $pipelineList[0])) && p() && e('http://www.baidu.com/job/Job1');          //测试当url为http://www.baidu.com, pipeline包含/job/且在开头的情况，生成的url链接是否正确。
r($compile->getJenkinsUrlPrefix($urlList[1], $pipelineList[1])) && p() && e('http://www.google.com/job/Job2/');        //测试当url为http://www.google.com, pipeline不包含/job/的情况，生成的url链接是否正确。
r($compile->getJenkinsUrlPrefix($urlList[2], $pipelineList[2])) && p() && e('http://www.sina.com.cn/test3/job/Job3');  //测试当url为http://www.sina.com.cn, pipeline包含/job/但不在开头的情况，生成的url链接是否正确。
