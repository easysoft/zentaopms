#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

zenData('pipeline')->loadYaml('pipeline')->gen(5);
zenData('job')->loadYaml('job_getprojectpairs')->gen(3);
zenData('user')->gen(5);

/**

title=测试 sonarqubeModel::getProjectPairs();
timeout=0
cid=18386

- 执行sonarqubeTest模块的getProjectPairsTest方法  @0
- 执行sonarqubeTest模块的getProjectPairsTest方法，参数是2  @6
- 执行sonarqubeTest模块的getProjectPairsTest方法，参数是2 属性bendi @本地项目
- 执行sonarqubeTest模块的getProjectPairsTest方法，参数是2, 'bendi' 属性bendi @本地项目
- 执行sonarqubeTest模块的getProjectPairsTest方法，参数是2, ''  @6

*/

su('admin');

$sonarqubeTest = new sonarqubeTest();

r(count($sonarqubeTest->getProjectPairsTest(0))) && p() && e('0');
r(count($sonarqubeTest->getProjectPairsTest(2))) && p() && e('6');
r($sonarqubeTest->getProjectPairsTest(2)) && p('bendi') && e('本地项目');
r($sonarqubeTest->getProjectPairsTest(2, 'bendi')) && p('bendi') && e('本地项目');
r(count($sonarqubeTest->getProjectPairsTest(2, ''))) && p() && e('6');