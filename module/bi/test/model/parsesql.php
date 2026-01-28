#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=biModel->parseSql();
timeout=0
cid=15192

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
- 测试第3条sql
 - 属性id @zt_story=>id
 - 属性estimate @zt_story=>estimate
- 测试第4条sql
 - 属性id @zt_story=>id
 - 属性hour @zt_story=>estimate
- 测试第5条sql
 - 属性name @zt_project=>name
 - 属性PM @zt_project=>PM
 - 属性begin @zt_project=>begin
 - 属性realBegan @zt_project=>realBegan
 - 属性realname @zt_user=>realname
- 测试第5条sql
 - 属性execution @zt_task=>execution
 - 属性id @zt_task=>id
 - 属性name @zt_task=>name
 - 属性type @zt_task=>type
- 测试第5条sql
 - 属性project @zt_project=>id
 - 属性execution @zt_project=>id
 - 属性task @zt_task=>id
 - 属性consumed @zt_task=>consumed
 - 属性type @zt_task=>type

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

$sqls[2] = <<<EOT
SELECT DISTINCT id,estimate
FROM zt_story t1
WHERE 1 = (
    SELECT COUNT(DISTINCT id,estimate)
    FROM zt_story t2
    WHERE t2.estimate> t1.estimate
)
EOT;

$sqls[3] = <<<EOT
SELECT DISTINCT id,estimate hour
FROM zt_story t1
WHERE 1 = (
    SELECT COUNT(DISTINCT id,estimate)
    FROM zt_story t2
    WHERE t2.estimate> t1.estimate
)
EOT;

$sqls[4] = <<<EOT
SELECT
    t1.name,
    t1.PM,
    t1.begin,
    t1.realBegan,
    t3.realname
FROM
    zt_project AS t1
LEFT JOIN
    zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution'
LEFT JOIN
    zt_user AS t3 ON t2.account = t3.account
WHERE t1.name LIKE '%zentaopms%'
    AND t1.realBegan > '2022-01-01'
order by t1.id
EOT;

$sqls[5] = <<<EOT
select execution, id, name, type from zt_task
EOT;

$sqls[6] = <<<EOT
select t3.id as project, t2.id as execution, t1.id as task, t1.type, t1.consumed
from zt_task t1
left join zt_project t2 on t1.execution = t2.id
left join zt_project t3 on t3.id = t2.project
where t1.deleted = '0' and t2.deleted = '0' and t3.deleted = '0'
and t2.type in ('sprint', 'stage', 'kanban')
EOT;

$bi = new biModelTest();
r($bi->parseSqlTest($sqls[0])) && p('id,name,bugID,type,fixedBugs,caseTitle') && e('zt_product=>id,zt_product=>name,zt_bug=>id,zt_bug=>type,zt_product=>fixedBugs,zt_case=>title'); // 测试第1条sql
r($bi->parseSqlTest($sqls[1])) && p('id,name,consumed')                       && e('zt_task=>id,zt_task=>name,zt_task=>consumed');                                                  // 测试第2条sql
r($bi->parseSqlTest($sqls[2])) && p('id,estimate')                            && e('zt_story=>id,zt_story=>estimate');                                                              // 测试第3条sql
r($bi->parseSqlTest($sqls[3])) && p('id,hour')                                && e('zt_story=>id,zt_story=>estimate');                                                              // 测试第4条sql
r($bi->parseSqlTest($sqls[4])) && p('name,PM,begin,realBegan,realname')       && e('zt_project=>name,zt_project=>PM,zt_project=>begin,zt_project=>realBegan,zt_user=>realname');    // 测试第5条sql
r($bi->parseSqlTest($sqls[5])) && p('execution,id,name,type')                 && e('zt_task=>execution,zt_task=>id,zt_task=>name,zt_task=>type');    // 测试第5条sql
r($bi->parseSqlTest($sqls[6])) && p('project,execution,task,consumed,type')     && e('zt_project=>id,zt_project=>id,zt_task=>id,zt_task=>consumed,zt_task=>type');    // 测试第5条sql