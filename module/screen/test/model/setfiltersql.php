#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('dept')->gen(1);
zenData('user')->gen(1);

/**

title=测试 screenModel->setFilterSql();
timeout=0
cid=1

- 当模型中没有chart数据的时候，生成的sql为图表本身的sql。 @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'

- 当模型中有chart数据的时候，并且类型不为account，生成的sql为图表本身的sql+筛选条件。 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin IN ('admin')

- 当模型中有chart数据的时候，并且类型为account，生成的sql为图表本身的sql+筛选条件。 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'

- 当模型中有chart数据的时候，并且类型为month，生成的sql为图表本身的sql+筛选条件。 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'

- 当模型中有chart数据的时候，并且类型为dept，生成的sql为图表本身的sql+筛选条件。 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'

*/

$screen = new screenTest();

global $tester;
$chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq(1018)->fetch();

r($screen->setFilterSqlTest($chart, ''))              && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");                                                                  //当模型中没有chart数据的时候，生成的sql为图表本身的sql。
r($screen->setFilterSqlTest($chart, 'year', true))    && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin IN ('admin')"); //当模型中有chart数据的时候，并且类型不为account，生成的sql为图表本身的sql+筛选条件。
r($screen->setFilterSqlTest($chart, 'account', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'");    //当模型中有chart数据的时候，并且类型为account，生成的sql为图表本身的sql+筛选条件。
r($screen->setFilterSqlTest($chart, 'month', true))   && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'");    //当模型中有chart数据的时候，并且类型为month，生成的sql为图表本身的sql+筛选条件。
r($screen->setFilterSqlTest($chart, 'dept', true))    && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023' AND admin = 'admin'");    //当模型中有chart数据的时候，并且类型为dept，生成的sql为图表本身的sql+筛选条件。