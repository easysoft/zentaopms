#!/usr/bin/env php
<?php

/**

title=测试 gitModel::setRepoRoot();
timeout=0
cid=16555

- 执行gitTest模块的setRepoRootTest方法，参数是$normalRepo  @/home/git/normal
- 执行gitTest模块的setRepoRootTest方法，参数是$specialRepo  @/path/with-special_chars
- 执行gitTest模块的setRepoRootTest方法，参数是$chineseRepo  @/路径/包含中文字符
- 执行gitTest模块的setRepoRootTest方法，参数是$emptyRepo  @0
- 执行gitTest模块的setRepoRootTest方法，参数是$longRepo  @/very/long/path/that/contains/many/levels/of/directories/and/subdirectories/to/test/long/path/handling
- 执行gitTest模块的setRepoRootTest方法，参数是$spaceRepo  @/path with spaces

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

zenData('repo')->loadYaml('repo_setreporoot', false, 2)->gen(6);
su('admin');

$gitTest = new gitTest();

$normalRepo = new stdclass();
$normalRepo->path = '/home/git/normal';

$specialRepo = new stdclass();
$specialRepo->path = '/path/with-special_chars';

$chineseRepo = new stdclass();
$chineseRepo->path = '/路径/包含中文字符';

$emptyRepo = new stdclass();
$emptyRepo->path = '';

$longRepo = new stdclass();
$longRepo->path = '/very/long/path/that/contains/many/levels/of/directories/and/subdirectories/to/test/long/path/handling';

$spaceRepo = new stdclass();
$spaceRepo->path = '/path with spaces';

r($gitTest->setRepoRootTest($normalRepo)) && p() && e('/home/git/normal');
r($gitTest->setRepoRootTest($specialRepo)) && p() && e('/path/with-special_chars');
r($gitTest->setRepoRootTest($chineseRepo)) && p() && e('/路径/包含中文字符');
r($gitTest->setRepoRootTest($emptyRepo)) && p() && e('0');
r($gitTest->setRepoRootTest($longRepo)) && p() && e('/very/long/path/that/contains/many/levels/of/directories/and/subdirectories/to/test/long/path/handling');
r($gitTest->setRepoRootTest($spaceRepo)) && p() && e('/path with spaces');