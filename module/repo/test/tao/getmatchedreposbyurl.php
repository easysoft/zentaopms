#!/usr/bin/env php
<?php

/**

title=测试 repoTao::getMatchedReposByUrl();
timeout=0
cid=18119

- 执行repoTest模块的getMatchedReposByUrlTest方法，参数是'http://github.com/example/test.git'  @0
- 执行repoTest模块的getMatchedReposByUrlTest方法，参数是'https://bitbucket.org/example/test.git'  @0
- 执行repoTest模块的getMatchedReposByUrlTest方法，参数是'http://192.168.1.161:51080/gitlab-instance-f9325ed1/azalea723test.git'  @0
- 执行repoTest模块的getMatchedReposByUrlTest方法，参数是'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git' 第0条的gitlab属性 @1
- 执行repoTest模块的getMatchedReposByUrlTest方法，参数是'https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git' 第0条的project属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(4);

su('admin');

$repoTest = new repoTaoTest();

r($repoTest->getMatchedReposByUrlTest('http://github.com/example/test.git')) && p() && e('0');
r($repoTest->getMatchedReposByUrlTest('https://bitbucket.org/example/test.git')) && p() && e('0');
r($repoTest->getMatchedReposByUrlTest('http://192.168.1.161:51080/gitlab-instance-f9325ed1/azalea723test.git')) && p() && e('0');
r($repoTest->getMatchedReposByUrlTest('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git')) && p('0:gitlab') && e('1');
r($repoTest->getMatchedReposByUrlTest('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git')) && p('0:project') && e('2');