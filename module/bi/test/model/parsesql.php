#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->parseSql();
timeout=0
cid=1

- 测试第1条sql
 - 属性id @zt_product=>id
 - 属性name @zt_product=>name
 - 属性bugID @zt_bug=>id
 - 属性type @zt_bug=>type
 - 属性fixedBugs @zt_product=>fixedBugs
 - 属性caseTitle @zt_case=>title
- 测试第2条sql
 - 属性id @zt_task=>id
 - 属性name @zt_task=>name
 - 属性consumed @zt_task=>consumed

*/

$sqls = array();

$sqls[0] = <<<EOT
select t1.id,t1.name,t2.id as bugID,t2.type, fixedBugs, caseTitle
from zt_product as t1
left join zt_bug as t2 on t1.id=t2.product and t2.deleted = '0'
left join zt_project as t3 on t1.program=t3.id
left join (select id, title as caseTitle from zt_case where deleted = '0') as t4 on t2.case = t4.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t1.line desc, t1.`order` asc
EOT;

$sqls[1] = <<<EOT
select id,name,consumed from zt_task
EOT;

$bi=new biTest();
r($bi->parseSqlTest($sqls[0])) && p('id,name,bugID,type,fixedBugs,caseTitle') && e('zt_product=>id,zt_product=>name,zt_bug=>id,zt_bug=>type,zt_product=>fixedBugs,zt_case=>title'); // 测试第1条sql
r($bi->parseSqlTest($sqls[1])) && p('id,name,consumed')                       && e('zt_task=>id,zt_task=>name,zt_task=>consumed');                                                  // 测试第2条sql