#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getList();
cid=1

- 获取类型为空按照id倒序排序，每页5条，第1页的流水线列表
 - 第16条的type属性 @gitlab
 - 第16条的name属性 @gitLab
 - 第16条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第16条的account属性 @root
- 获取类型为空按照name正序排序，每页5条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为空按照id倒序排序，每页10条，第1页的流水线列表
 - 第16条的type属性 @gitlab
 - 第16条的name属性 @gitLab
 - 第16条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第16条的account属性 @root
- 获取类型为空按照id倒序排序，每页5条，第2页的流水线列表
 - 第11条的type属性 @gitlab
 - 第11条的name属性 @gitLab
 - 第11条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第11条的account属性 @root
- 获取类型为空按照id倒序排序，每页20条，第1页的流水线列表
 - 第16条的type属性 @gitlab
 - 第16条的name属性 @gitLab
 - 第16条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第16条的account属性 @root
- 获取类型为空按照id倒序排序，每页20条，第2页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为空按照name正序排序，每页10条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为空按照name正序排序，每页10条，第2页的流水线列表
 - 第17条的type属性 @sonarqube
 - 第17条的name属性 @SonarQube
 - 第17条的url属性 @https://sonardev.qc.oop.cc/
 - 第17条的account属性 @sonar
- 获取类型为gitlab按照id倒序排序，每页5条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照name正序排序，每页5条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照id倒序排序，每页10条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照id倒序排序，每页5条，第2页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照id倒序排序，每页20条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照id倒序排序，每页20条，第2页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照name正序排序，每页10条，第1页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为gitlab按照name正序排序，每页10条，第2页的流水线列表
 - 第1条的type属性 @gitlab
 - 第1条的name属性 @gitLab
 - 第1条的url属性 @https://gitlabdev.qc.oop.cc/
 - 第1条的account属性 @root
- 获取类型为test按照id倒序排序，每页5条，第1页的流水线列表 @0
- 获取类型为test按照name正序排序，每页5条，第1页的流水线列表 @0
- 获取类型为test按照id倒序排序，每页10条，第1页的流水线列表 @0
- 获取类型为test按照id倒序排序，每页5条，第2页的流水线列表 @0
- 获取类型为test按照id倒序排序，每页20条，第1页的流水线列表 @0
- 获取类型为test按照id倒序排序，每页20条，第2页的流水线列表 @0
- 获取类型为test按照name正序排序，每页10条，第1页的流水线列表 @0
- 获取类型为test按照name正序排序，每页10条，第2页的流水线列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(20);

$types       = array('', 'gitlab', 'test');
$sorts       = array('id_desc', 'name_asc');
$recPerPages = array(5, 10, 20);
$pageIdList  = array(1, 2);

$pipelineTester = new pipelineTest();
r($pipelineTester->getListTest($types[0], $sorts[0], $recPerPages[0], $pageIdList[0])) && p('16:type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照id倒序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[1], $recPerPages[0], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照name正序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[0], $recPerPages[1], $pageIdList[0])) && p('16:type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照id倒序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[0], $recPerPages[0], $pageIdList[1])) && p('11:type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照id倒序排序，每页5条，第2页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[0], $recPerPages[2], $pageIdList[0])) && p('16:type,name,url,account') && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照id倒序排序，每页20条，第1页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[0], $recPerPages[2], $pageIdList[1])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照id倒序排序，每页20条，第2页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[1], $recPerPages[1], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为空按照name正序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[0], $sorts[1], $recPerPages[1], $pageIdList[1])) && p('17:type,name,url,account') && e('sonarqube,SonarQube,https://sonardev.qc.oop.cc/,sonar'); // 获取类型为空按照name正序排序，每页10条，第2页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[0], $recPerPages[0], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照id倒序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[1], $recPerPages[0], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照name正序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[0], $recPerPages[1], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照id倒序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[0], $recPerPages[0], $pageIdList[1])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照id倒序排序，每页5条，第2页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[0], $recPerPages[2], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照id倒序排序，每页20条，第1页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[0], $recPerPages[2], $pageIdList[1])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照id倒序排序，每页20条，第2页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[1], $recPerPages[1], $pageIdList[0])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照name正序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[1], $sorts[1], $recPerPages[1], $pageIdList[1])) && p('1:type,name,url,account')  && e('gitlab,gitLab,https://gitlabdev.qc.oop.cc/,root');       // 获取类型为gitlab按照name正序排序，每页10条，第2页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[0], $recPerPages[0], $pageIdList[0])) && p()                           && e('0');                                                     // 获取类型为test按照id倒序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[1], $recPerPages[0], $pageIdList[0])) && p()                           && e('0');                                                     // 获取类型为test按照name正序排序，每页5条，第1页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[0], $recPerPages[1], $pageIdList[0])) && p()                           && e('0');                                                     // 获取类型为test按照id倒序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[0], $recPerPages[0], $pageIdList[1])) && p()                           && e('0');                                                     // 获取类型为test按照id倒序排序，每页5条，第2页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[0], $recPerPages[2], $pageIdList[0])) && p()                           && e('0');                                                     // 获取类型为test按照id倒序排序，每页20条，第1页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[0], $recPerPages[2], $pageIdList[1])) && p()                           && e('0');                                                     // 获取类型为test按照id倒序排序，每页20条，第2页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[1], $recPerPages[1], $pageIdList[0])) && p()                           && e('0');                                                     // 获取类型为test按照name正序排序，每页10条，第1页的流水线列表
r($pipelineTester->getListTest($types[2], $sorts[1], $recPerPages[1], $pageIdList[1])) && p()                           && e('0');                                                     // 获取类型为test按照name正序排序，每页10条，第2页的流水线列表
