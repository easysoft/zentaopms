#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getLinkBuild();
cid=1

- 获取制品库ID为0的版本信息 @0
- 获取制品库ID为1的版本信息
 - 属性project @11
 - 属性product @1
 - 属性branch @0
 - 属性execution @0
 - 属性builds @~~
 - 属性name @项目11版本1
 - 属性scmPath @gitlab.zcorp.cc/easycorp/zentaopms.git
 - 属性filePath @www.csdn.net/
 - 属性stories @2,4
 - 属性bugs @1,2
 - 属性artifactRepoID @1
 - 属性builder @test1
 - 属性desc @<div> <p>司法局阿里水电费加快了时代峰峻辣三丁防 显卡鲁大师，，，asdf，，</p> <p>qoqao穷OA怄气袄怄气欧文饿哦啊OAof噢诶区文诗  熊熊0哦到OA山东全文怄气袄安全o</p> <p>zmvzxcmv啊，。你啊是否为欧舒斯蒂芬你先吃，哪吒，门下车，哦企鹅跑跑水电费，充满着那些，聪明在，需自行车，起伏阿萨德从，名字</p></div>
- 获取制品库ID为2的版本信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
$buildTable = zdTable('build');
$buildTable->artifactRepoID->range('1');
$buildTable->gen(1);

$idList = array(0, 1, 2);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->getLinkBuildTest($idList[0])) && p()                                                                                                              && e('0');                                                                                                                                                                                                                                                                                                                                                                                                    // 获取制品库ID为0的版本信息
r($artifactrepoTester->getLinkBuildTest($idList[1])) && p('project;product;branch;execution;builds;name;scmPath;filePath;stories;bugs;artifactRepoID;builder;desc', ';') && e('11;1;0;0;~~;项目11版本1;gitlab.zcorp.cc/easycorp/zentaopms.git;www.csdn.net/;2,4;1,2;1;test1;<div> <p>司法局阿里水电费加快了时代峰峻辣三丁防 显卡鲁大师，，，asdf，，</p> <p>qoqao穷OA怄气袄怄气欧文饿哦啊OAof噢诶区文诗  熊熊0哦到OA山东全文怄气袄安全o</p> <p>zmvzxcmv啊，。你啊是否为欧舒斯蒂芬你先吃，哪吒，门下车，哦企鹅跑跑水电费，充满着那些，聪明在，需自行车，起伏阿萨德从，名字</p></div>'); // 获取制品库ID为1的版本信息
r($artifactrepoTester->getLinkBuildTest($idList[2])) && p()                                                                                                              && e('0');                                                                                                                                                                                                                                                                                                                                                                                                    // 获取制品库ID为2的版本信息
