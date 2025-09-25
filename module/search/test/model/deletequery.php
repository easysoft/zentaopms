#!/usr/bin/env php
<?php

/**

title=测试 searchModel::deleteQuery();
timeout=0
cid=0

- 步骤1：管理员删除存在的查询ID @true
- 步骤2：管理员删除不存在的查询ID @true
- 步骤3：普通用户删除自己的查询记录 @true
- 步骤4：普通用户尝试删除其他用户的查询记录 @true
- 步骤5：删除无效ID（0） @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zendata('userquery')->loadYaml('userquery_deletequery', false, 2)->gen(10);

su('admin');

$search = new searchTest();

r($search->deleteQueryTest(1)) && p() && e('true');       // 步骤1：管理员删除存在的查询ID
r($search->deleteQueryTest(999)) && p() && e('true');     // 步骤2：管理员删除不存在的查询ID
su('user1');
r($search->deleteQueryTest(4)) && p() && e('true');       // 步骤3：普通用户删除自己的查询记录
r($search->deleteQueryTest(1)) && p() && e('true');       // 步骤4：普通用户尝试删除其他用户的查询记录
r($search->deleteQueryTest(0)) && p() && e('true');       // 步骤5：删除无效ID（0）