#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getList();
cid=1

- 按照ID倒序，每页5条，取第1页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID倒序，每页10条，取第1页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID倒序，每页20条，取第1页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID倒序，每页5条，取第2页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID倒序，每页10条，取第2页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID倒序，每页20条，取第2页的数据
 - 第20条的name属性 @制品库20
 - 第20条的products属性 @5
 - 第20条的serverID属性 @2
 - 第20条的repoName属性 @代码库20
 - 第20条的pipelineID属性 @2
 - 第20条的url属性 @https://gitlabdev.qc.oop.cc//repository/代码库20/
- 按照ID正序，每页5条，取第1页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/
- 按照ID正序，每页10条，取第1页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/
- 按照ID正序，每页20条，取第1页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/
- 按照ID正序，每页5条，取第2页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/
- 按照ID正序，每页10条，取第2页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/
- 按照ID正序，每页20条，取第2页的数据
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @1
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(6);
zdTable('artifactrepo')->config('artifactrepo')->gen(20);

$sorts       = array('id_desc', 'name_asc');
$recPerPages = array(5, 10, 20);
$pageIds     = array(1, 2);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[0], $pageIds[0])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页5条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[1], $pageIds[0])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页10条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[2], $pageIds[0])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页20条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[0], $pageIds[1])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页5条，取第2页的数据
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[1], $pageIds[1])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页10条，取第2页的数据
r($artifactrepoTester->getListTest($sorts[0], $recPerPages[2], $pageIds[1])) && p('20:name,products,serverID,repoName,pipelineID,url') && e('制品库20,5,2,代码库20,2,https://gitlabdev.qc.oop.cc//repository/代码库20/'); // 按照ID倒序，每页20条，取第2页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[0], $pageIds[0])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页5条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[1], $pageIds[0])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页10条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[2], $pageIds[0])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页20条，取第1页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[0], $pageIds[1])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页5条，取第2页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[1], $pageIds[1])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页10条，取第2页的数据
r($artifactrepoTester->getListTest($sorts[1], $recPerPages[2], $pageIds[1])) && p('1:name,products,serverID,repoName,pipelineID,url')  && e('制品库1,1,1,代码库1,1,https://nexus3dev.qc.oop.cc//repository/代码库1/');    // 按照ID正序，每页20条，取第2页的数据
