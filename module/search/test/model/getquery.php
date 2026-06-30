#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('userquery')->gen(10);

/**

title=测试 searchModel->getQuery();
timeout=0
cid=18303

- 查询ID为1的搜索条件名称及查询数量
 - 属性title @这是搜索条件名称1
 - 第1条查询条件的field,operator,value属性 @name,include,aa
- 查询ID为2的搜索条件名称及首条查询条件
 - 属性title @这是搜索条件名称2
 - 第1条查询条件的field,operator,value属性 @name,include,aa
- 查询ID为3的搜索条件名称及首条查询条件
 - 属性title @这是搜索条件名称3
 - 第1条查询条件的field,operator,value属性 @name,include,aa
- 查询ID为4的搜索条件名称及首条查询条件
 - 属性title @这是搜索条件名称4
 - 第1条查询条件的field,operator,value属性 @name,include,aa
- 查询ID为5的搜索条件名称及首条查询条件
 - 属性title @这是搜索条件名称5
 - 第1条查询条件的field,operator,value属性 @name,include,aa
- 查询ID为6的搜索条件名称及首条查询条件
 - 属性title @这是搜索条件名称6
 - 第1条查询条件的field,operator,value属性 @name,include,aa

*/

$search = new searchModelTest();

$queryIDList = array('1', '2', '3', '4', '5', '6');

$query1 = $search->getQueryTest((int)$queryIDList[0]);
$query2 = $search->getQueryTest((int)$queryIDList[1]);
$query3 = $search->getQueryTest((int)$queryIDList[2]);
$query4 = $search->getQueryTest((int)$queryIDList[3]);
$query5 = $search->getQueryTest((int)$queryIDList[4]);
$query6 = $search->getQueryTest((int)$queryIDList[5]);

r($query1)          && p('title')                && e('这是搜索条件名称1'); // 查询ID为1的搜索条件名称
r($query1->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为1的首条查询条件
r($query2)          && p('title')                && e('这是搜索条件名称2'); // 查询ID为2的搜索条件名称
r($query2->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为2的首条查询条件
r($query3)          && p('title')                && e('这是搜索条件名称3'); // 查询ID为3的搜索条件名称
r($query3->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为3的首条查询条件
r($query4)          && p('title')                && e('这是搜索条件名称4'); // 查询ID为4的搜索条件名称
r($query4->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为4的首条查询条件
r($query5)          && p('title')                && e('这是搜索条件名称5'); // 查询ID为5的搜索条件名称
r($query5->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为5的首条查询条件
r($query6)          && p('title')                && e('这是搜索条件名称6'); // 查询ID为6的搜索条件名称
r($query6->form[0]) && p('field,operator,value') && e('name,include,aa'); // 查询ID为6的首条查询条件
