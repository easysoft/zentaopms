#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testsuite')->gen(5);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel::getPairs();
timeout=0
cid=0

- 步骤1：获取全部用例库键值对 @array
- 步骤2：获取review类型用例库键值对 @array  
- 步骤3：按名称排序获取用例库键值对 @array
- 步骤4：使用无效类型参数获取键值对 @array
- 步骤5：分页获取用例库键值对 @array

*/

$caselibTest = new caselibTest();

r($caselibTest->getPairsTest('all', 'id_desc')) && p() && e('array'); // 步骤1：获取全部用例库键值对
r($caselibTest->getPairsTest('review', 'id_desc')) && p() && e('array'); // 步骤2：获取review类型用例库键值对
r($caselibTest->getPairsTest('all', 'name_asc')) && p() && e('array'); // 步骤3：按名称排序获取用例库键值对
r($caselibTest->getPairsTest('invalid', 'id_desc')) && p() && e('array'); // 步骤4：使用无效类型参数获取键值对
r($caselibTest->getPairsTest('all', 'id_desc', (object)array('recPerPage' => 2, 'pageID' => 1))) && p() && e('array'); // 步骤5：分页获取用例库键值对