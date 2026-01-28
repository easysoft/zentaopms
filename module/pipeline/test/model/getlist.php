#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=jobModel->getList();
timeout=0
cid=16844

- 测试获取列表的个数 @5
- 测试获取列表某个job的名称信息第1条的name属性 @这是一个Job1
- 测试获取版本库为2列表的个数 @1
- 测试获取版本库为2的列表某个job的名称信息第2条的name属性 @这是一个Job2
- 测试获取jenkins类型列表的个数 @3
- 测试获取jenkins类型列表某个job的名称信息第5条的name属性 @这是一个Job5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$job  = new jobTest();
$list        = $job->getListTest();
$repoList    = $job->getListTest(0, 2);
$jenkinsList = $job->getListTest(0, 0, 'id_desc', null, 'jenkins');

$types       = array('', 'gitlab', 'test');
$sorts       = array('id_desc', 'name_asc');
$recPerPages = array(5, 10, 20);
$pageIdList  = array(1, 2);

$pipelineTester = new pipelineModelTest();
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
