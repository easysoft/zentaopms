#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

/**

title=测试 caselibModel->getCasesToExport();
cid=15530

- 测试导出用例库按照id倒序排列的所有用例 @429;427;425;423;421;419;417;415;413;411;409;407;405;403;401
- 测试导出用例库按照id倒序排列的选择的用例 @429;427;417
- 测试导出用例库按照id正序排列的所有用例 @401;403;405;407;409;411;413;415;417;419;421;423;425;427;429
- 测试导出用例库按照id正序排列的选择的用例 @417;427;429
- 测试导出用例库按照id倒序排列的10条用例 @429;427;425;423;421;419;417;415;413;411
- 测试导出用例库模块id为101且按照id倒序排列的所有用例 @429;427;425;423;421;419;417;415;413;411;409;407;405;403;401
- 测试导出用例库模块id为101且按照id倒序排列的选择的用例 @429;427;417
- 测试导出用例库模块id为101且按照id正序排列的所有用例 @401;403;405;407;409;411;413;415;417;419;421;423;425;427;429
- 测试导出用例库模块id为101且按照id正序排列的选择的用例 @417;427;429
- 测试导出用例库模块id为101且按照id倒序排列的10条用例 @429;427;425;423;421;419;417;415;413;411
- 测试导出用例库未评审的且按照id倒序排列的所有用例 @429;425;421;417;413;409;405;401
- 测试导出用例库未评审的且按照id倒序排列的选择的用例 @429;417
- 测试导出用例库未评审的且按照id正序排列的所有用例 @401;405;409;413;417;421;425;429
- 测试导出用例库未评审的且按照id正序排列的选择的用例 @417;429
- 测试导出用例库未评审的且按照id倒序排列的10条用例 @429;425;421;417;413;409;405;401
- 测试导出用例库搜索且按照id倒序排列的所有用例 @429;427;425;423;421;419;417;415;413;411;409;407;405;403;401
- 测试导出用例库搜索且按照id倒序排列的选择的用例 @429;427;417
- 测试导出用例库搜索且按照id正序排列的所有用例 @401;403;405;407;409;411;413;415;417;419;421;423;425;427;429
- 测试导出用例库搜索且按照id正序排列的选择的用例 @417;427;429
- 测试导出用例库搜索且按照id倒序排列的10条用例 @429;427;425;423;421;419;417;415;413;411

*/

zenData('testsuite')->gen(405);
zenData('case')->loadYaml('libcase')->gen(30);
zenData('user')->gen(1);
zenData('module')->gen(20)->fixPath();
su('admin');

$exportTypeList = array('all', 'selected');
$browseTypeList = array('all', 'bymodule', 'wait', 'bysearch');
$sortList       = array('id_desc', 'id_asc');
$checkedItem    = array('', '429,427,417');
$limitList      = array(0, 10);

$caselibTester = new caselibTest();
r($caselibTester->getCasesToExportTest($browseTypeList[0], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[0])) && p() && e('429;427;425;423;421;419;417;415;413;411;409;407;405;403;401'); // 测试导出用例库按照id倒序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[0], $exportTypeList[1], $sortList[0], $checkedItem[1], $limitList[0])) && p() && e('429;427;417');                                                 // 测试导出用例库按照id倒序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[0], $exportTypeList[0], $sortList[1], $checkedItem[0], $limitList[0])) && p() && e('401;403;405;407;409;411;413;415;417;419;421;423;425;427;429'); // 测试导出用例库按照id正序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[0], $exportTypeList[1], $sortList[1], $checkedItem[1], $limitList[0])) && p() && e('417;427;429');                                                 // 测试导出用例库按照id正序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[0], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[1])) && p() && e('429;427;425;423;421;419;417;415;413;411');                     // 测试导出用例库按照id倒序排列的10条用例

r($caselibTester->getCasesToExportTest($browseTypeList[1], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[0])) && p() && e('429;427;425;423;421;419;417;415;413;411;409;407;405;403;401'); // 测试导出用例库模块id为101且按照id倒序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[1], $exportTypeList[1], $sortList[0], $checkedItem[1], $limitList[0])) && p() && e('429;427;417');                                                 // 测试导出用例库模块id为101且按照id倒序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[1], $exportTypeList[0], $sortList[1], $checkedItem[0], $limitList[0])) && p() && e('401;403;405;407;409;411;413;415;417;419;421;423;425;427;429'); // 测试导出用例库模块id为101且按照id正序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[1], $exportTypeList[1], $sortList[1], $checkedItem[1], $limitList[0])) && p() && e('417;427;429');                                                 // 测试导出用例库模块id为101且按照id正序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[1], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[1])) && p() && e('429;427;425;423;421;419;417;415;413;411');                     // 测试导出用例库模块id为101且按照id倒序排列的10条用例

r($caselibTester->getCasesToExportTest($browseTypeList[2], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[0])) && p() && e('429;425;421;417;413;409;405;401'); // 测试导出用例库未评审的且按照id倒序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[2], $exportTypeList[1], $sortList[0], $checkedItem[1], $limitList[0])) && p() && e('429;417');                         // 测试导出用例库未评审的且按照id倒序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[2], $exportTypeList[0], $sortList[1], $checkedItem[0], $limitList[0])) && p() && e('401;405;409;413;417;421;425;429'); // 测试导出用例库未评审的且按照id正序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[2], $exportTypeList[1], $sortList[1], $checkedItem[1], $limitList[0])) && p() && e('417;429');                         // 测试导出用例库未评审的且按照id正序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[2], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[1])) && p() && e('429;425;421;417;413;409;405;401'); // 测试导出用例库未评审的且按照id倒序排列的10条用例

r($caselibTester->getCasesToExportTest($browseTypeList[3], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[0])) && p() && e('429;427;425;423;421;419;417;415;413;411;409;407;405;403;401'); // 测试导出用例库搜索且按照id倒序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[3], $exportTypeList[1], $sortList[0], $checkedItem[1], $limitList[0])) && p() && e('429;427;417');                                                 // 测试导出用例库搜索且按照id倒序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[3], $exportTypeList[0], $sortList[1], $checkedItem[0], $limitList[0])) && p() && e('401;403;405;407;409;411;413;415;417;419;421;423;425;427;429'); // 测试导出用例库搜索且按照id正序排列的所有用例
r($caselibTester->getCasesToExportTest($browseTypeList[3], $exportTypeList[1], $sortList[1], $checkedItem[1], $limitList[0])) && p() && e('417;427;429');                                                 // 测试导出用例库搜索且按照id正序排列的选择的用例
r($caselibTester->getCasesToExportTest($browseTypeList[3], $exportTypeList[0], $sortList[0], $checkedItem[0], $limitList[1])) && p() && e('429;427;425;423;421;419;417;415;413;411');                     // 测试导出用例库搜索且按照id倒序排列的10条用例
