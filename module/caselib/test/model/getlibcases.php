#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';

zdTable('testsuite')->gen(405);
zdTable('case')->config('libcase')->gen(30);
zdTable('user')->gen(1);
zdTable('module')->gen(20)->fixPath();

su('admin');

/**

title=测试 caselibModel->getLibCases();
cid=1
pid=1

*/
$libIdList      = array(201, 402, 1);
$browseTypeList = array('bymodule', 'all', 'wait', 'bysearch', 'othertype');
$moduleIdList   = array(0, 1, 11);
$sortList       = array('id_desc', 'id_asc');

$caselib = new caselibTest();

r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[0], $sortList[0])) && p() && e('429,427,425,423,421,419,417,415,413,411,409,407,405,403,401'); // bymodule 状态下 查询用例库 201 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[1], $sortList[0])) && p() && e('421,401');                                                     // bymodule 状态下 查询用例库 201 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[2], $sortList[0])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 201 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[0], $sortList[1])) && p() && e('401,403,405,407,409,411,413,415,417,419,421,423,425,427,429'); // bymodule 状态下 查询用例库 201 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[1], $sortList[1])) && p() && e('401,421');                                                     // bymodule 状态下 查询用例库 201 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[0], $moduleIdList[2], $sortList[1])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 201 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[0], $sortList[0])) && p() && e('429,427,425,423,421,419,417,415,413,411,409,407,405,403,401'); // all 状态下 查询用例库 201 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[1], $sortList[0])) && p() && e('421,401');                                                     // all 状态下 查询用例库 201 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[2], $sortList[0])) && p() && e('0');                                                           // all 状态下 查询用例库 201 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[0], $sortList[1])) && p() && e('401,403,405,407,409,411,413,415,417,419,421,423,425,427,429'); // all 状态下 查询用例库 201 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[1], $sortList[1])) && p() && e('401,421');                                                     // all 状态下 查询用例库 201 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[1], $moduleIdList[2], $sortList[1])) && p() && e('0');                                                           // all 状态下 查询用例库 201 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[0], $sortList[0])) && p() && e('429,425,421,417,413,409,405,401'); // wait 状态下 查询用例库 201 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[1], $sortList[0])) && p() && e('421,401');                         // wait 状态下 查询用例库 201 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[2], $sortList[0])) && p() && e('0');                               // wait 状态下 查询用例库 201 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[0], $sortList[1])) && p() && e('401,405,409,413,417,421,425,429'); // wait 状态下 查询用例库 201 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[1], $sortList[1])) && p() && e('401,421');                         // wait 状态下 查询用例库 201 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[2], $moduleIdList[2], $sortList[1])) && p() && e('0');                               // wait 状态下 查询用例库 201 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[0], $sortList[0])) && p() && e('429,427,425,423,421,419,417,415,413,411,409,407,405,403,401'); // bysearch 状态下 查询用例库 201 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[1], $sortList[0])) && p() && e('429,427,425,423,421,419,417,415,413,411,409,407,405,403,401'); // bysearch 状态下 查询用例库 201 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[2], $sortList[0])) && p() && e('429,427,425,423,421,419,417,415,413,411,409,407,405,403,401'); // bysearch 状态下 查询用例库 201 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[0], $sortList[1])) && p() && e('401,403,405,407,409,411,413,415,417,419,421,423,425,427,429'); // bysearch 状态下 查询用例库 201 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[1], $sortList[1])) && p() && e('401,403,405,407,409,411,413,415,417,419,421,423,425,427,429'); // bysearch 状态下 查询用例库 201 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[3], $moduleIdList[2], $sortList[1])) && p() && e('401,403,405,407,409,411,413,415,417,419,421,423,425,427,429'); // bysearch 状态下 查询用例库 201 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[0], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[1], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[2], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[0], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[1], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[0], $browseTypeList[4], $moduleIdList[2], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 201 模块 11 排序 id_asc 的用例 id


r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[0], $sortList[0])) && p() && e('430,428,426,424,422,420,418,416,414,412,410,408,406,404,402'); // bymodule 状态下 查询用例库 402 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[1], $sortList[0])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 402 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[2], $sortList[0])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 402 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[0], $sortList[1])) && p() && e('402,404,406,408,410,412,414,416,418,420,422,424,426,428,430'); // bymodule 状态下 查询用例库 402 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[1], $sortList[1])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 402 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[0], $moduleIdList[2], $sortList[1])) && p() && e('0');                                                           // bymodule 状态下 查询用例库 402 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[0], $sortList[0])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[1], $sortList[0])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[2], $sortList[0])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[0], $sortList[1])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[1], $sortList[1])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[2], $moduleIdList[2], $sortList[1])) && p() && e('0'); // wait 状态下 查询用例库 402 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[0], $sortList[0])) && p() && e('430,428,426,424,422,420,418,416,414,412,410,408,406,404,402'); // bysearch 状态下 查询用例库 402 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[1], $sortList[0])) && p() && e('430,428,426,424,422,420,418,416,414,412,410,408,406,404,402'); // bysearch 状态下 查询用例库 402 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[2], $sortList[0])) && p() && e('430,428,426,424,422,420,418,416,414,412,410,408,406,404,402'); // bysearch 状态下 查询用例库 402 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[0], $sortList[1])) && p() && e('402,404,406,408,410,412,414,416,418,420,422,424,426,428,430'); // bysearch 状态下 查询用例库 402 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[1], $sortList[1])) && p() && e('402,404,406,408,410,412,414,416,418,420,422,424,426,428,430'); // bysearch 状态下 查询用例库 402 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[3], $moduleIdList[2], $sortList[1])) && p() && e('402,404,406,408,410,412,414,416,418,420,422,424,426,428,430'); // bysearch 状态下 查询用例库 402 模块 11 排序 id_asc 的用例 id

r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[0], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 0 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[1], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 1 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[2], $sortList[0])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 11 排序 id_desc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[0], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 0 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[1], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 1 排序 id_asc 的用例 id
r($caselib->getLibCasesTest($libIdList[1], $browseTypeList[4], $moduleIdList[2], $sortList[1])) && p() && e('0'); // othertype 状态下 查询用例库 402 模块 11 排序 id_asc 的用例 id
