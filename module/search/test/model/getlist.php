#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('task')->gen(10);
zdTable('bug')->gen(10);
zdTable('case')->gen(10);
zdTable('todo')->gen(10);
zdTable('build')->gen(10);
zdTable('product')->gen(10);
zdTable('release')->gen(10);
zdTable('testtask')->gen(10);
zdTable('testsuite')->gen(10);
zdTable('testreport')->gen(10);
zdTable('productplan')->gen(10);
zdTable('project')->gen(100);
zdTable('story')->gen(10);

$doc = zdTable('doc');
$doc->version->range('1');
$doc->gen(10);
zdTable('doccontent')->gen(10);

/**

title=测试 searchModel->getList();
timeout=0
cid=1

- 测试在全部类型中搜索带有任务字体的条数 @12
- 测试在任务类型中搜索带有任务字体的条数 @10
- 测试在bug类型中搜索带有bug字体的条数 @10
- 测试在用例类型中搜索带有用例字体的条数 @10
- 测试在文档类型中搜索带有文档字体的条数 @10
- 测试在待办类型中搜索带有待办字体的条数 @10
- 测试在版本类型中搜索带有版本字体的条数 @10
- 测试在用例库类型中搜索带有用例库字体的条数 @0
- 测试在产品类型中搜索带有产品字体的条数 @10
- 测试在发布类型中搜索带有发布字体的条数 @10
- 测试在测试单类型中搜索带有测试单字体的条数 @10
- 测试在测试套件类型中搜索带有测试套件字体的条数 @10
- 测试在测试报告类型中搜索带有测试报告字体的条数 @10
- 测试在计划类型中搜索带有计划字体的条数 @0
- 测试在项目集类型中搜索带有项目集字体的条数 @10
- 测试在项目类型中搜索带有项目字体的条数 @90
- 测试在迭代类型中搜索带有迭代字体的条数 @0
- 测试在需求类型中搜索带有需求字体的条数 @5

*/

$search = new searchTest();

$searchType   = array();
$searchType[] = 'all';
$searchType[] = array('task');
$searchType[] = array('bug');
$searchType[] = array('case');
$searchType[] = array('doc');
$searchType[] = array('todo');
$searchType[] = array('build');
$searchType[] = array('caselib');
$searchType[] = array('product');
$searchType[] = array('release');
$searchType[] = array('testtask');
$searchType[] = array('testsuite');
$searchType[] = array('testreport');
$searchType[] = array('productplan');
$searchType[] = array('program');
$searchType[] = array('project');
$searchType[] = array('execution');
$searchType[] = array('story');

$searchWords = array('任务','bug','用例','文档','待办','版本','用例库','产品','发布','测试单','测试套件','测试报告','计划','项目集','项目','迭代','需求');

r($search->getListTest($searchWords[0], $searchType[0]))   && p() && e('12');  //测试在全部类型中搜索带有任务字体的条数
r($search->getListTest($searchWords[0], $searchType[1]))   && p() && e('10');  //测试在任务类型中搜索带有任务字体的条数
r($search->getListTest($searchWords[1], $searchType[2]))   && p() && e('10');  //测试在bug类型中搜索带有bug字体的条数
r($search->getListTest($searchWords[2], $searchType[3]))   && p() && e('10');  //测试在用例类型中搜索带有用例字体的条数
r($search->getListTest($searchWords[3], $searchType[4]))   && p() && e('10');   //测试在文档类型中搜索带有文档字体的条数
r($search->getListTest($searchWords[4], $searchType[5]))   && p() && e('10');  //测试在待办类型中搜索带有待办字体的条数
r($search->getListTest($searchWords[5], $searchType[6]))   && p() && e('10');  //测试在版本类型中搜索带有版本字体的条数
r($search->getListTest($searchWords[6], $searchType[7]))   && p() && e('0');   //测试在用例库类型中搜索带有用例库字体的条数
r($search->getListTest($searchWords[7], $searchType[8]))   && p() && e('10');  //测试在产品类型中搜索带有产品字体的条数
r($search->getListTest($searchWords[8], $searchType[9]))   && p() && e('10');  //测试在发布类型中搜索带有发布字体的条数
r($search->getListTest($searchWords[9], $searchType[10]))  && p() && e('10');  //测试在测试单类型中搜索带有测试单字体的条数
r($search->getListTest($searchWords[10], $searchType[11])) && p() && e('10');  //测试在测试套件类型中搜索带有测试套件字体的条数
r($search->getListTest($searchWords[11], $searchType[12])) && p() && e('10');  //测试在测试报告类型中搜索带有测试报告字体的条数
r($search->getListTest($searchWords[12], $searchType[13])) && p() && e('0');   //测试在计划类型中搜索带有计划字体的条数
r($search->getListTest($searchWords[13], $searchType[14])) && p() && e('10');  //测试在项目集类型中搜索带有项目集字体的条数
r($search->getListTest($searchWords[14], $searchType[15])) && p() && e('90');  //测试在项目类型中搜索带有项目字体的条数
r($search->getListTest($searchWords[15], $searchType[16])) && p() && e('0');   //测试在迭代类型中搜索带有迭代字体的条数
r($search->getListTest($searchWords[16], $searchType[17])) && p() && e('5');   //测试在需求类型中搜索带有需求字体的条数