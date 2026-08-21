#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListBySCM();
timeout=0
cid=18072

- 执行repoTest模块的getListBySCMTest方法，参数是'Git'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Gitlab'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Git, Gitlab'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'NotExist'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Subversion'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Git, Gitlab, Subversion, Gogs', 'haspriv'  @empty
- 执行repoTest模块的getListBySCMTest方法，参数是'Git'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getListBySCMTest('Git')) && p() && e('empty');
r($repoTest->getListBySCMTest('Gitlab')) && p() && e('empty');
r($repoTest->getListBySCMTest('Git,Gitlab')) && p() && e('empty');
r($repoTest->getListBySCMTest('NotExist')) && p() && e('empty');
r($repoTest->getListBySCMTest('Subversion')) && p() && e('empty');
r($repoTest->getListBySCMTest('Git,Gitlab,Subversion,Gogs', 'haspriv')) && p() && e('empty');
r($repoTest->getListBySCMIsArrayTest('Git')) && p() && e('0');
