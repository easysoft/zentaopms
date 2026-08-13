#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

/**

title=测试 repoTao->getmatchedreposbyurl();
timeout=0
cid=0

- 执行repoTest模块的getMatchedReposByUrlIsArrayTest方法，参数是'https://example.com/group/repo.git'  @1
- 执行repoTest模块的getMatchedReposByUrlIsArrayTest方法，参数是'https://example.com/group/repo'  @1
- 执行repoTest模块的getMatchedReposByUrlIsArrayTest方法，参数是'ssh://example.com/group/repo.git'  @1
- 执行repoTest模块的getMatchedReposByUrlIsArrayTest方法，参数是'http://localhost/repo.git'  @1
- 执行repoTest模块的getMatchedReposByUrlIsArrayTest方法，参数是''  @1

*/

$repoTest = new repoTaoTest();
r($repoTest->getMatchedReposByUrlIsArrayTest('https://example.com/group/repo.git')) && p() && e('1');
r($repoTest->getMatchedReposByUrlIsArrayTest('https://example.com/group/repo'))     && p() && e('1');
r($repoTest->getMatchedReposByUrlIsArrayTest('ssh://example.com/group/repo.git')) && p() && e('1');
r($repoTest->getMatchedReposByUrlIsArrayTest('http://localhost/repo.git'))           && p() && e('1');
r($repoTest->getMatchedReposByUrlIsArrayTest(''))                                    && p() && e('1');