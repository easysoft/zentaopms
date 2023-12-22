#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 screenModel->setFilterSql();
timeout=0
cid=1

- 当type为date，default为$MONDAY，返回的时间戳为上周一的时间戳 @1
- 当type为select，typeOption为user，返回的option为用户列表,测试用户列表数据是否正确。
 - 第0条的label属性 @A:admin
 - 第0条的value属性 @admin
 - 第9条的label属性 @U:用户9
 - 第9条的value属性 @user9
 - 第10条的label属性 @Closed
 - 第10条的value属性 @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('user')->gen(10);

$screen = new screenTest();

$filters = array();
$filters['type']    = 'date';
$filters['default'] = '$MONDAY';
$screens = $screen->setIsQueryScreenFiltersTest($filters);
$mondayTime = strtotime('last monday') * 1000;

r($mondayTime === $filters['default']) && p() && e('1');    //当type为date，default为$MONDAY，返回的时间戳为上周一的时间戳

$filters1 = array();
$filters1['type']       = 'select';
$filters1['typeOption'] = 'user';
$screen->setIsQueryScreenFiltersTest($filters1);
r($filters1['options']) && p('0:label,value;9:label,value;10:label,value') && e('A:admin,admin;U:用户9,user9;Closed,closed');    //当type为select，typeOption为user，返回的option为用户列表,测试用户列表数据是否正确。