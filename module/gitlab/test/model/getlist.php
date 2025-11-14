#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getList();
timeout=0
cid=16652

- 步骤1：正常获取gitlab列表，按id降序排序第5条的id属性 @5
- 步骤2：正常获取gitlab列表，按id升序排序第1条的id属性 @1
- 步骤3：验证返回的gitlab条目类型为gitlab第5条的type属性 @gitlab
- 步骤4：测试空orderBy参数的默认行为第5条的type属性 @gitlab
- 步骤5：测试列表数量统计功能 @5
- 步骤6：验证返回数据的完整性第5条的name属性 @GitLab5
- 步骤7：测试按name升序排列第1条的name属性 @GitLab1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gitlab{5},jenkins{5}');
$table->name->range('GitLab1,GitLab2,GitLab3,GitLab4,GitLab5,Jenkins1,Jenkins2,Jenkins3,Jenkins4,Jenkins5');
$table->url->range('http://gitlab1.com,http://gitlab2.com,http://gitlab3.com,http://gitlab4.com,http://gitlab5.com,http://jenkins1.com,http://jenkins2.com,http://jenkins3.com,http://jenkins4.com,http://jenkins5.com');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->getListTest('id_desc')) && p('5:id') && e('5');                          // 步骤1：正常获取gitlab列表，按id降序排序
r($gitlabTest->getListTest('id_asc')) && p('1:id') && e('1');                           // 步骤2：正常获取gitlab列表，按id升序排序
r($gitlabTest->getListTest('id_desc')) && p('5:type') && e('gitlab');                   // 步骤3：验证返回的gitlab条目类型为gitlab
r($gitlabTest->getListTest('')) && p('5:type') && e('gitlab');                          // 步骤4：测试空orderBy参数的默认行为
r(count($gitlabTest->getListTest('id_desc'))) && p() && e('5');                         // 步骤5：测试列表数量统计功能
r($gitlabTest->getListTest('id_desc')) && p('5:name') && e('GitLab5');                  // 步骤6：验证返回数据的完整性
r($gitlabTest->getListTest('name_asc')) && p('1:name') && e('GitLab1');                 // 步骤7：测试按name升序排列