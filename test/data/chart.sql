TRUNCATE TABLE zt_chart;
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1001, '年度总结-登录次数', 1, 'card', '0', '', '', '{"value": {"type": "agg", "field": "login", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT sum(t2.login) AS login, `year`, account, realname
FROM zt_user t1 
LEFT JOIN (SELECT count(1) as login, actor, YEAR(`date`) as \'year\' FROM zt_action GROUP BY actor, `year`) t2 on t1.account = t2.actor 
WHERE t1.deleted = \'0\'
GROUP BY `year`, account, realname', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1002, '年度总结-操作次数', 1, 'card', '0', '', '', '{"value": {"type": "agg", "field": "allAction", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT sum(t2.allAction) as allAction, `year` , account, realname
FROM zt_user t1
LEFT JOIN (SELECT count(1) as allAction, actor, YEAR(`date`) as \'year\' FROM zt_action GROUP BY actor, `year`) t2 on t1.account = t2.actor 
WHERE t1.deleted = \'0\'
GROUP BY `year`, account, realname', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1003, '年度总结-消耗工时', 1, 'card', '0', '', '', '{"value": {"type": "agg", "field": "consumed", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT ROUND(SUM(t2.consumed)) AS consumed, `year` , t1.account, realname
FROM zt_user t1
LEFT JOIN (SELECT sum(consumed) as consumed, account, YEAR(`date`) as \'year\' FROM zt_effort WHERE deleted = \'0\' GROUP BY account, `year` ) t2 on t1.account = t2.account 
WHERE t1.deleted = \'0\'
GROUP BY `year`, t1.account, realname', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1004, '年度总结-待办数', 1, 'card', '0', '', '', '{"value": {"type": "agg", "field": "todo", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT sum(t2.todo) AS todo,sum(t2.undone) AS undone,sum(t2.done) AS done,t2.`year`, t1.account, realname, dept
FROM zt_user t1
LEFT JOIN (SELECT count(1) AS \'todo\', sum(if((`status` != \'done\'), 1, 0)) AS `undone`, sum(if((`status` = \'done\'), 1, 0)) AS `done`, account, YEAR(`date`) AS \'year\' FROM zt_todo WHERE deleted = \'0\' GROUP BY account, `year`) t2 on t1.account = t2.account 
WHERE t1.deleted = \'0\'
GROUP BY t2.`year`, t1.account, realname, dept', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1005, '年度总结-贡献数', 1, 'card', '0', '', '', '{"value": {"type": "agg", "field": "num", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'select tt.year,tt.actor as account, count(1) as num from (
SELECT YEAR(t1.date) as `year`, t1.actor, t1.objectType, t1.action
from zt_action t1
WHERE 
(
(t1.objectType = \'bug\' AND t1.action in(\'resolved\',\'opened\',\'closed\',\'activated\') and (select deleted from zt_bug where id = t1.objectID) = \'0\')
OR (t1.objectType = \'task\' AND t1.action in(\'finished\',\'opened\',\'closed\',\'activated\',\'assigned\') and (select deleted from zt_task where id = t1.objectID) = \'0\')
OR (t1.objectType = \'story\' AND t1.action in(\'opened\',\'reviewed\',\'closed\') and (select deleted from zt_story where id = t1.objectID) = \'0\')
OR (t1.objectType = \'execution\' AND t1.action in(\'opened\',\'edited\',\'started\',\'closed\') and (select deleted from zt_project where id = t1.objectID) = \'0\')
OR (t1.objectType = \'product\' AND t1.action in(\'opened\',\'edited\',\'closed\') and (select deleted from zt_product where id = t1.objectID) = \'0\')
OR (t1.objectType = \'case\' AND t1.action in(\'opened\',\'run\') and (select deleted from zt_case where id = t1.objectID) = \'0\')
OR (t1.objectType = \'testtask\' AND t1.action in(\'opened\',\'edited\') and (select deleted from zt_testtask where id = t1.objectID) = \'0\')
OR (t1.objectType = \'productplan\' AND t1.action in(\'opened\') and (select deleted from zt_productplan where id = t1.objectID) = \'0\')
OR (t1.objectType = \'release\' AND t1.action in(\'opened\') and (select deleted from zt_release where id = t1.objectID) = \'0\')
OR (t1.objectType = \'doc\' AND t1.action in(\'created\',\'edited\') and (select deleted from zt_doc where id = t1.objectID) = \'0\')
OR (t1.objectType = \'build\' AND t1.action in(\'opened\') and (select deleted from zt_build where id = t1.objectID) = \'0\')
)
union all
SELECT YEAR(t1.date) as `year`, t1.actor, \'code\' as objectType, t1.action from zt_action t1
where t1.action in (\'gitcommited\', \'svncommited\') and t1.objectType = \'task\'
) tt group by actor', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1006, '年度总结-贡献数据', 1, 'bar', '0', '', '', '{
"xaxis":[{"field":"objectType","name":"对象类型"}],
"yaxis":[{"type":"agg","field":"create","agg":"sum","name":"创建","valOrAgg":"sum"},{"type":"value","field":"edit","agg":"sum","name":"编辑","valOrAgg":"sum"}]
}', '[]', 0, '', null, 'SELECT t2.year, t1.dept, t2.account, t2.objectType, t2.create, t2.edit
FROM zt_user AS t1
LEFT JOIN
(SELECT 
  YEAR(t1.date) AS `year`, t1.actor AS account, "产品" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`, 
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_product AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'product\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "需求" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,  
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_story AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'story\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "计划" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,  
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_productplan AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'productplan\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "发布" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,  
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_release AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'release\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "执行" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`, 
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_project AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'execution\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\' AND t2.type IN (\'sprint\', \'stage\', \'kanban\')
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "任务" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,  
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_task AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'task\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, \'Bug\' AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,  
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_bug AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'bug\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "版本" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`, 
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit` 
FROM zt_action AS t1 
LEFT JOIN zt_build AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'build\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "用例" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`,   
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_case AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'case\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "测试单" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`, 
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_testtask AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'testtask\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType
UNION ALL
SELECT 
  YEAR(t1.date) AS `year`, t1.actor, "文档" AS objectType, 
  SUM(IF(t1.action = \'opened\', 1, 0)) AS `create`, 
  SUM(IF(t1.action = \'edited\', 1, 0)) AS `edit`
FROM zt_action AS t1 
LEFT JOIN zt_doc AS t2 ON t1.objectID = t2.id
WHERE t1.objectType = \'doc\' AND t1.action IN (\'opened\', \'edited\') AND t2.deleted = \'0\'
GROUP BY `year`, actor, objectType) AS t2 ON t1.account = t2.account
WHERE t2.account IS NOT NULL', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1007, '年度总结-能力雷达图', 1, 'radar', '0', '', '', '{
"group":[{"field":"dimension","name":"维度"}],
"metric":[
  {"type":"value","field":"num","agg":"value","name": "产品管理", "key":"product","valOrAgg":"value"},
  {"type":"value","field":"num","agg":"value","name": "项目管理", "key":"project","valOrAgg":"value"},
  {"type":"value","field":"num","agg":"value","name": "研发", "key":"dev","valOrAgg":"value"},
  {"type":"value","field":"num","agg":"value","name": "测试", "key":"qa","valOrAgg":"value"},
  {"type":"value","field":"num","agg":"value","name": "其他", "key":"other","valOrAgg":"value"}
]}', '[]', 0, '', null, 'select tt.year, tt.actor AS account,tt.dimension, count(1) as num from (
SELECT YEAR(t1.date) as `year`, t1.actor, \'product\' as dimension
from zt_action t1
WHERE 
(
(t1.objectType = \'product\' AND t1.action in(\'opened\',\'edited\') and (select deleted from zt_product where id = t1.objectID) = \'0\')
OR (t1.objectType = \'story\' AND t1.action in(\'opened\',\'reviewed\',\'closed\') and (select deleted from zt_story where id = t1.objectID) = \'0\')
OR (t1.objectType = \'productplan\' AND t1.action in(\'opened\') and (select deleted from zt_productplan where id = t1.objectID) = \'0\')
OR (t1.objectType = \'release\' AND t1.action in(\'opened\') and (select deleted from zt_release where id = t1.objectID) = \'0\')
)
union all
SELECT YEAR(t1.date) as `year`, t1.actor, \'execution\' as dimension
from zt_action t1
WHERE 
(
(t1.objectType = \'execution\' AND t1.action in(\'opened\',\'edited\',\'started\',\'closed\') and (select deleted from zt_project where id = t1.objectID) = \'0\')
OR (t1.objectType = \'build\' AND t1.action in(\'opened\') and (select deleted from zt_build where id = t1.objectID) = \'0\')
OR (t1.objectType = \'task\' AND t1.action in(\'opened\',\'closed\',\'activated\',\'assigned\') and (select deleted from zt_task where id = t1.objectID) = \'0\')
)
union all
SELECT YEAR(t1.date) as `year`, t1.actor, \'devel\' as dimension
from zt_action t1
WHERE 
(
(t1.objectType = \'execution\' AND t1.action in(\'opened\',\'edited\',\'started\',\'closed\') and (select deleted from zt_project where id = t1.objectID) = \'0\')
OR (t1.objectType = \'build\' AND t1.action in(\'opened\') and (select deleted from zt_build where id = t1.objectID) = \'0\')
OR (t1.objectType = \'task\' AND t1.action in(\'opened\',\'closed\',\'assigned\') and (select deleted from zt_task where id = t1.objectID) = \'0\')
OR (t1.objectType = \'task\' and t1.action in (\'gitcommited\', \'svncommited\'))
OR (t1.objectType = \'bug\' AND t1.action in(\'resolved\') and (select deleted from zt_bug where id = t1.objectID) = \'0\')
)
union all
SELECT YEAR(t1.date) as `year`, t1.actor, \'qa\' as dimension
from zt_action t1
WHERE 
(
(t1.objectType = \'bug\' AND t1.action in(\'opened\',\'closed\',\'activated\') and (select deleted from zt_bug where id = t1.objectID) = \'0\')
OR (t1.objectType = \'case\' AND t1.action in(\'opened\',\'run\') and (select deleted from zt_case where id = t1.objectID) = \'0\')
OR (t1.objectType = \'testtask\' AND t1.action in(\'opened\',\'edited\') and (select deleted from zt_testtask where id = t1.objectID) = \'0\')
)
) tt WHERE tt.year != \'0000\'
GROUP BY tt.year, tt.dimension', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1008, '年度总结-迭代数据', 1, 'table', '0', '', '', '{"group":[],"column":[
{"field":"name","valOrAgg":"value","name":"迭代名称"},{"field":"finishedStory","valOrAgg":"value","name":"完成需求数"},
{"field":"finishedTask","valOrAgg":"value","name":"完成任务数"},
{"field":"resolvedBug","valOrAgg":"value","name":"解决Bug数"}
],"filter":[]}', '[]', 0, '', null, 'SELECT 
tt.id, 
tt.name, 
tt.year, 
tt.account,
tt.finishedStory,
tt.finishedTask,
count(t3.id) as resolvedBug
from (
SELECT 
tt.id, 
t2.name, 
tt.year, 
tt.account,
SUM(if((t1.story != 0), 1 , 0)) as finishedStory,
count(t1.id) as finishedTask
from (
SELECT 
*
from (
SELECT id, YEAR(begin) as year, openedBy as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(begin) != \'0000\'
union all
SELECT id, YEAR(begin) as year, PO as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(begin) != \'0000\'
union all
SELECT id, YEAR(begin) as year, PM as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(begin) != \'0000\'
union all
SELECT id, YEAR(begin) as year, QD as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(begin) != \'0000\'
union all
SELECT id, YEAR(begin) as year, RD as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(begin) != \'0000\'
union all
SELECT id, YEAR(end) as year, openedBy as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(end) != \'0000\'
union all
SELECT id, YEAR(end) as year, PO as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(end) != \'0000\'
union all
SELECT id, YEAR(end) as year, PM as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(end) != \'0000\'
union all
SELECT id, YEAR(end) as year, QD as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(end) != \'0000\'
union all
SELECT id, YEAR(end) as year, RD as account from zt_project
WHERE deleted = \'0\' AND type = \'sprint\' and multiple = \'1\' and YEAR(end) != \'0000\'
union all
SELECT t1.root as id, YEAR(t1.`join`) as year, t1.account from zt_team t1
RIGHT JOIN zt_project t2 on t2.id = t1.root and t2.deleted = \'0\' and t2.type = \'sprint\'
WHERE t1.type = \'execution\' and YEAR(t1.`join`) != \'0000\'
union all
SELECT t1.execution as id, YEAR(t1.finishedDate) as year, t1.finishedBy as account from zt_task t1
RIGHT JOIN zt_project t2 on t2.id = t1.execution and t2.deleted = \'0\' and t2.type = \'sprint\'
WHERE t1.deleted = \'0\' and YEAR(t1.finishedDate) != \'0000\'
) tt 
where tt.account != \'\'
GROUP BY tt.id, tt.`year`, tt.account
) tt
LEFT JOIN zt_task t1 on t1.execution = tt.id and YEAR(t1.finishedDate) = tt.year and t1.deleted = \'0\' and t1.finishedBy = tt.account
LEFT JOIN zt_project t2 on t2.id = tt.id
GROUP BY tt.id, tt.`year`, tt.account
) tt
LEFT JOIN zt_bug t2 on t2.resolvedBy = tt.account and YEAR(t2.resolvedDate) = tt.year
left join zt_build t3 on t2.resolvedBuild = t3.id and t3.execution = tt.id
WHERE t2.deleted = \'0\'
GROUP BY tt.account, tt.`year`, tt.id', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1009, '年度总结-产品数据', 1, 'table', '0', '', '', '{"group":[],"column":[
{"field":"name","valOrAgg":"value","name":"产品名称"},{"field":"plan","valOrAgg":"value","name":"计划数"},
{"field":"requirement","valOrAgg":"value","name":"创建用户需求数"},
{"field":"story","valOrAgg":"value","name":"创建需求数"},
{"field":"closedStory","valOrAgg":"value","name":"关闭需求数"}
],"filter":[]}', '[]', 0, '', null, 'select * from (
select tt.id, t2.name, tt.year, tt.account, tt.plans, tt.requirement, tt.story, count(t1.id) as closedStory
from(
  select tt.id, tt.year, tt.account, tt.plans,
sum(if((type = \'requirement\'), 1, 0)) as requirement,
sum(if((type = \'story\'), 1, 0)) as story
from (
select tt.id, tt.year, tt.account,
count(t2.id) as plans
from (
select * from (
select id, YEAR(createdDate) as `year`, createdBy as account from zt_product
where deleted = \'0\' and shadow = \'0\'
union all
select id, YEAR(createdDate) as `year`, PO as account from zt_product
where deleted = \'0\' and shadow = \'0\'
union all
select id, YEAR(createdDate) as `year`, QD as account from zt_product
where deleted = \'0\' and shadow = \'0\'
union all
select id, YEAR(createdDate) as `year`, RD as account from zt_product
where deleted = \'0\' and shadow = \'0\'
) tt
WHERE tt.account != \'\' and tt.year != \'0000\'
GROUP BY tt.account, tt.year, tt.id
) tt
LEFT JOIN zt_productplan t1 on t1.product = tt.id
LEFT JOIN zt_action t2 on t1.id = t2.objectID and YEAR(t2.date) = tt.year
and t2.objectType = \'productplan\'
and t1.deleted = \'0\'
and t2.actor = tt.account
and t2.action = \'opened\'
GROUP BY tt.account, tt.year, tt.id) tt
LEFT JOIN zt_story t1 on t1.product = tt.id and YEAR(t1.openedDate) = tt.year and t1.openedBy = tt.account and t1.deleted = \'0\'
GROUP BY tt.account, tt.year, tt.id) tt
LEFT JOIN zt_story t1 on t1.product = tt.id and YEAR(t1.closedDate) = tt.year and t1.closedBy = tt.account and t1.deleted = \'0\'
LEFT JOIN zt_product t2 on t2.id = tt.id
GROUP BY tt.account, tt.year, tt.id) tt
WHERE tt.account = \'zhangpeng\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1010, '年度总结-任务状态分布', 1, 'pie', '0', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"任务数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT
YEAR(t1.date) AS `year`,
t3.account,
t3.realname,
CASE t2.status
WHEN \'wait\' THEN "未开始"
WHEN \'doing\' THEN "进行中"
WHEN \'done\' THEN "已完成"
WHEN \'closed\' THEN "已关闭"
ELSE "未设置" END status,
t1.id
FROM zt_action t1
LEFT JOIN zt_task t2 on t1.objectID=t2.id RIGHT JOIN zt_user t3 on t1.actor=t3.account
WHERE t1.objectType = \'task\'
and t2.deleted = \'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1011, '年度总结-每月任务操作情况', 1, 'bar', '0', '', '', '{
  "xaxis":[{"field":"actionDate","name":"日期","group":"value"}],
  "yaxis":[{"type":"agg","field":"opened","agg":"sum","name":"创建","valOrAgg":"sum"},
{"type":"agg","field":"started","agg":"sum","name":"开始","valOrAgg":"sum"},
{"type":"agg","field":"finished","agg":"sum","name":"完成","valOrAgg":"sum"},
{"type":"agg","field":"paused","agg":"sum","name":"暂停","valOrAgg":"sum"},
{"type":"agg","field":"activated","agg":"sum","name":"激活","valOrAgg":"sum"},
{"type":"agg","field":"canceled","agg":"sum","name":"取消","valOrAgg":"sum"},
{"type":"agg","field":"closed","agg":"sum","name":"关闭","valOrAgg":"sum"}
]}', '[]', 0, '', null, 'SELECT t2.opened,t2.started,t2.finished,t2.paused,t2.activated,t2.canceled,t2.closed,t1.account,t2.actionDate,YEAR(CONCAT(t2.actionDate, \'-01\')) AS `year`,realname,t3.`name` AS deptName FROM zt_user AS t1
LEFT JOIN (
    SELECT t21.actor,LEFT(t21.`date`,7) AS actionDate,
    SUM(if(t21.action = \'opened\', 1, 0)) as opened,
    SUM(if(t21.action = \'started\', 1, 0)) as started,
    SUM(if(t21.action = \'finished\', 1, 0)) as finished,
    SUM(if(t21.action = \'paused\', 1, 0)) as paused,
    SUM(if(t21.action = \'activated\', 1, 0)) as activated,
    SUM(if(t21.action = \'canceled\', 1, 0)) as canceled,
    SUM(if(t21.action = \'closed\', 1, 0)) as closed FROM zt_action AS t21
    LEFT JOIN zt_story AS t22 ON t21.objectID=t22.id
    WHERE t21.objectType=\'bug\'
    AND t22.deleted=\'0\'
    GROUP BY t21.actor,actionDate
) AS t2 ON t1.account=t2.actor
LEFT JOIN zt_dept AS t3 ON t1.dept=t3.id
WHERE t1.deleted=\'0\'
AND t2.actor IS NOT NULL
GROUP BY t2.actionDate,deptName,t1.account,realname
', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1012, '年度总结-需求状态分布', 1, 'pie', '0', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT
YEAR(t1.date) AS `year`,
t3.account,
t3.realname,
CASE t2.status
WHEN \'wait\' THEN "未开始"
WHEN \'doing\' THEN "进行中"
WHEN \'done\' THEN "已完成"
WHEN \'colsed\' THEN "已关闭"
ELSE "未设置"
END status,
t1.id
FROM zt_action t1
LEFT JOIN zt_task t2 on t1.objectID=t2.id RIGHT JOIN zt_user t3 on t1.actor=t3.account
WHERE t1.objectType = \'story\'
and t2.deleted = \'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1013, '年度总结-每月需求操作情况', 1, 'bar', '0', '', '', '{
  "xaxis":[{"field":"actionDate","name":"日期","group":"value"}],
  "yaxis":[{"type":"value","field":"opened","agg":"value","name":"创建","valOrAgg":"value"},
{"type":"value","field":"activated","agg":"value","name":"激活","valOrAgg":"value"},
{"type":"value","field":"changed","agg":"value","name":"变更","valOrAgg":"value"},
{"type":"value","field":"closed","agg":"value","name":"关闭","valOrAgg":"value"}
]}', '[]', 0, '', null, 'SELECT t2.opened,t2.activated,t2.closed,t2.`changed`,t1.account,t2.actionDate,YEAR(CONCAT(t2.actionDate,\'-01\')) AS `year`,realname,t3.`name` AS deptName FROM zt_user AS t1
LEFT JOIN (
    SELECT t21.actor,LEFT(t21.`date`, 7) AS actionDate,
    SUM(IF(t21.action=\'opened\',1,0)) AS opened,
    SUM(IF(t21.action=\'activated\',1,0)) AS activated,
    SUM(IF(t21.action=\'closed\',1,0)) AS closed,
    SUM(IF(t21.action=\'changed\',1,0)) AS `changed` FROM zt_action AS t21
    LEFT JOIN zt_story AS t22 ON t21.objectID=t22.id
    WHERE t21.objectType=\'story\'
    AND t22.deleted=\'0\'
    GROUP BY t21.actor,actionDate
) AS t2 ON t1.account=t2.actor
LEFT JOIN zt_dept AS t3 ON t1.dept=t3.id
WHERE t1.deleted=\'0\'
AND t2.actor IS NOT NULL
GROUP BY t2.actionDate,deptName,t1.account,realname
', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1014, '年度总结-Bug状态分布', 1, 'pie', '0', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"Bug数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT
YEAR(t1.date) AS `year`, t3.account,
t3.realname,
CASE t2.status
WHEN \'wait\' THEN "未开始"
WHEN \'doing\' THEN "进行中"
WHEN \'done\' THEN "已完成"
WHEN \'closed\' THEN "已关闭"
ELSE "未设置"
END status,
t1.id
FROM zt_action t1
LEFT JOIN zt_task t2 on t1.objectID=t2.id RIGHT JOIN zt_user t3 on t1.actor=t3.account
WHERE t1.objectType = \'bug\'
and t2.deleted = \'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1015, '年度总结-每月Bug操作情况', 1, 'bar', '0', '', '', '{
  "xaxis":[{"field":"actionDate","name":"日期","group":"value"}],
  "yaxis":[{"type":"value","field":"opened","agg":"value","name":"创建","valOrAgg":"value"},
{"type":"value","field":"bugconfirmed","agg":"value","name":"确认","valOrAgg":"value"},
{"type":"value","field":"activated","agg":"value","name":"激活","valOrAgg":"value"},
{"type":"value","field":"resolved","agg":"value","name":"解决","valOrAgg":"value"},
{"type":"value","field":"closed","agg":"value","name":"关闭","valOrAgg":"value"}
]}', '[]', 0, '', null, 'SELECT t2.opened,t2.bugconfirmed,t2.activated,t2.resolved,t2.closed,t1.account,t2.actionDate,YEAR(CONCAT(t2.actionDate, \'-01\')) AS 
`year`,realname,t3.`name` AS deptName FROM zt_user AS t1
LEFT JOIN (
    SELECT t21.actor,LEFT(t21.`date`, 7) AS actionDate,
    SUM(IF(t21.action=\'opened\',1,0)) AS opened,
    SUM(IF(t21.action=\'bugconfirmed\',1,0)) AS bugconfirmed,
    SUM(IF(t21.action=\'activated\',1,0)) AS activated,
    SUM(IF(t21.action=\'resolved\',1,0)) AS resolved,
    SUM(IF(t21.action=\'closed\',1,0)) AS closed FROM zt_action AS t21
    LEFT JOIN zt_story AS t22 ON t21.objectID=t22.id
    WHERE t21.objectType=\'bug\'
    AND t22.deleted=\'0\'
    GROUP BY t21.actor,actionDate
) AS t2 ON t1.account=t2.actor
LEFT JOIN zt_dept AS t3 ON t1.dept=t3.id
WHERE t1.deleted=\'0\'
AND t2.actor IS NOT NULL
GROUP BY t2.actionDate,deptName,t1.account,realname', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1016, '年度总结-用例结果分布', 1, 'pie', '0', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"个数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT count ,t2.caseResult as status,t2.`year`, t1.account, realname, dept
FROM zt_user t1
LEFT JOIN (
    SELECT t21.lastRunner, YEAR(t21.`date`) as \'year\', t21.caseResult, count(distinct t21.`id`) as count
    FROM zt_testresult t21 
    LEFT JOIN zt_case t22 on t21.case = t22.id
    WHERE t22.deleted = \'0\'
    GROUP BY t21.lastRunner, `year`, t21.caseResult
) t2 on t1.account = t2.lastRunner
WHERE t1.deleted = \'0\'
GROUP BY t2.caseResult,t2.`year`, t1.account, realname, dept', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1017, '年度总结-每月用例操作情况', 1, 'bar', '0', '', '', '{
  "xaxis":[{"field":"actionDate","name":"日期","group":"value"}],
  "yaxis":[{"type":"value","field":"createdCases","agg":"value","name":"创建","valOrAgg":"value"},
{"type":"value","field":"toBugCases","agg":"value","name":"转Bug","valOrAgg":"value"},
{"type":"value","field":"runCases","agg":"value","name":"执行","valOrAgg":"value"}
]}', '[]', 0, '', null, 'SELECT SUM(createdCases) AS createdCases, SUM(toBugCases) AS toBugCases, SUM(runCases) AS runCases, YEAR(CONCAT(t2.actionDate, \'-01\')) AS `year`, t1.account, realname, dept
FROM zt_user t1
LEFT JOIN (
    SELECT t21.actor, LEFT(t21.`date`, 7) as actionDate, 
    SUM(IF((t22.id IS NOT NULL AND t23.id IS NULL), 1, 0)) AS createdCases, 
    SUM(IF((t22.id IS NOT NULL AND t23.id IS NOT NULL), 1, 0)) AS toBugCases, 
    SUM(IF((t24.lastRunner = t21.actor AND t21.action = \'run\' AND t21.`date` = t24.`date`), 1, 0)) AS runCases
    FROM zt_action t21 
    LEFT JOIN zt_case t22 on t21.objectID = t22.id
    LEFT JOIN zt_bug t23 on t22.id = t23.case
    LEFT JOIN zt_testresult t24 on t22.id = t24.`case` AND t24.lastRunner = t21.actor AND t21.action = \'run\' AND t21.`date` = t24.`date`
    WHERE t21.objectType = \'case\'
    AND t21.action in (\'opened\', \'run\')
    AND t22.deleted = \'0\'
    AND (t23.deleted = \'0\' OR t23.id IS NULL)
    GROUP BY t21.actor, actionDate
) t2 on t1.account = t2.actor
WHERE t1.deleted = \'0\'
AND t2.actor is not null
GROUP BY t2.actionDate, t1.account, realname, dept', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1018, '宏观数据-一级项目集个数', 1, 'card', '45', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '{}', null, 'SELECT id,name FROM zt_project WHERE type=\'program\' AND parent=0 AND deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1019, '宏观数据-项目个数', 1, 'card', '46', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_project WHERE type=\'project\' AND deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1020, '宏观数据-产品个数', 1, 'card', '47', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_product WHERE deleted=\'0\' AND shadow = \'0\' AND vision = \'rnd\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1021, '宏观数据-计划个数', 1, 'card', '48', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_productplan WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1022, '宏观数据-执行个数', 1, 'card', '49', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_project WHERE type IN (\'sprint\',\'stage\',\'kanban\') AND deleted=\'0\' AND multiple = \'1\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1023, '宏观数据-发布个数', 1, 'card', '50', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_release WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1024, '宏观数据-需求个数', 1, 'card', '51', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_story WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1025, '宏观数据-任务个数', 1, 'card', '52', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_task WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1026, '宏观数据-缺陷个数', 1, 'card', '53', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_bug WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1027, '宏观数据-文档个数', 1, 'card', '54', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_doc WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1028, '宏观数据-现有人员个数', 1, 'card', '55', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_user WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1029, '宏观数据-累计消耗工时', 1, 'card', '55', '', '', '{"value": {"type": "agg", "field": "consumed", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT consumed FROM zt_effort WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1030, '宏观数据-禅道使用时长', 1, 'card', '58', '', '', '{"value": {"type": "value", "field": "period", "agg": "value"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, '	SELECT if(t2.`year` > 0, concat(t2.`year`, \'年\', t2.`day`, \'天\'), concat(t2.`day`, \'天\')) as period from (
SELECT TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) AS `year`,DATEDIFF(DATE_SUB(t1.today,INTERVAL TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) YEAR), t1.firstDay) AS `day`  
FROM (SELECT `value` AS firstDay, now() AS today FROM zt_config WHERE `owner` = \'system\' AND `key` = \'installedDate\') AS t1
) t2', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1031, '宏观数据-需求完成率', 1, 'piecircle', '36', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT id, IF(closedReason=\'done\', \'done\', \'undone\') AS status FROM zt_story WHERE deleted=\'0\' AND (status != \'closed\' OR closedReason=\'done\')', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1032, '宏观数据-Bug修复率', 1, 'piecircle', '44', '', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"Bug数","valOrAgg":"count"}]}', '[]', 0, '', null, 'SELECT id, IF(`status`=\'closed\' AND resolution=\'fixed\', \'done\', \'undone\') AS status FROM zt_bug WHERE deleted=\'0\' AND (status = \'active\' OR resolution in (\'fixed\', \'postponed\'))', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1033, '宏观数据-未完成的一级项目集个数', 1, 'card', '45', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_project WHERE type=\'program\' AND `status`!=\'closed\' AND deleted=\'0\' AND grade=\'1\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1034, '宏观数据-未完成的需求', 1, 'card', '51', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_story WHERE `status`!=\'closed\' AND deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1035, '宏观数据-未完成的产品', 1, 'card', '47', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_product WHERE `status`!=\'closed\' AND deleted=\'0\' AND shadow=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1036, '宏观数据-未完成的项目', 1, 'card', '46', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_project WHERE type=\'project\' AND `status`!=\'closed\' AND deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1037, '宏观数据-未完成的计划', 1, 'card', '48', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM (SELECT id,deleted FROM zt_productplan WHERE NOT ((`status`=\'closed\' AND closedReason=\'done\') OR `status`=\'done\')) AS plan WHERE plan.deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1038, '宏观数据-未完成的执行', 1, 'card', '49', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_project WHERE type IN (\'sprint\',\'stage\',\'kanban\') AND `status`!=\'closed\' AND deleted=\'0\' AND multiple = \'1\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1039, '宏观数据-未完成的缺陷', 1, 'card', '53', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM zt_bug WHERE `status`!=\'closed\' AND deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1040, '宏观数据-未完成的任务', 1, 'card', '52', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', null, 'SELECT id FROM (SELECT id,deleted FROM zt_task WHERE NOT ((`status`=\'closed\' AND closedReason=\'cancel\') OR `status`=\'done\')) AS task WHERE task.deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1041, '宏观数据-项目集数据概览', 1, 'table', '64', '', '', '{"group":[],"column":[
{"field":"topProgram","valOrAgg":"value","name":"一级项目集"},{"field":"subProgram","valOrAgg":"value","name":"子项目集数"},
{"field":"product","valOrAgg":"value","name":"产品数"},
{"field":"story","valOrAgg":"value","name":"研发需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"},
{"field":"release","valOrAgg":"value","name":"发布数"},
{"field":"project","valOrAgg":"value","name":"项目数"},
{"field":"execution","valOrAgg":"value","name":"执行数"},
{"field":"task","valOrAgg":"value","name":"任务数"}
],"filter":[]}', '[]', 0, '', null, 'SELECT
  t1.name AS topProgram,
  IFNULL(t2.subProgram, 0) AS subProgram,
  COUNT(DISTINCT t3.id) AS product,
  SUM(IFNULL(t4.story, 0)) AS story,
  SUM(IFNULL(t5.`release`, 0)) AS "release",
  SUM(IFNULL(t6.bug, 0)) AS bug,
  IFNULL(t7.project, 0) AS project,
  IFNULL(t7.execution, 0) AS execution,
  IFNULL(t7.task, 0) AS task
FROM zt_project AS t1
LEFT JOIN (SELECT SUBSTR(path, 2, POSITION(\',\' IN SUBSTR(path, 2)) -1) AS topProgram, COUNT(1) AS subProgram FROM zt_project WHERE deleted = \'0\' AND type = \'program\' AND grade > 1 GROUP BY topProgram) AS t2 ON t1.id = t2.topProgram
LEFT JOIN zt_product AS t3 ON t1.id = t3.program AND t3.deleted = \'0\' AND t3.shadow = \'0\' AND t3.vision = \'rnd\'
LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' GROUP BY product) AS t4 ON t3.id = t4.product
LEFT JOIN (SELECT product, COUNT(1) AS "release" FROM zt_release WHERE deleted = \'0\' GROUP BY product) AS t5 ON t3.id = t5.product
LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' GROUP BY product) AS t6 ON t3.id = t6.product
LEFT JOIN (
  SELECT t1.topProgram, COUNT(DISTINCT t1.project) AS project, SUM(t2.task) AS task, SUM(t3.execution) AS execution
  FROM (SELECT SUBSTR(path, 2, POSITION(\',\' IN SUBSTR(path, 2)) -1) AS topProgram, id AS project FROM zt_project WHERE deleted = \'0\' AND type = \'project\') AS t1
  LEFT JOIN (SELECT COUNT(1) AS task, project FROM zt_task WHERE deleted = \'0\' GROUP BY project) AS t2 ON t1.project = t2.project
  LEFT JOIN (SELECT COUNT(1) AS execution,project FROM zt_project WHERE deleted = \'0\' AND type IN (\'sprint\', \'stage\', \'kanban\') GROUP BY project) AS t3 ON t1.project = t3.project
  GROUP BY t1.topProgram
) AS t7 ON t1.id = t7.topProgram
WHERE t1.deleted = \'0\' AND t1.type = \'program\' AND t1.grade = 1
GROUP BY t1.name', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1042, '宏观数据-项目集需求完成率与Bug修复率', 1, 'cluBarY', '45', '', '', '[{"type":"cluBarY","xaxis":[{"field":"topProgram","name":"topProgram","group":""}],"yaxis":[{"field":"storyDoneRate","name":"storyDoneRate","valOrAgg":"sum"},{"field":"bugSolvedRate","name":"bugSolvedRate","valOrAgg":"sum"}]}]', '[]', 0, '{"topProgram":{"name":"topProgram","object":"bug","field":"topProgram","type":"string"},"doneStory":{"name":"doneStory","object":"bug","field":"doneStory","type":"number"},"allStory":{"name":"allStory","object":"bug","field":"allStory","type":"number"},"storyDoneRate":{"name":"storyDoneRate","object":"bug","field":"storyDoneRate","type":"number"},"solvedBug":{"name":"solvedBug","object":"bug","field":"solvedBug","type":"number"},"allBug":{"name":"allBug","object":"bug","field":"allBug","type":"number"},"bugSolvedRate":{"name":"bugSolvedRate","object":"bug","field":"bugSolvedRate","type":"number"}}', '{"topProgram":{"zh-cn":"\\u4e00\\u7ea7\\u9879\\u76ee\\u96c6","zh-tw":"","en":"topProgram","de":"","fr":""},"doneStory":{"zh-cn":"\\u5b8c\\u6210\\u9700\\u6c42\\u6570","zh-tw":"","en":"doneStory","de":"","fr":""},"allStory":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"","en":"allStory","de":"","fr":""},"storyDoneRate":{"zh-cn":"\\u9700\\u6c42\\u5b8c\\u6210\\u7387","zh-tw":"","en":"storyDoneRate","de":"","fr":""},"solvedBug":{"zh-cn":"\\u89e3\\u51b3bug\\u6570","zh-tw":"","en":"solvedBug","de":"","fr":""},"allBug":{"zh-cn":"bug\\u6570","zh-tw":"","en":"allBug","de":"","fr":""},"bugSolvedRate":{"zh-cn":"bug\\u4fee\\u590d\\u7387","zh-tw":"","en":"bugSolvedRate","de":"","fr":""}}', 'SELECT
    t1.name AS topProgram,
    SUM(IFNULL(t3.doneStory,0)) as doneStory,
    SUM(IFNULL(t4.allStory,0)) as allStory,
    CONVERT(IF(SUM(IFNULL(t4.allStory,0)) <= 0, 0, SUM(IFNULL(t3.doneStory,0)) / SUM(IFNULL(t4.allStory,0))*100), decimal(10,2)) as storyDoneRate,                                                                                                                                         
    SUM(IFNULL(t5.solvedBug,0)) as solvedBug,
    SUM(IFNULL(t6.allBug,0)) as allBug,
    CONVERT(IF(SUM(IFNULL(t6.allBug,0)) <= 0, 0, SUM(IFNULL(t5.solvedBug,0)) / SUM(IFNULL(t6.allBug,0))*100), decimal(10,2)) as bugSolvedRate
FROM zt_project AS t1
LEFT JOIN zt_product AS t2 ON t1.id = t2.program
LEFT JOIN (SELECT COUNT(1) as doneStory, product FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' AND status = \'closed\' GROUP BY product) AS t3 ON t2.id = t3.product
LEFT JOIN (SELECT COUNT(1) as allStory, product FROM zt_story WHERE deleted = \'0\' AND ((closedReason = \'done\' AND status = \'closed\') OR status != \'closed\') GROUP BY product) AS t4 ON t2.id = t4.product
LEFT JOIN (SELECT COUNT(1) as solvedBug, product FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY product) AS t5 ON t2.id = t5.product
LEFT JOIN (SELECT COUNT(1) as allBug, product FROM zt_bug WHERE deleted = \'0\' AND (resolution in (\'fixed\', \'postponed\') OR status = \'active\') GROUP BY product) AS t6 ON t2.id = t6.product
WHERE t1.type = \'program\' AND t1.grade = 1 AND t1.deleted = \'0\'
AND t2.deleted = \'0\'
GROUP BY t1.name
ORDER BY t1.`order` DESC', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1043, '宏观数据-公司项目集状态分布', 1, 'pie', '45', '', '', '[{"type":"pie","group":[{"field":"status","name":"\\u72b6\\u6001","group":""}],"metric":[{"field":"id","name":"\\u9879\\u76eeID","valOrAgg":"count"}]}]', '[]', 0, '{"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"}}', '{"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"id","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""}}', 'SELECT id, CASE `status` WHEN \'wait\' then \'未开始\' WHEN \'doing\' THEN \'进行中\' WHEN \'suspended\' THEN \'已挂起\' ELSE \'已关闭\' END status FROM zt_project  WHERE type = \'program\' AND grade = 1 AND deleted = \'0\'', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1044, '宏观数据-公司项目状态分布', 1, 'pie', '38', '', '', '[{"type":"pie","group":[{"field":"status","name":"\\u72b6\\u6001","group":""}],"metric":[{"field":"id","name":"\\u9879\\u76eeID","valOrAgg":"count"}]}]', '[]', 0, '{"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"}}', '{"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"id","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""}}', 'SELECT id, CASE `status` WHEN \'wait\' then \'未开始\' WHEN \'doing\' THEN \'进行中\' WHEN \'suspended\' THEN \'已挂起\' ELSE \'已关闭\' END status FROM zt_project  WHERE type = \'project\' AND deleted = \'0\'', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1045, '宏观数据-产品数据概览', 1, 'table', '63', '', '', '{"group":[],"column":[
{"field":"program","valOrAgg":"value","name":"一级项目集"},{"field":"productLine","valOrAgg":"value","name":"产品线"},
{"field":"product","valOrAgg":"value","name":"产品"},
{"field":"story","valOrAgg":"value","name":"需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"},
{"field":"plan","valOrAgg":"value","name":"计划数"},
{"field":"release","valOrAgg":"value","name":"发布数"}],"filter":[]}', '[]', 0, '', null, 'SELECT 
  t1.name AS product, 
  IFNULL(t2.name, \'/\') AS program, 
  IFNULL(t3.name, \'/\') AS productLine, 
  IFNULL(t4.plan, 0) AS plan, 
  IFNULL(t5.release, 0) AS `release`, 
  IFNULL(t6.story, 0) AS story, 
  IFNULL(t7.bug, 0) AS bug 
FROM 
  zt_product AS t1 
  LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = \'program\' AND t2.grade = 1 
  LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = \'line\' 
  LEFT JOIN (SELECT product, COUNT(1) AS plan FROM zt_productplan WHERE deleted = \'0\' GROUP BY product) AS t4 ON t1.id = t4.product 
  LEFT JOIN (SELECT product, COUNT(1) AS `release` FROM zt_release WHERE deleted = \'0\' GROUP BY product) AS t5 ON t1.id = t5.product 
  LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' GROUP BY product) AS t6 ON t1.id = t6.product 
  LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' GROUP BY product) AS t7 ON t1.id = t7.product 
WHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\'AND t1.vision = \'rnd\'
ORDER BY t1.order', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1046, '宏观数据-产品需求完成率', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"\\u6240\\u5c5e\\u4ea7\\u54c1","group":""}],"yaxis":[{"field":"closedRate","name":"closedRate","valOrAgg":"sum"}]}]', '[]', 0, '{"product":{"name":"\\u6240\\u5c5e\\u4ea7\\u54c1","object":"story","field":"product","type":"string"},"program":{"name":"program","object":"story","field":"program","type":"string"},"productLine":{"name":"productLine","object":"story","field":"productLine","type":"string"},"closedStory":{"name":"\\u9700\\u6c42\\uff1a%s \\u5df2\\u5173\\u95ed\\uff0c\\u5c06\\u4e0d\\u4f1a\\u88ab\\u5173\\u95ed\\u3002","object":"story","field":"closedStory","type":"string"},"totalStory":{"name":"totalStory","object":"story","field":"totalStory","type":"string"},"closedRate":{"name":"closedRate","object":"story","field":"closedRate","type":"number"}}', '{"product":{"zh-cn":"\\u6240\\u5c5e\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6","zh-tw":"","en":"program","de":"","fr":""},"productLine":{"zh-cn":"\\u4ea7\\u54c1\\u7ebf","zh-tw":"","en":"productLine","de":"","fr":""},"closedStory":{"zh-cn":"\\u5b8c\\u6210\\u9700\\u6c42\\u6570","zh-tw":"","en":"closedStory","de":"","fr":""},"totalStory":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"","en":"totalStory","de":"","fr":""},"closedRate":{"zh-cn":"\\u9700\\u6c42\\u5b8c\\u6210\\u7387","zh-tw":"","en":"closedRate","de":"","fr":""}}', 'SELECT
  t1.name AS product,
  IFNULL(t2.name, \'/\') AS program, 
  IFNULL(t3.name, \'/\') AS productLine,   
  IFNULL(t4.story, 0) AS closedStory, 
  t5.story AS totalStory, 
  ROUND(IFNULL(t4.story, 0) / t5.story * 100, 2) AS closedRate 
FROM zt_product AS t1 
LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = \'program\' AND t2.grade = 1 
LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = \'line\' 
LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' GROUP BY product) AS t4 ON t1.id = t4.product 
LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' AND ( closedReason = \'done\' OR status != \'closed\') GROUP BY product) AS t5 ON t1.id = t5.product 
WHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\' AND t5.story IS NOT NULL 
ORDER BY t1.order DESC', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1047, '宏观数据-产品Bug修复率', 1, 'cluBarY', '44', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"\\u6240\\u5c5e\\u4ea7\\u54c1","group":""}],"yaxis":[{"field":"fixedRate","name":"\\u4fee\\u590d\\u7387","valOrAgg":"sum"}]}]', '[]', 0, '{"product":{"name":"\\u6240\\u5c5e\\u4ea7\\u54c1","object":"bug","field":"product","type":"string"},"program":{"name":"program","object":"bug","field":"program","type":"string"},"productLine":{"name":"productLine","object":"bug","field":"productLine","type":"string"},"fixedBug":{"name":"fixedBug","object":"bug","field":"fixedBug","type":"string"},"totalBug":{"name":"totalBug","object":"bug","field":"totalBug","type":"string"},"fixedRate":{"name":"\\u4fee\\u590d\\u7387","object":"bug","field":"fixedRate","type":"number"}}', '{"product":{"zh-cn":"\\u6240\\u5c5e\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6","zh-tw":"","en":"program","de":"","fr":""},"productLine":{"zh-cn":"\\u4ea7\\u54c1\\u7ebf","zh-tw":"","en":"productLine","de":"","fr":""},"fixedBug":{"zh-cn":"\\u4fee\\u590dbug\\u6570","zh-tw":"","en":"fixedBug","de":"","fr":""},"totalBug":{"zh-cn":"bug\\u6570","zh-tw":"","en":"totalBug","de":"","fr":""},"fixedRate":{"zh-cn":"bug\\u4fee\\u590d\\u7387","zh-tw":"","en":"fixedRate","de":"","fr":""}}', 'SELECT 
  t1.name AS product,
  IFNULL(t2.name, \'/\') AS program,
  IFNULL(t3.name, \'/\') AS productLine,
  IFNULL(t4.bug, 0) AS fixedBug,
  t5.bug AS totalBug,
  ROUND(IFNULL(t4.bug, 0) / t5.bug * 100, 2) AS fixedRate
FROM zt_product AS t1
LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = \'program\' AND t2.grade = 1
LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = \'line\'
LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY product) AS t4 ON t1.id = t4.product
LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' AND (resolution = \'fixed\' OR resolution = \'postponed\' OR status = \'active\') GROUP BY product) AS t5 ON t1.id = t5.product
WHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\'  AND t5.bug IS NOT NULL
ORDER BY t1.order DESC', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1049, '宏观数据-部门人员分布图', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"deptName","name":"deptName","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[]', 0, '{"deptName":{"name":"deptName","object":"false","field":"deptName","type":"object"},"count":{"name":"count","object":"false","field":"count","type":"object"},"deptOrder":{"name":"deptOrder","object":"false","field":"deptOrder","type":"object"}}', '{"deptName":{"zh-cn":"\\u90e8\\u95e8","zh-tw":"","en":"deptName","de":"","fr":""},"count":{"zh-cn":"\\u4eba\\u6570","zh-tw":"","en":"count","de":"","fr":""},"deptOrder":{"zh-cn":"\\u987a\\u5e8f","zh-tw":"","en":"deptOrder","de":"","fr":""}}', 'SELECT IF(t3.id IS NOT NULL, t3.`name`, "空") AS deptName,count(1) as count, 
IF(t3.id IS NOT NULL, t3.`order`, 9999) AS deptOrder 
FROM zt_user AS t1 
LEFT JOIN zt_dept AS t2 ON t1.dept = t2.id
LEFT JOIN zt_dept AS t3 ON FIND_IN_SET(TRIM(\',\' FROM t3.path), TRIM(\',\' FROM t2.path)) AND t3.grade = \'1\'
WHERE t1.deleted = \'0\'
GROUP BY deptName, deptOrder 
ORDER BY deptOrder  ASC', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1050, '宏观数据-公司角色分布图', 1, 'pie', '56', '', '', '[{"type":"pie","group":[{"field":"role","name":"\\u804c\\u4f4d","group":""}],"metric":[{"field":"account","name":"\\u7528\\u6237\\u540d","valOrAgg":"count"}]}]', '[]', 0, '{"account":{"name":"\\u7528\\u6237\\u540d","object":"user","field":"account","type":"string"},"role":{"name":"\\u804c\\u4f4d","object":"user","field":"role","type":"string"}}', '{"account":{"zh-cn":"\\u7528\\u6237\\u540d","zh-tw":"","en":"account","de":"","fr":""},"role":{"zh-cn":"\\u804c\\u4f4d","zh-tw":"","en":"role","de":"","fr":""}}', 'SELECT
	account,
CASE
		ROLE 
		WHEN \'dev\' THEN
		"研发" 
		WHEN \'qa\' THEN
		"测试" 
		WHEN \'pm\' THEN
		"项目经理" 
		WHEN \'others\' THEN
		"其他" 
		WHEN \'td\' THEN
		"研发主管" 
		WHEN \'pd\' THEN
		"产品主管" 
		WHEN \'po\' THEN
		"产品经理" 
		WHEN \'qd\' THEN
		"测试主管" 
		WHEN \'top\' THEN
		"高层管理" ELSE "未知" 
	END role 
FROM
	zt_user 
WHERE
	deleted = \'0\'', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1051, '宏观数据-人员工龄分布图', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"joinDate","name":"joinDate","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[]', 0, '{"count":{"name":"count","object":"user","field":"count","type":"string"},"joinDate":{"name":"joinDate","object":"user","field":"joinDate","type":"string"}}', '{"count":{"zh-cn":"\\u4eba\\u6570","zh-tw":"","en":"count","de":"","fr":""},"joinDate":{"zh-cn":"\\u5de5\\u9f84","zh-tw":"","en":"joinDate","de":"","fr":""}}', 'SELECT count(1) as count, "0-1年" as joinDate FROM zt_user WHERE deleted = \'0\' AND `join` > DATE_SUB(NOW(), INTERVAL 1 YEAR)
union
SELECT count(1) as count, "1-3年" as joinDate FROM zt_user WHERE deleted = \'0\' AND `join` > DATE_SUB(NOW(), INTERVAL 3 YEAR) AND `join` <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
union
SELECT count(1) as count, "3-5年" as joinDate FROM zt_user WHERE deleted = \'0\' AND `join` > DATE_SUB(NOW(), INTERVAL 5 YEAR) AND `join` <= DATE_SUB(NOW(), INTERVAL 3 YEAR)
union
SELECT count(1) as count, "5-10年" as joinDate FROM zt_user WHERE deleted = \'0\' AND `join` > DATE_SUB(NOW(), INTERVAL 10 YEAR) AND `join` <= DATE_SUB(NOW(), INTERVAL 5 YEAR)
union
SELECT count(1) as count, "10年以上" as joinDate FROM zt_user WHERE deleted = \'0\' AND `join` < DATE_SUB(NOW(), INTERVAL 10 YEAR) AND LEFT(`join`, 4) != \'0000\'
union                                                                                                                                                                                                                                                          
SELECT count(1) as count, "未知" as joinDate FROM zt_user WHERE deleted = \'0\' AND LEFT(`join`, 4) = \'0000\'', 'published', 0, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1055, '年度新增-一级项目集个数', 1, 'card', '45', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, name,
		YEAR ( openedDate ) AS `year` 
	FROM
		zt_project 
	WHERE
		`type` = \'program\' 
		AND deleted = \'0\' 
		AND grade = \'1\'  
	) t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1056, '年度新增-产品个数', 1, 'card', '47', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, name,
		YEAR ( createdDate ) AS `year` 
	FROM
		zt_product 
	WHERE
		deleted = \'0\' 
		AND shadow = \'0\' 
	) t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1057, '年度新增-需求个数', 1, 'card', '51', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( openedDate ) AS `year` FROM zt_story WHERE deleted = \'0\' ) AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1058, '年度新增-Bug个数', 1, 'card', '53', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( openedDate ) AS `year` FROM zt_bug WHERE deleted = \'0\' ) AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1059, '年度新增-计划个数', 1, 'card', '48', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( createdDate ) AS `year` FROM zt_productplan WHERE deleted = \'0\') AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1060, '年度新增-项目个数', 1, 'card', '46', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, name,
		YEAR ( openedDate ) AS `year` 
	FROM
		zt_project 
	WHERE
		`type` = \'project\' 
		AND deleted = \'0\' 
	) t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1061, '年度新增-执行个数', 1, 'card', '49', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT t1.`year`, t2.id, t2.name
FROM (SELECT DISTINCT YEAR(`date`) AS "year" FROM zt_action) AS t1
LEFT JOIN (SELECT id,name, YEAR(openedDate) AS `year` FROM zt_project WHERE `type` IN ( \'sprint\', \'stage\', \'kanban\' ) AND deleted = \'0\' AND multiple = \'1\') AS t2 ON t1.`year` = t2.`year` 
WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1062, '年度新增-任务数', 1, 'card', '52', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( openedDate ) AS `year` FROM zt_task WHERE deleted = \'0\') AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1063, '年度新增-文档个数', 1, 'card', '54', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( addedDate ) AS `year` FROM zt_doc WHERE deleted = \'0\') AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1064, '年度新增-发布个数', 1, 'card', '50', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( `date` ) AS `year` FROM zt_release WHERE deleted = \'0\') AS t2 ON t1.`year` = t2.`year`
WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1065, '年度新增-人员个数', 1, 'card', '56', '', '', '{"value": {"type": "agg", "field": "account", "agg": "count"}, "title": {"type": "text", "realname": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.account,
	t2.realname
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		account, realname,
		YEAR ( t112.`date` ) AS \'year\' 
	FROM
		zt_user AS t111
		LEFT JOIN zt_action t112 ON t111.id = t112.objectID 
		AND t112.objectType = \'user\' 
	WHERE
		t111.deleted = \'0\' 
		AND t112.action = \'created\' 
	) AS t2 ON t1.`year` = t2.`year` 	
 WHERE t2.account IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1066, '年度新增-完成项目数', 1, 'card', '46', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
t1.`year`,
t2.id,
t2.name
FROM
( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
LEFT JOIN (
SELECT
id, name,
YEAR ( closedDate ) AS `year` 
FROM
zt_project 
WHERE
`type` = \'project\' 
AND deleted = \'0\' 
) t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1067, '年度新增-完成执行数', 1, 'card', '49', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT t1.`year`, t2.id, t2.name
FROM (SELECT DISTINCT YEAR(date) AS "year" FROM zt_action) AS t1
LEFT JOIN (SELECT id, name, YEAR(closedDate) AS `year` FROM zt_project WHERE `type` IN ( \'sprint\', \'stage\', \'kanban\' ) AND deleted = \'0\' AND multiple = \'1\' AND status = \'closed\') AS t2 ON t1.`year` = t2.`year` 
WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1068, '年度新增-完成发布数', 1, 'card', '50', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( `date` ) AS `year` FROM zt_release WHERE deleted = \'0\') AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1069, '年度新增-完成需求数', 1, 'card', '51', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, title,
		YEAR ( closedDate ) AS `year` 
	FROM
		zt_story 
	WHERE
		deleted = \'0\' 
		AND closedReason = \'done\' 
		AND STATUS = \'closed\' 
	) AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1070, '年度新增-解决Bug数', 1, 'card', '53', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, title,
		YEAR ( closedDate ) AS `year` 
	FROM
		zt_bug 
	WHERE
		deleted = \'0\' 
		AND resolution = \'fixed\' 
		AND STATUS = \'closed\' 
	) AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1071, '年度新增-完成任务数', 1, 'card', '52', '', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		id, name,
		YEAR ( finishedDate ) AS `year` 
	FROM
		zt_task 
	WHERE
		deleted = \'0\' 
		AND STATUS = \'closed\' 
		AND closedReason = \'done\' 
	) AS t2 ON t1.`year` = t2.`year`	
 WHERE t2.id IS NOT NULL', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1072, '年度新增-投入工时数', 1, 'card', '57', '', '', '{"value": {"type": "value", "field": "consumed", "agg": "value"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '', 0, null, null, 'SELECT
	t1.`year`,
	IFNULL( t2.consumed, 0 ) AS consumed 
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT ROUND( SUM( consumed )) AS consumed, YEAR ( `date` ) AS "year" FROM zt_effort WHERE deleted = \'0\' GROUP BY `year`) AS t2 ON t1.`year` = t2.`year` ', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1073, '年度新增-项目集年度新增数据汇总表', 1, 'table', '64', '', '', '{"group":[],"column":[
{"field":"topProgram","valOrAgg":"value","name":"一级项目集"},
{"field":"product","valOrAgg":"value","name":"产品数"},
{"field":"plan","valOrAgg":"value","name":"计划数"},
{"field":"story","valOrAgg":"value","name":"需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"},
{"field":"release","valOrAgg":"value","name":"发布数"},
{"field":"doc","valOrAgg":"value","name":"文档数"}
],"filter":[]}', '', 0, null, null, 'SELECT
    t1.name AS topProgram,t1.id, t2.`year`, (SELECT COUNT(1) as product FROM zt_product WHERE deleted = \'0\' AND shadow = \'0\' AND YEAR(createdDate) = t2.`year` AND program = t1.id) as product, SUM(IFNULL(t4.plan, 0)) AS plan, SUM(IFNULL(t5.story, 0)) AS story, SUM(IFNULL(t6.bug, 0)) AS bug, SUM(IFNULL(t7.`release`, 0)) AS \'release\', SUM(IFNULL(t8.doc, 0)) AS doc
FROM zt_project AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as \'year\' FROM zt_action) as t2 ON 1 = 1 LEFT JOIN (SELECT id, program FROM zt_product WHERE deleted = \'0\') AS t3 ON t1.id = t3.program
LEFT JOIN (SELECT COUNT(1) as \'plan\', product, YEAR(createdDate) as \'year\' FROM zt_productplan WHERE deleted = \'0\' GROUP BY product,`year`) AS t4 on t3.id = t4.product AND t4.`year`= t2.`year` LEFT JOIN (SELECT COUNT(1) as \'story\', product, YEAR(openedDate) as \'year\' FROM zt_story WHERE deleted = \'0\' GROUP BY product,`year`) AS t5 on t3.id = t5.product AND t5.`year`= t2.`year` LEFT JOIN (SELECT COUNT(1) as \'bug\', product, YEAR(openedDate) as "year" FROM zt_bug WHERE deleted = \'0\' GROUP BY product,`year`) AS t6 on t3.id = t6.product AND t6.`year` = t2.`year` LEFT JOIN (SELECT COUNT(1) as \'release\', product, YEAR(`date`) as "year" FROM zt_release WHERE deleted = \'0\' GROUP BY product,`year`) AS t7 on t3.id = t7.product AND t7.`year` = t2.`year` LEFT JOIN (SELECT COUNT(1) as \'doc\', product, YEAR(addedDate) as "year" FROM zt_doc WHERE deleted = \'0\' GROUP BY product,`year`) AS t8 on t3.id = t8.product AND t8.`year` = t2.`year` WHERE t1.type = \'program\' AND t1.grade = 1 AND t1.deleted = \'0\'AND t1.status != \'closed\' GROUP BY topProgram,id,`year` ORDER BY `year`, topProgram', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1074, '年度新增-项目集年度完成数据概览', 1, 'table', '64', '', '', '{"group":[],"column":[
{"field":"topProgram","valOrAgg":"value","name":"一级项目集"},
{"field":"projectA","valOrAgg":"value","name":"项目数"},
{"field":"executionA","valOrAgg":"value","name":"执行数"},
{"field":"release","valOrAgg":"value","name":"发布数"},
{"field":"story","valOrAgg":"value","name":"需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"}
],"filter":[]}', '', 0, null, null, 'SELECT
    t1.name AS topProgram,t1.id,t2.`year`,
    SUM(IF((t3.`status` = \'closed\'), 1, 0)) as projectA,
    SUM(IFNULL(t4.execution, 0)) as executionA,
    SUM(IFNULL(t6.`release`, 0)) AS \'release\',
    SUM(IFNULL(t7.story, 0)) AS story,
    SUM(IFNULL(t8.bug, 0)) AS bug
FROM zt_project AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as "year" FROM zt_action) as t2 ON 1 = 1
LEFT JOIN zt_project AS t3 ON FIND_IN_SET(t1.id, t3.path) and t3.type = \'project\' AND YEAR(t3.closedDate) = t2.`year`
LEFT JOIN (SELECT COUNT(1) AS \'execution\', parent,YEAR(closedDate) AS `year` FROM zt_project WHERE type IN (\'sprint\', \'stage\', \'kanban\') AND `status` = \'closed\' GROUP BY `year`, parent) AS t4 ON t3.id = t4.parent AND t4.`year` = t2.`year`
LEFT JOIN (SELECT id,program FROM zt_product WHERE deleted = \'0\') AS t5 ON t1.id = t5.program
LEFT JOIN (SELECT COUNT(1) as \'release\', product, YEAR(`date`) as `year` FROM zt_release WHERE deleted = \'0\' GROUP BY product, `year`) AS t6 ON t5.id = t6.product AND t6.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'story\', product, YEAR(closedDate) as `year` FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' AND status = \'closed\' GROUP BY product, `year`) AS t7 on t5.id = t7.product AND t7.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'bug\', product, YEAR(resolvedDate) as `year` FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY product, `year`) AS t8 on t5.id = t8.product AND t8.`year` = t2.`year`
WHERE t1.type = \'program\' AND t1.grade = 1 AND t1.deleted = \'0\'
GROUP BY t1.name,t1.id,t2.`year`', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1075, '年度新增-产品年度新增数据汇总表', 1, 'table', '63', '', '', '{"group":[],"column":[
{"field":"name","valOrAgg":"value","name":"产品"},
{"field":"story","valOrAgg":"value","name":"需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"},
{"field":"plan","valOrAgg":"value","name":"计划数"},
{"field":"release","valOrAgg":"value","name":"发布数"}
],"filter":[]}', '', 0, null, null, 'SELECT
    t1.name,t1.id,t2.`year`,IF(YEAR(t1.createdDate) = t2.`year`, 1, 0) as newProduct,
    SUM(IFNULL(t3.story, 0)) AS story,
    SUM(IFNULL(t4.bug, 0)) AS bug,
    SUM(IFNULL(t5.`plan`, 0)) AS \'plan\',
    SUM(IFNULL(t6.`release`, 0)) AS \'release\'
FROM zt_product AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as "year" FROM zt_action) as t2 ON 1 = 1
LEFT JOIN (SELECT COUNT(1) as \'story\', product, YEAR(openedDate) as `year` FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' AND status = \'closed\' GROUP BY product, `year`) AS t3 on t1.id = t3.product AND t3.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'bug\', product, YEAR(openedDate) as `year` FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY product, `year`) AS t4 on t1.id = t4.product AND t4.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'plan\', product, YEAR(createdDate) AS "year" FROM zt_productplan WHERE deleted = \'0\' GROUP BY product,`year`) AS t5 on t1.id = t5.product AND t5.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'release\', product, YEAR(`date`) as `year` FROM zt_release WHERE deleted = \'0\' GROUP BY product, `year`) AS t6 ON t1.id = t6.product AND t6.`year` = t2.`year`
WHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\'
 GROUP BY t1.name,t1.id,t2.`year`,newProduct', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1076, '年度新增-产品年度完成数据汇总表', 1, 'table', '63', '', '', '{"group":[],"column":[
{"field":"name","valOrAgg":"value","name":"产品"},
{"field":"story","valOrAgg":"value","name":"需求数"},
{"field":"bug","valOrAgg":"value","name":"Bug数"},
{"field":"plan","valOrAgg":"value","name":"计划数"},
{"field":"release","valOrAgg":"value","name":"发布数"}
],"filter":[]}', '', 0, null, null, 'SELECT
    t1.name,t1.id,t2.`year`,IF(YEAR(t1.createdDate) = t2.`year`, 1, 0) as newProduct,
    SUM(IFNULL(t3.story, 0)) AS story,
    SUM(IFNULL(t4.bug, 0)) AS bug,
    SUM(IFNULL(t5.`plan`, 0)) AS \'plan\',
    SUM(IFNULL(t6.`release`, 0)) AS \'release\'
FROM zt_product AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as "year" FROM zt_action) as t2 ON 1 = 1
LEFT JOIN (SELECT COUNT(1) as \'story\', product, YEAR(closedDate) as `year` FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' AND status = \'closed\' GROUP BY product, `year`) AS t3 on t1.id = t3.product AND t3.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'bug\', product, YEAR(resolvedDate) as `year` FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY product, `year`) AS t4 on t1.id = t4.product AND t4.`year` = t2.`year`
LEFT JOIN (
    SELECT COUNT(DISTINCT t51.id) as \'plan\', t51.product, YEAR(t52.`date`) AS "year"
    FROM zt_productplan AS t51
    LEFT JOIN (SELECT objectID,objectType,action,MAX(`date`) as \'date\' FROM zt_action GROUP BY objectID,objectType, action) AS t52 ON t51.id = t52.objectID AND t52.objectType = \'productplan\'
    WHERE t51.deleted = \'0\' AND t51.closedReason = \'done\' AND t51.status = \'closed\' 
    AND t52.action = \'closed\'
    GROUP BY t51.product,`year`
) AS t5 on t1.id = t5.product AND t5.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as \'release\', product, YEAR(`date`) as `year` FROM zt_release WHERE deleted = \'0\' GROUP BY product, `year`) AS t6 ON t1.id = t6.product AND t6.`year` = t2.`year`
WHERE t1.deleted = \'0\' AND t1.status != \'closed\' AND t1.shadow = \'0\'
GROUP BY t1.name,t1.id,t2.`year`,newProduct', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1077, '年度新增-需求年度新增和完成趋势图', 1, 'line', '36', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"newStory","name":"\\u7ee7\\u7eed\\u6dfb\\u52a0\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"},{"field":"closedStory","name":"\\u9700\\u6c42\\uff1a%s \\u5df2\\u5173\\u95ed\\uff0c\\u5c06\\u4e0d\\u4f1a\\u88ab\\u5173\\u95ed\\u3002","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u5ea6"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"month":{"name":"month","object":"story","field":"month","type":"string"},"newStory":{"name":"\\u7ee7\\u7eed\\u6dfb\\u52a0\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"newStory","type":"string"},"closedStory":{"name":"\\u9700\\u6c42\\uff1a%s \\u5df2\\u5173\\u95ed\\uff0c\\u5c06\\u4e0d\\u4f1a\\u88ab\\u5173\\u95ed\\u3002","object":"story","field":"closedStory","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u5ea6","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u5ea6","zh-tw":"","en":"month","de":"","fr":""},"newStory":{"zh-cn":"\\u65b0\\u589e\\u9700\\u6c42\\u6570","zh-tw":"","en":"newStory","de":"","fr":""},"closedStory":{"zh-cn":"\\u5b8c\\u6210\\u9700\\u6c42\\u6570","zh-tw":"","en":"closedStory","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.story, 0) AS newStory, IFNULL(t3.story, 0) AS closedStory
FROM (SELECT DISTINCT Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS story FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1078, '年度新增-Bug年度新增和解决趋势图', 1, 'line', '44', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"newBug","name":"newBug","valOrAgg":"sum"},{"field":"fixedBug","name":"fixedBug","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u5ea6"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"month":{"name":"month","object":"bug","field":"month","type":"string"},"newBug":{"name":"newBug","object":"bug","field":"newBug","type":"string"},"fixedBug":{"name":"fixedBug","object":"bug","field":"fixedBug","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u5ea6","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u4efd","zh-tw":"","en":"month","de":"","fr":""},"newBug":{"zh-cn":"\\u65b0\\u589eBug\\u6570","zh-tw":"","en":"newBug","de":"","fr":""},"fixedBug":{"zh-cn":"\\u89e3\\u51b3Bug\\u6570","zh-tw":"","en":"fixedBug","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.bug, 0) AS newBug, IFNULL(t3.bug, 0) AS fixedBug
FROM (SELECT DISTINCT Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS bug FROM zt_bug WHERE deleted = \'0\' AND resolution = \'fixed\' AND status = \'closed\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1079, '年度新增-任务年度新增和完成趋势图', 1, 'line', '39', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"newTask","name":"newTask","valOrAgg":"sum"},{"field":"closedTask","name":"closedTask","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"task","field":"year","type":"number"},"month":{"name":"month","object":"task","field":"month","type":"string"},"newTask":{"name":"newTask","object":"task","field":"newTask","type":"string"},"closedTask":{"name":"closedTask","object":"task","field":"closedTask","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u4efd","zh-tw":"","en":"month","de":"","fr":""},"newTask":{"zh-cn":"\\u65b0\\u589e\\u4efb\\u52a1\\u6570","zh-tw":"","en":"newTask","de":"","fr":""},"closedTask":{"zh-cn":"\\u5b8c\\u6210\\u4efb\\u52a1\\u6570","zh-tw":"","en":"closedTask","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.task, 0) AS newTask, IFNULL(t3.task, 0) AS closedTask
FROM (SELECT DISTINCT Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS task FROM zt_task WHERE deleted = \'0\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS task FROM zt_task WHERE deleted = \'0\' AND status = \'closed\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1080, '年度新增-项目年度新增和完成趋势图', 1, 'line', '38', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"newProject","name":"newProject","valOrAgg":"sum"},{"field":"closedProject","name":"\\u5df2\\u5173\\u95ed\\u7684\\u9879\\u76ee","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"project","field":"year","type":"number"},"month":{"name":"month","object":"project","field":"month","type":"string"},"newProject":{"name":"newProject","object":"project","field":"newProject","type":"string"},"closedProject":{"name":"\\u5df2\\u5173\\u95ed\\u7684\\u9879\\u76ee","object":"project","field":"closedProject","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u4efd","zh-tw":"","en":"month","de":"","fr":""},"newProject":{"zh-cn":"\\u65b0\\u589e\\u9879\\u76ee\\u6570","zh-tw":"","en":"newProject","de":"","fr":""},"closedProject":{"zh-cn":"\\u5b8c\\u6210\\u9879\\u76ee\\u6570","zh-tw":"","en":"closedProject","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.project, 0) AS newProject, IFNULL(t3.project, 0) AS closedProject
FROM (SELECT DISTINCT Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS project FROM zt_project WHERE deleted = \'0\' AND type = \'project\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS project FROM zt_project WHERE deleted = \'0\' AND type = \'project\' AND status = \'closed\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1081, '年度新增-执行年度新增和完成趋势图', 1, 'line', '40', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"newExecution","name":"newExecution","valOrAgg":"sum"},{"field":"closedExecution","name":"closedExecution","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"project","field":"year","type":"number"},"month":{"name":"month","object":"project","field":"month","type":"string"},"newExecution":{"name":"newExecution","object":"project","field":"newExecution","type":"string"},"closedExecution":{"name":"closedExecution","object":"project","field":"closedExecution","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u4efd","zh-tw":"","en":"month","de":"","fr":""},"newExecution":{"zh-cn":"\\u65b0\\u589e\\u6267\\u884c\\u6570","zh-tw":"","en":"newExecution","de":"","fr":""},"closedExecution":{"zh-cn":"\\u5b8c\\u6210\\u6267\\u884c\\u6570","zh-tw":"","en":"closedExecution","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.execution, 0) AS newExecution, IFNULL(t3.execution, 0) AS closedExecution
FROM (SELECT DISTINCT YEAR(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(openedDate) AS `year`, MONTH(openedDate) AS `month`, COUNT(1) AS execution FROM zt_project WHERE deleted = \'0\' AND type IN (\'sprint\', \'stage\', \'kanban\') AND multiple = \'1\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
LEFT JOIN (SELECT YEAR(closedDate) AS `year`, MONTH(closedDate) AS `month`, COUNT(1) AS execution FROM zt_project WHERE deleted = \'0\' AND type IN (\'sprint\', \'stage\', \'kanban\') AND status = \'closed\' AND multiple = \'1\' GROUP BY `year`, `month`) AS t3 ON t1.year = t3.year AND t1.month = t3.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1082, '年度新增-产品发布次数年度趋势图', 1, 'line', '37', '', '', '[{"type":"line","xaxis":[{"field":"month","name":"month","group":""}],"yaxis":[{"field":"release","name":"release","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"release","field":"year","type":"number"},"month":{"name":"month","object":"release","field":"month","type":"string"},"release":{"name":"release","object":"release","field":"release","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"month":{"zh-cn":"\\u6708\\u4efd","zh-tw":"","en":"month","de":"","fr":""},"release":{"zh-cn":"\\u53d1\\u5e03\\u6b21\\u6570","zh-tw":"","en":"release","de":"","fr":""}}', 'SELECT t1.year, CONCAT(t1.month, "月") AS `month`, IFNULL(t2.release, 0) AS `release`
FROM (SELECT DISTINCT Year(date) AS `year`, MONTH(date) AS `month` FROM zt_action) AS t1
LEFT JOIN (SELECT YEAR(createdDate) AS `year`, MONTH(createdDate) AS `month`, COUNT(1) AS `release` FROM zt_release WHERE deleted = \'0\' GROUP BY `year`, `month`) AS t2 ON t1.year = t2.year AND t1.month = t2.month
ORDER BY `year`, t1.month', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1083, '年度新增-年度投入产出比', 1, 'line', '45', '', '', '{
  "xaxis":[{"field":"month","name":"月份","group":"value"}],
  "yaxis":[{"type":"value","field":"ratio","agg":"value","name":"投入产出比","valOrAgg":"value"},
{"type":"value","field":"story","agg":"value","name":"需求交付","valOrAgg":"value"},
{"type":"value","field":"consumed","agg":"value","name":"工时消耗","valOrAgg":"value"}
]}', '', 0, null, null, 'SELECT t1.`year`, CONCAT(t1.`month`, "月") AS `month`, IFNULL(t2.story, 0) AS story, IFNULL(t3.consumed, 0) AS consumed, ROUND(IF(IFNULL(t3.consumed, 0) = 0, 0, IFNULL(t2.story, 0) / IFNULL(t3.consumed, 0)), 2) AS ratio
FROM (SELECT YEAR(`date`) AS \'year\', MONTH(`date`) AS \'month\' FROM zt_action GROUP BY `year`,`month`) AS t1
LEFT JOIN (SELECT ROUND(SUM(estimate)) AS story, YEAR(`closedDate`) AS \'year\', MONTH(`closedDate`) AS \'month\' FROM zt_story WHERE deleted = \'0\' AND closedReason = \'done\' AND status = \'closed\' GROUP BY `year`,`month`) AS t2 ON t1.`year` = t2.`year` AND t1.`month` = t2.`month`
LEFT JOIN (SELECT ROUND(SUM(consumed)) as consumed, YEAR(`date`) as \'year\', MONTH(`date`) AS \'month\' FROM zt_effort WHERE deleted = \'0\' GROUP BY `year`,`month`) AS t3 ON t1.`year` = t3.`year` AND t1.`month` = t3.`month`', 'published', 1, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1085, '年度排行-项目集-预算投入榜', 1, 'cluBarY', '41', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"budget","name":"\\u9884\\u7b97","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"project","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"budget":{"name":"\\u9884\\u7b97","object":"project","field":"budget","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"budget":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u9884\\u7b97","zh-tw":"","en":"budget","de":"","fr":""}}', 'SELECT 
  YEAR(t2.openedDate) AS `year`, 
  t1.id,
  t1.name AS program, 
  ROUND(
    SUM(
      IFNULL(t2.budget, 0)
    ) / 10000, 
    2
  ) AS budget 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_project AS t2 ON FIND_IN_SET(t1.id, t2.path) 
  AND t2.deleted = \'0\' 
  AND t2.type = \'project\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  budget DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1086, '年度排行-项目集-人员投入榜', 1, 'cluBarY', '41', '', '', '[{"type":"cluBarY","xaxis":[{"field":"setName","name":"setName","group":""}],"yaxis":[{"field":"number","name":"number","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"user","field":"year","type":"number"},"number":{"name":"number","object":"user","field":"number","type":"string"},"setName":{"name":"setName","object":"user","field":"setName","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"number":{"zh-cn":"\\u4eba\\u5458\\u6570\\u91cf","zh-tw":"","en":"number","de":"","fr":""},"setName":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"setName","de":"","fr":""}}', 'SELECT tt.join as `year`, count(1) as number, tt.setName from (
select 
YEAR(t1.join) as `join`, t4.name as setName 
from zt_team t1 
RIGHT JOIN zt_project t2 on t2.id = t1.root
LEFT JOIN zt_project t4 on FIND_IN_SET(t4.id,t2.path) and t4.grade = 1
RIGHT JOIN zt_user t3 on t3.account = t1.account
WHERE t1.type = \'project\'
AND t2.deleted = \'0\'
AND t3.deleted = \'0\'
) tt
GROUP BY tt.setName, tt.join
ORDER BY tt.join, number desc, tt.setName', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1087, '年度排行-项目集-工时消耗榜', 1, 'cluBarY', '41', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"consumed","name":"\\u603b\\u8ba1\\u6d88\\u8017","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"effort","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"consumed":{"name":"\\u603b\\u8ba1\\u6d88\\u8017","object":"task","field":"consumed","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"consumed":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u603b\\u8ba1\\u6d88\\u8017","zh-tw":"","en":"program","de":"","fr":""}}', 'SELECT 
  YEAR(t5.date) AS `year`, 
  t1.id,
  t1.name AS program, 
  ROUND(
    SUM(t5.consumed), 
    2
  ) AS consumed 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_project AS t2 ON FIND_IN_SET(t1.id, t2.path) 
  AND t2.deleted = \'0\' 
  AND t2.type = \'project\' 
  LEFT JOIN zt_project AS t3 ON t2.id = t3.parent 
  AND t3.deleted = \'0\' 
  AND t3.type IN (\'sprint\', \'stage\', \'kanban\') 
  LEFT JOIN zt_task AS t4 ON t3.id = t4.execution 
  AND t4.deleted = \'0\' 
  AND t4.status != \'cancel\' 
  LEFT JOIN zt_effort AS t5 ON t4.id = t5.objectID 
  AND t5.deleted = \'0\' 
  AND t5.objectType = \'task\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t5.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  consumed DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1088, '年度排行-项目集-新增需求条目榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"story":{"zh-cn":"\\u65b0\\u589e\\u7814\\u53d1\\u9700\\u6c42\\u8ba1\\u6570","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT 
  YEAR(t3.openedDate) AS `year`,
  t1.id, 
  t1.name AS program, 
  COUNT(1) AS story 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_story AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1089, '年度排行-项目集-新增需求规模榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"story":{"zh-cn":"\\u65b0\\u589e\\u7814\\u53d1\\u9700\\u6c42\\u89c4\\u6a21","zh-tw":"","en":"","de":"","fr":""}}', 'SELECT 
  YEAR(t3.openedDate) AS `year`, 
  t1.id,
  t1.name AS program, 
  ROUND(
    SUM(t3.estimate), 
    2
  ) AS story 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_story AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`,
  id, 
  program 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1090, '年度排行-项目集-新增Bug条目榜', 1, 'cluBarY', '44', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"bug","name":"Bug\\u5217\\u8868","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"bug":{"name":"Bug\\u5217\\u8868","object":"project","field":"bug","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"bug":{"zh-cn":"\\u65b0\\u589eBug\\u8ba1\\u6570","zh-tw":"","en":"bug","de":"","fr":""}}', 'SELECT 
  YEAR(t3.openedDate) AS `year`,
  t1.id, 
  t1.name AS program, 
  COUNT(1) AS bug 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_bug AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  bug DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1091, '年度排行-项目集-完成需求条目榜', 1, 'cluBarY', '43', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"story":{"zh-cn":"\\u5b8c\\u6210\\u7814\\u53d1\\u9700\\u6c42\\u6c42\\u548c","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT 
  YEAR(t3.closedDate) AS `year`, 
  t1.id,
  t1.name AS program, 
  COUNT(1) AS story 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_story AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
  AND t3.closedReason = \'done\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`,
  id,  
  program 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1092, '年度排行-项目集-完成需求规模榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"story":{"zh-cn":"\\u7814\\u53d1\\u9700\\u6c42\\u9884\\u8ba1\\u5de5\\u65f6\\u6c42\\u548c","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT 
  YEAR(t3.closedDate) AS `year`, 
  t1.id,
  t1.name AS program, 
  ROUND(
    SUM(t3.estimate), 
    2
  ) AS story 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_story AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
  AND t3.closedReason = \'done\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1093, '年度排行-项目集-修复Bug条目榜', 1, 'cluBarY', '44', '', '', '[{"type":"cluBarY","xaxis":[{"field":"program","name":"program","group":""}],"yaxis":[{"field":"bug","name":"Bug\\u5217\\u8868","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"program":{"name":"program","object":"zt_project","field":"program","type":"string"},"bug":{"name":"Bug\\u5217\\u8868","object":"project","field":"bug","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"program":{"zh-cn":"\\u9879\\u76ee\\u96c6\\u540d\\u79f0","zh-tw":"","en":"program","de":"","fr":""},"bug":{"zh-cn":"Bug\\u8ba1\\u6570","zh-tw":"","en":"bug","de":"","fr":""}}', 'SELECT 
  YEAR(t3.closedDate) AS `year`,
  t1.id,  
  t1.name AS program, 
  COUNT(1) AS bug 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_product AS t2 ON t1.id = t2.program 
  AND t2.deleted = \'0\' 
  LEFT JOIN zt_bug AS t3 ON t2.id = t3.product 
  AND t3.deleted = \'0\' 
  AND t3.resolution = \'fixed\' 
  AND t3.status = \'closed\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'program\' 
  AND t1.grade = 1 
  AND t3.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  program 
ORDER BY 
  `year`, 
  bug DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1094, '年度排行-项目-工期榜', 1, 'cluBarY', '42', '', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\\u9879\\u76ee\\u540d\\u79f0","group":""}],"yaxis":[{"field":"duration","name":"duration","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"project","field":"year","type":"number"},"id":{"name":"\\u7f16\\u53f7","object":"project","field":"id","type":"number"},"name":{"name":"\\u9879\\u76ee\\u540d\\u79f0","object":"project","field":"name","type":"string"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"},"realBegan":{"name":"\\u5b9e\\u9645\\u5f00\\u59cb\\u65e5\\u671f","object":"project","field":"realBegan","type":"date"},"realEnd":{"name":"\\u5b9e\\u9645\\u5b8c\\u6210\\u65e5\\u671f","object":"project","field":"realEnd","type":"date"},"duration":{"name":"duration","object":"project","field":"duration","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""},"realBegan":{"zh-cn":"\\u5b9e\\u9645\\u5f00\\u59cb\\u65e5\\u671f","zh-tw":"","en":"realBegan","de":"","fr":""},"realEnd":{"zh-cn":"\\u5b9e\\u9645\\u5b8c\\u6210\\u65e5\\u671f","zh-tw":"","en":"realEnd","de":"","fr":""},"duration":{"zh-cn":"\\u5de5\\u671f","zh-tw":"","en":"duration","de":"","fr":""}}', 'SELECT `year`, id,name,status,realBegan,realEnd,IF(status = \'closed\', DATEDIFF(realEnd, realBegan), DATEDIFF(NOW(),realBegan)) as duration
FROM (SELECT DISTINCT YEAR(`date`) as \'year\' FROM zt_action) AS t1
LEFT JOIN zt_project AS t2 ON 1 = 1 WHERE deleted = \'0\' AND type = \'project\' AND YEAR(realBegan) <= `year` AND LEFT(realBegan, 4) != \'0000\' AND (status =\'doing\' OR (status = \'suspended\' AND YEAR(suspendedDate) >= `year`) OR (status = \'closed\' AND YEAR(realEnd) >= `year`)) HAVING 1=1 ORDER BY `year`, duration desc', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1096, '年度排行-项目-工期偏差榜', 1, 'cluBarY', '42', '', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\\u9879\\u76ee\\u540d\\u79f0","group":""}],"yaxis":[{"field":"duration","name":"duration","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"project","field":"year","type":"number"},"id":{"name":"\\u7f16\\u53f7","object":"action","field":"id","type":"number"},"name":{"name":"\\u9879\\u76ee\\u540d\\u79f0","object":"project","field":"name","type":"string"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"},"begin":{"name":"\\u8ba1\\u5212\\u5f00\\u59cb","object":"project","field":"begin","type":"date"},"end":{"name":"\\u8ba1\\u5212\\u5b8c\\u6210","object":"project","field":"end","type":"date"},"realBegan":{"name":"\\u5b9e\\u9645\\u5f00\\u59cb\\u65e5\\u671f","object":"project","field":"realBegan","type":"date"},"realEnd":{"name":"\\u5b9e\\u9645\\u5b8c\\u6210\\u65e5\\u671f","object":"project","field":"realEnd","type":"date"},"duration":{"name":"duration","object":"project","field":"duration","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""},"begin":{"zh-cn":"\\u8ba1\\u5212\\u5f00\\u59cb","zh-tw":"","en":"begin","de":"","fr":""},"end":{"zh-cn":"\\u8ba1\\u5212\\u5b8c\\u6210","zh-tw":"","en":"end","de":"","fr":""},"realBegan":{"zh-cn":"\\u5b9e\\u9645\\u5f00\\u59cb\\u65e5\\u671f","zh-tw":"","en":"realBegan","de":"","fr":""},"realEnd":{"zh-cn":"\\u5b9e\\u9645\\u5b8c\\u6210\\u65e5\\u671f","zh-tw":"","en":"realEnd","de":"","fr":""},"duration":{"zh-cn":"\\u5de5\\u671f\\u504f\\u5dee","zh-tw":"","en":"duration","de":"","fr":""}}', 'SELECT `year`, id,name,status,`begin`,`end`,realBegan,realEnd,
ROUND((IF(LEFT(realEnd,4) != \'0000\', DATEDIFF(realEnd, realBegan), DATEDIFF(NOW(),realBegan)) - DATEDIFF(`end`, `begin`)) / DATEDIFF(`end`,`begin`) * 100) as duration
FROM (SELECT DISTINCT YEAR(`date`) as \'year\' FROM zt_action) AS t1
LEFT JOIN zt_project AS t2 ON 1 = 1 
WHERE deleted = \'0\' AND type = \'project\'
AND YEAR(realBegan) <= `year` AND LEFT(realBegan, 4) != \'0000\'
AND (YEAR(realEnd) >= `year` OR LEFT(realEnd, 4) = \'0000\') AND YEAR(`end`) != \'2059\'
HAVING 1=1
ORDER BY duration ASC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1097, '年度排行-项目-人员投入榜', 1, 'cluBarY', '41', '', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\\u9879\\u76ee\\u540d\\u79f0","group":""}],"yaxis":[{"field":"number","name":"number","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"user","field":"year","type":"number"},"number":{"name":"number","object":"user","field":"number","type":"string"},"name":{"name":"\\u9879\\u76ee\\u540d\\u79f0","object":"project","field":"name","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"number":{"zh-cn":"\\u4eba\\u5458\\u4e2a\\u6570","zh-tw":"","en":"number","de":"","fr":""},"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""}}', 'SELECT tt.join as `year`, count(1) as number, tt.name from (
select 
t2.name, YEAR(t1.join) as `join`
from zt_team t1 
RIGHT JOIN zt_project t2 on t2.id = t1.root
RIGHT JOIN zt_user t3 on t3.account = t1.account
WHERE t1.type = \'project\'
AND t2.deleted = \'0\'
) tt
GROUP BY tt.`name`, tt.join
ORDER BY tt.join, number desc, tt.name', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1098, '年度排行-项目-工时消耗榜', 1, 'cluBarY', '41', '', '', '[{"type":"cluBarY","xaxis":[{"field":"project","name":"project","group":""}],"yaxis":[{"field":"consumed","name":"\\u603b\\u8ba1\\u6d88\\u8017","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"effort","field":"year","type":"number"},"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"project":{"name":"project","object":"zt_project","field":"project","type":"string"},"consumed":{"name":"\\u603b\\u8ba1\\u6d88\\u8017","object":"task","field":"consumed","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"project":{"zh-cn":"\\u9879\\u76ee","zh-tw":"","en":"project","de":"","fr":""},"consumed":{"zh-cn":"\\u4efb\\u52a1\\u603b\\u8ba1\\u6d88\\u8017","zh-tw":"","en":"consumed","de":"","fr":""}}', 'SELECT 
  YEAR(t4.date) AS `year`,
  t1.id,  
  t1.name AS project, 
  ROUND(
    SUM(t4.consumed), 
    2
  ) AS consumed 
FROM 
  zt_project AS t1 
  LEFT JOIN zt_project AS t2 ON t1.id = t2.parent 
  AND t2.deleted = \'0\' 
  AND t2.type IN (\'sprint\', \'stage\', \'kanban\') 
  LEFT JOIN zt_task AS t3 ON t2.id = t3.execution 
  AND t3.deleted = \'0\' 
  AND t3.status != \'cancel\' 
  LEFT JOIN zt_effort AS t4 ON t3.id = t4.objectID 
  AND t4.deleted = \'0\' 
  AND t4.objectType = \'task\' 
WHERE 
  t1.deleted = \'0\' 
  AND t1.type = \'project\' 
  AND t4.id IS NOT NULL 
GROUP BY 
  `year`, 
  id,
  project 
ORDER BY 
  `year`, 
  consumed DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1099, '年度排行-项目-完成需求条目榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"project","name":"\\u6240\\u5c5e\\u9879\\u76ee","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42\\u5217\\u8868","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"project":{"name":"\\u6240\\u5c5e\\u9879\\u76ee","object":"project","field":"project","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42\\u5217\\u8868","object":"projectstory","field":"story","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"project":{"zh-cn":"\\u6240\\u5c5e\\u9879\\u76ee","zh-tw":"","en":"project","de":"","fr":""},"story":{"zh-cn":"\\u7814\\u53d1\\u9700\\u6c42\\u8ba1\\u6570","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT 
  YEAR(t1.closedDate) AS `year`, 
  t1.id, 
  t1.project, 
  COUNT(1) AS story 
FROM 
  (
    SELECT 
      DISTINCT t1.id, 
      t1.name AS project, 
      t4.id AS story, 
      t4.closedDate 
    FROM 
      zt_project AS t1 
      LEFT JOIN zt_project AS t2 ON t1.id = t2.parent 
      AND t2.deleted = \'0\' 
      AND t2.type IN (\'sprint\', \'stage\', \'kanban\') 
      LEFT JOIN zt_projectstory AS t3 ON t2.id = t3.project 
      LEFT JOIN zt_story AS t4 ON t3.story = t4.id 
      AND t4.deleted = \'0\' 
      AND t4.closedReason = \'done\' 
    WHERE 
      t1.deleted = \'0\' 
      AND t1.type = \'project\' 
      AND t4.id IS NOT NULL
  ) AS t1 
GROUP BY 
  `year`, 
  id, 
  project 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1100, '年度排行-项目-完成需求规模榜', 1, 'cluBarY', '43', '', '', '[{"type":"cluBarY","xaxis":[{"field":"project","name":"\\u6240\\u5c5e\\u9879\\u76ee","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42\\u5217\\u8868","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"project":{"name":"\\u6240\\u5c5e\\u9879\\u76ee","object":"project","field":"project","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42\\u5217\\u8868","object":"projectstory","field":"story","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"project":{"zh-cn":"\\u6240\\u5c5e\\u9879\\u76ee","zh-tw":"","en":"project","de":"","fr":""},"story":{"zh-cn":"\\u9700\\u6c42\\u9884\\u8ba1\\u5de5\\u65f6","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT 
  YEAR(t1.closedDate) AS `year`, 
  t1.id, 
  t1.project, 
  ROUND(
    SUM(t1.estimate), 
    2
  ) AS story 
FROM 
  (
    SELECT 
      DISTINCT t1.id, 
      t1.name AS project, 
      t4.id AS story, 
      t4.estimate, 
      t4.closedDate 
    FROM 
      zt_project AS t1 
      LEFT JOIN zt_project AS t2 ON t1.id = t2.parent 
      AND t2.deleted = \'0\' 
      AND t2.type IN (\'sprint\', \'stage\', \'kanban\') 
      LEFT JOIN zt_projectstory AS t3 ON t2.id = t3.project 
      LEFT JOIN zt_story AS t4 ON t3.story = t4.id 
      AND t4.deleted = \'0\' 
      AND t4.closedReason = \'done\' 
    WHERE 
      t1.deleted = \'0\' 
      AND t1.type = \'project\' 
      AND t4.id IS NOT NULL
  ) AS t1 
GROUP BY 
  `year`, 
  id, 
  project 
ORDER BY 
  `year`, 
  story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1101, '年度排行-产品-新增需求条目榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"product","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_product","field":"id","type":"number"},"product":{"name":"product","object":"zt_product","field":"product","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u4ea7\\u54c1\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"product":{"zh-cn":"\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"story":{"zh-cn":"\\u7814\\u53d1\\u9700\\u6c42\\u8ba1\\u6570","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT YEAR(t2.openedDate) AS `year`, t1.id,  t1.name AS product, COUNT(1) AS story
FROM zt_product AS t1
LEFT JOIN zt_story AS t2 ON t1.id = t2.product AND t2.deleted = \'0\'
WHERE t1.deleted = \'0\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\' AND t2.id IS NOT NULL
GROUP BY `year`, id, product
ORDER BY `year`, story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1102, '年度排行-产品-完成需求规模榜', 1, 'cluBarY', '36', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"product","group":""}],"yaxis":[{"field":"story","name":"\\u7814\\u53d1\\u9700\\u6c42","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"id":{"name":"id","object":"zt_product","field":"id","type":"number"},"product":{"name":"product","object":"zt_product","field":"product","type":"string"},"story":{"name":"\\u7814\\u53d1\\u9700\\u6c42","object":"story","field":"story","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u4ea7\\u54c1\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"product":{"zh-cn":"\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"story":{"zh-cn":"\\u7814\\u53d1\\u9700\\u6c42\\u9884\\u8ba1\\u5de5\\u65f6\\u6c42\\u548c","zh-tw":"","en":"story","de":"","fr":""}}', 'SELECT YEAR(t2.closedDate) AS `year`, t1.id, t1.name AS product, ROUND(SUM(t2.estimate), 1) AS story
FROM zt_product AS t1
LEFT JOIN zt_story AS t2 ON t1.id = t2.product AND t2.deleted = \'0\' AND t2.closedReason = \'done\'
WHERE t1.deleted = \'0\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\' AND t2.id IS NOT NULL
GROUP BY `year`, id, product
ORDER BY `year`, story DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1103, '年度排行-产品-新增Bug条目榜', 1, 'cluBarY', '44', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"product","group":""}],"yaxis":[{"field":"bug","name":"bug","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"id":{"name":"id","object":"zt_product","field":"id","type":"number"},"product":{"name":"product","object":"zt_product","field":"product","type":"string"},"bug":{"name":"bug","object":"bug","field":"bug","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u4ea7\\u54c1\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"product":{"zh-cn":"\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"bug":{"zh-cn":"Bug\\u8ba1\\u6570","zh-tw":"","en":"bug","de":"","fr":""}}', 'SELECT YEAR(t2.openedDate) AS `year`, t1.id,  t1.name AS product, COUNT(1) AS bug
FROM zt_product AS t1
LEFT JOIN zt_bug AS t2 ON t1.id = t2.product AND t2.deleted = \'0\'
WHERE t1.deleted = \'0\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\' AND t2.id IS NOT NULL
GROUP BY `year`, id, product
ORDER BY `year`, bug DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1104, '年度排行-产品-修复Bug条目榜', 1, 'cluBarY', '44', '', '', '[{"type":"cluBarY","xaxis":[{"field":"product","name":"product","group":""}],"yaxis":[{"field":"bug","name":"bug","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"id":{"name":"id","object":"zt_product","field":"id","type":"number"},"product":{"name":"product","object":"zt_product","field":"product","type":"string"},"bug":{"name":"bug","object":"bug","field":"bug","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"id":{"zh-cn":"\\u4ea7\\u54c1\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"product":{"zh-cn":"\\u4ea7\\u54c1","zh-tw":"","en":"product","de":"","fr":""},"bug":{"zh-cn":"Bug\\u8ba1\\u6570","zh-tw":"","en":"bug","de":"","fr":""}}', 'SELECT YEAR(t2.closedDate) AS `year`, t1.id, t1.name AS product, COUNT(1) AS bug
FROM zt_product AS t1
LEFT JOIN zt_bug AS t2 ON t1.id = t2.product AND t2.deleted = \'0\' AND t2.resolution = \'fixed\' AND t2.status = \'closed\'
WHERE t1.deleted = \'0\' AND t1.shadow = \'0\' AND t1.vision = \'rnd\' AND t2.id IS NOT NULL
GROUP BY `year`, id, product
ORDER BY `year`, bug DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1105, '年度排行-个人-创建需求条目榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"story","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_user","field":"realname","type":"string"},"count":{"name":"count","object":"story","field":"count","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"count":{"zh-cn":"\\u8ba1\\u6570","zh-tw":"","en":"count","de":"","fr":""}}', 'SELECT 
YEAR(t3.openedDate) AS `year`,t2.realname,count(1) AS count
FROM zt_action AS t1 RIGHT JOIN zt_user AS t2 ON t1.actor=t2.account LEFT JOIN zt_story AS t3 ON t1.objectID=t3.id
WHERE t1.objectType=\'story\' AND t1.action=\'opened\' AND t3.deleted=\'0\'
GROUP BY `year`,t2.account ORDER BY `year`,count DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1106, '年度排行-个人-创建用例条目榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"testcase","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_user","field":"realname","type":"string"},"count":{"name":"count","object":"testcase","field":"count","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"count":{"zh-cn":"\\u8ba1\\u6570","zh-tw":"","en":"count","de":"","fr":""}}', 'SELECT 
YEAR(t3.openedDate) AS `year`,t2.realname,count(1) AS count
FROM zt_action AS t1 RIGHT JOIN zt_user AS t2 ON t1.actor=t2.account LEFT JOIN zt_case AS t3 ON t1.objectID=t3.id
WHERE t1.objectType=\'case\' AND t1.action=\'opened\' AND t3.deleted=\'0\'
GROUP BY `year`,t2.account ORDER BY `year`,count DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1107, '年度排行-个人-创建Bug条目榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_user","field":"realname","type":"string"},"count":{"name":"count","object":"bug","field":"count","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"count":{"zh-cn":"\\u8ba1\\u6570","zh-tw":"","en":"count","de":"","fr":""}}', 'SELECT 
YEAR(t3.openedDate) AS `year`,t2.realname,count(1) AS count
FROM zt_action AS t1 RIGHT JOIN zt_user AS t2 ON t1.actor=t2.account LEFT JOIN zt_bug AS t3 ON t1.objectID=t3.id
WHERE t1.objectType=\'bug\' AND t1.action=\'opened\' AND t3.deleted=\'0\'
GROUP BY `year`,t2.account ORDER BY `year`,count DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1108, '年度排行-个人-修复Bug条目榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"bug","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_user","field":"realname","type":"string"},"count":{"name":"count","object":"bug","field":"count","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"count":{"zh-cn":"\\u8ba1\\u6570","zh-tw":"","en":"count","de":"","fr":""}}', 'SELECT 
YEAR(t3.openedDate) AS `year`,t2.realname,count(DISTINCT t3.id) AS count
FROM zt_action AS t1 RIGHT JOIN zt_user AS t2 ON t1.actor=t2.account LEFT JOIN zt_bug AS t3 ON t1.objectID=t3.id
WHERE t1.objectType=\'bug\' AND t1.action=\'resolved\' AND t3.deleted=\'0\'
GROUP BY `year`,t2.account ORDER BY `year`,count DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1109, '年度排行-个人-工时消耗榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"consumed","name":"\\u8017\\u65f6","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"user","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_user","field":"realname","type":"string"},"consumed":{"name":"\\u8017\\u65f6","object":"effort","field":"consumed","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"consumed":{"zh-cn":"\\u8017\\u65f6","zh-tw":"","en":"consumed","de":"","fr":""}}', 'SELECT YEAR(t1.date) AS `year`, t2.realname, ROUND(SUM(t1.consumed),1) AS consumed
FROM zt_effort AS t1 LEFT JOIN zt_user AS t2 ON t1.account = t2.account
WHERE t1.deleted = \'0\' AND t2.deleted = \'0\'
GROUP BY `year`, realname
ORDER BY `year`, consumed DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (1110, '年度排行-个人-禅道操作次数榜', 1, 'cluBarY', '56', '', '', '[{"type":"cluBarY","xaxis":[{"field":"realname","name":"realname","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 0, '{"year":{"name":"year","object":"user","field":"year","type":"number"},"realname":{"name":"realname","object":"zt_action","field":"realname","type":"string"},"count":{"name":"count","object":"user","field":"count","type":"string"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"year","de":"","fr":""},"realname":{"zh-cn":"\\u59d3\\u540d","zh-tw":"","en":"realname","de":"","fr":""},"count":{"zh-cn":"\\u8ba1\\u6570","zh-tw":"","en":"count","de":"","fr":""}}', 'SELECT YEAR(t1.date) AS `year`,IFNULL(t2.realname,t1.actor) AS realname,count(1) AS count
FROM zt_action t1 LEFT JOIN zt_user AS t2 ON t1.actor=t2.account
GROUP BY `year`,t1.actor
ORDER BY `year`, `count` DESC', 'published', 0, '', 'system', null, '', null, 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10000, '年度完成项目-完成项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_project WHERE type=\'project\' AND status=\'closed\' AND deleted=\'0\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10001, '年度完成项目-按时完成项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) as \'year\' FROM (SELECT id, begin, end, IF(left(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'project\' AND status=\'closed\') t1 WHERE t1.realEnd<=end GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10002, '年度完成项目-延期完成项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM (SELECT id, begin, end, IF(left(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'project\' AND status=\'closed\') t1 WHERE t1.realEnd>end GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10003, '年度完成项目-完成需求条目数', 2, 'card', '75', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_story WHERE deleted=\'0\' AND status=\'closed\' AND closedReason=\'done\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10004, '年度完成项目-完成需求规模数', 2, 'card', '75', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT ROUND(SUM(estimate),2) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_story WHERE deleted=\'0\' AND status=\'closed\' AND closedReason=\'done\'  GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10005, '年度完成项目-完成发布数', 2, 'card', '74', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`date`) AS \'year\' FROM zt_release WHERE deleted=\'0\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10006, '年度完成项目-解决bug数', 2, 'card', '77', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT SUM(CASE WHEN resolution=\'fixed\' THEN 1 ELSE 0 END) AS number,YEAR(`resolvedDate`) AS \'year\' FROM zt_bug WHERE deleted=\'0\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10007, '年度完成项目-完成执行数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_project WHERE type=\'sprint\' AND status=\'closed\' AND deleted=\'0\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10008, '年度完成项目-按时完成执行数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM (SELECT id, begin, end, IF(LEFT(realEnd,4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'sprint\' AND status=\'closed\') t1 WHERE t1.realEnd<=end GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10009, '年度完成项目-延期完成执行数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM (SELECT id, begin, end, IF(LEFT(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'sprint\' AND status=\'closed\') t1 WHERE t1.realEnd>end GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10010, '年度完成项目-完成任务条目数', 2, 'card', '76', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT COUNT(1) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_task WHERE deleted=\'0\' AND status=\'closed\' AND closedReason=\'done\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10011, '年度完成项目-完成任务预计工时数', 2, 'card', '78', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT ROUND(SUM(estimate),2) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_task WHERE deleted=\'0\' AND status=\'closed\' AND closedReason=\'done\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10012, '年度完成项目-完成任务消耗工时数', 2, 'card', '78', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT ROUND(SUM(consumed),2) AS number,YEAR(`closedDate`) AS \'year\' FROM zt_task WHERE deleted=\'0\' AND status=\'closed\' AND closedReason=\'done\' GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10013, '年度完成项目-投入的总人天', 2, 'card', '78', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""},
"type": "value"
}', '[]', 0, '', '', 'SELECT SUM(t2.people*DATEDIFF(t1.realEnd,t1.realBegan)) AS number,YEAR(`closedDate`) AS \'year\' FROM (SELECT id, realBegan, IF(LEFT(realEnd, 4) = \'0000\', closedDate, realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' AND status=\'closed\' AND type=\'project\' AND realBegan != \'0000-00-00\') t1 LEFT JOIN (SELECT root, COUNT(id) people FROM zt_team WHERE type=\'project\' GROUP BY `root`) t2 ON t1.id=t2.root GROUP BY `year`', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10014, '年度完成项目-项目按期完成率', 2, 'piecircle', '71', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT t1.id,IF(t1.realEnd<=t1.end,\'done\',\'undone\') AS \'status\', YEAR(`closedDate`) AS \'year\' FROM(SELECT id, begin, end, IF(LEFT(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'project\' AND status=\'closed\') t1', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10015, '年度完成项目-执行按期完成率', 2, 'piecircle', '71', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT IF(t1.realEnd<=t1.end,\'done\',\'undone\') AS \'status\', YEAR(`closedDate`) AS \'year\' FROM (SELECT id, begin, end, IF(LEFT(realEnd,4)=\'0000\',LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' and type=\'sprint\' and status=\'closed\') t1', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10016, '年度完成项目-项目延期率', 2, 'piecircle', '71', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT IF(t1.realEnd>t1.end ,\'done\',\'undone\') AS \'status\', YEAR(`closedDate`) AS \'year\' FROM (SELECT id, begin, end, IF(LEFT(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' AND type=\'project\' AND status=\'closed\') t1', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10017, '年度完成项目-执行延期率', 2, 'piecircle', '71', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT IF(t1.realEnd>t1.end,\'done\',\'undone\') AS \'status\', YEAR(`closedDate`) AS \'year\' FROM(SELECT id, begin, end, IF(LEFT(realEnd, 4) = \'0000\', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted=\'0\' and type=\'sprint\' and status=\'closed\') t1', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10018, '年度完成项目-完成项目工期偏差条形图', 2, 'cluBarY', '71', '0', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\\u9879\\u76ee\\u540d\\u79f0","group":""}],"yaxis":[{"field":"daterate","name":"daterate","valOrAgg":"max"}]}]', '[{"field":"closedDate","type":"date","name":"\\u5173\\u95ed\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"name":{"name":"\\u9879\\u76ee\\u540d\\u79f0","object":"project","field":"name","type":"string"},"closedDate":{"name":"\\u5173\\u95ed\\u65e5\\u671f","object":"project","field":"closedDate","type":"date"},"daterate":{"name":"daterate","object":"project","field":"daterate","type":"number"}}', '{"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"closedDate":{"zh-cn":"\\u5173\\u95ed\\u65e5\\u671f","zh-tw":"","en":"closedDate","de":"","fr":""},"daterate":{"zh-cn":"\\u5de5\\u671f\\u504f\\u5dee\\u7387","zh-tw":"","en":"daterate","de":"","fr":""}}', 'select
t1.name,
t1.closedDate,
round(t1.realduration-t1.planduration)/t1.planduration as daterate
from(
select
name,
id,
closedDate,
begin,
end,
datediff(`end`,`begin`) planduration,
realBegan,
realEnd,
ifnull(if(left(realEnd,4) != \'0000\',datediff(`realEnd`,`realBegan`),datediff(`closedDate`,`realBegan`)),0) realduration
from
zt_project
where deleted=\'0\'
and status=\'closed\'
and type=\'project\'
) t1', 'published', 0, '', 'system', '2023-04-06 12:18:07', 'admin', '2023-04-06 12:18:07', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10019, '年度完成项目-单位工时交付需求规模数对比图', 2, 'pie', '71', '0', '', '[{"type":"cluBarX","xaxis":[{"field":"project","name":"\\u6240\\u5c5e\\u9879\\u76ee","group":""}],"yaxis":[{"field":"\\u5355\\u4f4d\\u65f6\\u95f4\\u4ea4\\u4ed8\\u9700\\u6c42\\u89c4\\u6a21\\u6570","name":"\\u5355\\u4f4d\\u65f6\\u95f4\\u4ea4\\u4ed8\\u9700\\u6c42\\u89c4\\u6a21\\u6570","valOrAgg":"sum"}]}]', '[{"field":"project","type":"input","name":"\\u6240\\u5c5e\\u9879\\u76ee","default":""}]', 0, '{"project":{"name":"\\u6240\\u5c5e\\u9879\\u76ee","object":"project","field":"project","type":"string"},"\\u6545\\u4e8b\\u70b9":{"name":"\\u6545\\u4e8b\\u70b9","object":"project","field":"\\u6545\\u4e8b\\u70b9","type":"number"},"\\u5de5\\u65f6":{"name":"\\u5de5\\u65f6","object":"project","field":"\\u5de5\\u65f6","type":"number"},"\\u5355\\u4f4d\\u65f6\\u95f4\\u4ea4\\u4ed8\\u9700\\u6c42\\u89c4\\u6a21\\u6570":{"name":"\\u5355\\u4f4d\\u65f6\\u95f4\\u4ea4\\u4ed8\\u9700\\u6c42\\u89c4\\u6a21\\u6570","object":"project","field":"\\u5355\\u4f4d\\u65f6\\u95f4\\u4ea4\\u4ed8\\u9700\\u6c42\\u89c4\\u6a21\\u6570","type":"number"}}', '', 'select tt.*,
tt.`故事点` / tt.`工时` as "单位时间交付需求规模数"
from (
select
t1.name as project, 
(
	select round(sum(t3.estimate), 1) from zt_projectstory t2
	left join zt_story t3 on t3.id= t2.story and t3.status=\'closed\' and t3.closedReason = \'done\'
	where t2.project = t1.id
) as "故事点",
(
	select round(sum(t5.consumed), 1) from zt_project t4
	left join zt_task t5 on t5.execution = t4.id and t5.deleted = \'0\' and t5.parent in (0, -1)
  where t4.project = t1.id and t4.type = \'sprint\'
) as "工时"
from zt_project t1
where t1.status = \'closed\'
and t1.deleted = \'0\'
and t1.type = \'project\'
group by t1.id) tt', 'published', 1, '', 'system', '2023-04-06 13:41:05', 'admin', '2023-04-06 13:41:05', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10020, '年度完成项目-项目完成分布图', 2, 'pie', '71', '0', '', '[{"type":"pie","group":[{"field":"completeStatus","name":"completeStatus","group":""}],"metric":[{"field":"id","name":"\\u9879\\u76eeID","valOrAgg":"count"}]}]', '[{"field":"closedDate","type":"date","name":"\\u5173\\u95ed\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"completeStatus":{"name":"completeStatus","object":"project","field":"completeStatus","type":"string"},"closedDate":{"name":"\\u5173\\u95ed\\u65e5\\u671f","object":"project","field":"closedDate","type":"date"}}', '{"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"id","de":"","fr":""},"completeStatus":{"zh-cn":"\\u9879\\u76ee\\u5b8c\\u6210\\u60c5\\u51b5","zh-tw":"","en":"completeStatus","de":"","fr":""},"closedDate":{"zh-cn":"\\u5173\\u95ed\\u65e5\\u671f","zh-tw":"","en":"closedDate","de":"","fr":""}}', 'select
t1.id,
(case when t1.realEnd<t1.end then "提前完成项目" when t1.realEnd=t1.end then "正常完成项目" else "延期完成项目" end) "completeStatus",
t1.closedDate
from(
select
id,
closedDate,
end,
if(left(realEnd, 4) = \'0000\', closedDate, realEnd) as realEnd
from
zt_project
where deleted=\'0\'
and status=\'closed\'
and type=\'project\') t1', 'published', 0, '', 'system', '2023-04-06 12:22:34', 'admin', '2023-04-06 12:22:34', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10021, '年度完成项目-执行完成分布图', 2, 'pie', '71', '0', '', '[{"type":"pie","group":[{"field":"completeStatus","name":"completeStatus","group":""}],"metric":[{"field":"id","name":"\\u9879\\u76eeID","valOrAgg":"count"}]}]', '[{"field":"closedDate","type":"date","name":"\\u5173\\u95ed\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"completeStatus":{"name":"completeStatus","object":"project","field":"completeStatus","type":"string"},"closedDate":{"name":"\\u5173\\u95ed\\u65e5\\u671f","object":"project","field":"closedDate","type":"date"}}', '{"id":{"zh-cn":"\\u6267\\u884cID","zh-tw":"","en":"id","de":"","fr":""},"completeStatus":{"zh-cn":"\\u5b8c\\u6210\\u60c5\\u51b5","zh-tw":"","en":"completeStatus","de":"","fr":""},"closedDate":{"zh-cn":"\\u5173\\u95ed\\u65e5\\u671f","zh-tw":"","en":"","de":"","fr":""}}', 'select
t1.id,
(case when t1.realEnd<t1.end then "提前完成执行" when t1.realEnd=t1.end then "正常完成执行" else "延期完成执行" end) "completeStatus",
t1.closedDate
from(
select
id,
closedDate,
end,
if(left(realEnd, 4) = \'0000\', closedDate, realEnd) as realEnd
from
zt_project
where deleted=\'0\'
and status=\'closed\'
and type=\'sprint\') t1', 'published', 0, '', 'system', '2023-04-06 12:19:58', 'admin', '2023-04-06 12:22:34', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10022, '年度完成项目-完成项目工时偏差条形图', 2, 'cluBarY', '70', '0', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\\u4efb\\u52a1\\u540d\\u79f0","group":""}],"yaxis":[{"field":"rate","name":"rate","valOrAgg":"sum"}]}]', '[{"field":"closedDate","type":"date","name":"\\u5173\\u95ed\\u65f6\\u95f4","default":{"begin":"","end":""}}]', 0, '{"name":{"name":"\\u4efb\\u52a1\\u540d\\u79f0","object":"project","field":"name","type":"string"},"id":{"name":"\\u7f16\\u53f7","object":"project","field":"id","type":"number"},"closedDate":{"name":"\\u5173\\u95ed\\u65f6\\u95f4","object":"task","field":"closedDate","type":"date"},"estimate":{"name":"\\u6700\\u521d\\u9884\\u8ba1","object":"task","field":"estimate","type":"string"},"consumed":{"name":"\\u603b\\u8ba1\\u6d88\\u8017","object":"task","field":"consumed","type":"string"},"left":{"name":"\\u9884\\u8ba1\\u5269\\u4f59","object":"task","field":"left","type":"string"},"deviation":{"name":"deviation","object":"task","field":"deviation","type":"number"},"rate":{"name":"rate","object":"task","field":"rate","type":"number"}}', '{"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76ee\\u7f16\\u53f7","zh-tw":"","en":"id","de":"","fr":""},"closedDate":{"zh-cn":"\\u5173\\u95ed\\u65f6\\u95f4","zh-tw":"","en":"closedDate","de":"","fr":""},"estimate":{"zh-cn":"\\u6700\\u521d\\u9884\\u8ba1","zh-tw":"","en":"estimate","de":"","fr":""},"consumed":{"zh-cn":"\\u603b\\u8ba1\\u6d88\\u8017","zh-tw":"","en":"consumed","de":"","fr":""},"left":{"zh-cn":"\\u9884\\u8ba1\\u5269\\u4f59","zh-tw":"","en":"left","de":"","fr":""},"deviation":{"zh-cn":"\\u504f\\u5dee","zh-tw":"","en":"deviation","de":"","fr":""},"rate":{"zh-cn":"\\u504f\\u5dee\\u6bd4\\u7387","zh-tw":"","en":"rate","de":"","fr":""}}', 'select
*,
round(tt.deviation/tt.estimate,3) rate
from(
select
t1.name,
t1.id,
t1.closedDate,
t2.estimate estimate,
t2.consumed consumed,
t2.`left`,
t2.consumed-t2.estimate deviation
from
zt_project t1
left join
(select
project,
sum(estimate) estimate,
sum(consumed) consumed,
sum(`left`) `left`
from
zt_task
group by project) t2
on t1.id=t2.project
where t1.deleted=\'0\'
and t1.status=\'closed\'
and t1.type=\'project\') tt', 'published', 0, '', 'system', '2023-04-06 12:16:04', 'admin', '2023-04-06 12:16:04', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10101, '年度进行中项目-进行中的项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id FROM zt_project WHERE deleted = \'0\' AND status = \'doing\' AND type = \'project\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10102, '年度进行中项目-进行中的迭代数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id,type FROM zt_project WHERE deleted = \'0\' AND status = \'doing\' AND type IN (\'sprint\', \'stage\', \'kanban\') AND multiple = \'1\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10103, '年度进行中项目-进展顺利项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT t1.id, t1.name, IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss,LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = \'0\' AND t21.type IN (\'sprint\', \'kanban\')
    AND t22.deleted = \'0\' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = \'0\' AND t21.type = \'stage\'
        AND t22.deleted = \'0\' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project 
WHERE t1.deleted = \'0\'
AND t1.status = \'doing\' 
AND t1.type = \'project\'
AND ((IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != \'2059\' AND DATEDIFF(`end`, NOW()) >= 0) OR LEFT(t1.`end`, 4) = \'2059\' )', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10104, '年度进行中项目-进展顺利迭代数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id, prograss, planPrograss, `end`
FROM (
SELECT t1.id,ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,t1.`end`,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
AND t2.deleted = \'0\' AND t2.parent < 1
GROUP BY t1.id
) AS t
WHERE prograss >= planPrograss AND DATEDIFF(`end`, NOW()) >= 0', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10105, '年度进行中项目-进度滞后项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT t1.id, t1.name, IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss
, LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = \'0\' AND t21.type IN (\'sprint\', \'kanban\')
    AND t22.deleted = \'0\' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = \'0\' AND t21.type = \'stage\'
        AND t22.deleted = \'0\' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project 
WHERE t1.deleted = \'0\'
AND t1.status = \'doing\' 
AND t1.type = \'project\'
AND LEFT(t1.`end`, 4) != \'2059\'
AND IFNULL(prograss, 0) < (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100)  AND DATEDIFF(`end`, NOW()) >= 0', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10106, '年度进行中项目-进度滞后迭代数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id, prograss, planPrograss
FROM (
SELECT t1.id,ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\' AND DATEDIFF(t1.`end`, NOW()) >= 0
AND t2.deleted = \'0\' AND t2.parent < 1
GROUP BY t1.id
) AS t
WHERE prograss < planPrograss', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10107, '年度进行中项目-已延期项目数', 2, 'card', '72', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id, name FROM zt_project WHERE deleted = \'0\' AND status = \'doing\' AND type = \'project\' AND LEFT(`end`, 4) != \'2059\' AND DATEDIFF(`end`, NOW()) < 0', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10108, '年度进行中项目-已延期迭代数', 2, 'card', '73', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT id, name FROM zt_project WHERE deleted = \'0\' AND status = \'doing\' AND type IN (\'sprint\', \'stage\', \'kanban\') AND DATEDIFF(`end`, NOW()) < 0 AND multiple = \'1\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10109, '年度进行中项目-未完成需求条目数', 2, 'card', '75', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT DISTINCT t3.id, t3.estimate
FROM zt_project AS t1
LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
LEFT JOIN zt_story AS t3 ON t2.story = t3.id
WHERE t1.deleted = \'0\' AND t1.status = \'doing\' AND t1.type = \'project\'
AND t3.deleted = \'0\' AND t3.stage NOT IN (\'verified\', \'released\', \'closed\')', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10110, '年度进行中项目-未完成任务数', 2, 'card', '76', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT DISTINCT t2.id
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = \'0\' AND t1.status = \'doing\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\')
AND t2.deleted = \'0\' AND t2.status IN (\'wait\', \'doing\', \'pause\') AND t2.id IS NOT NULL', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10111, '年度进行中项目-未完成需求规模数', 2, 'card', '75', '0', '', '{"value": {"type": "agg", "field": "estimate", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT DISTINCT t3.id, t3.estimate
FROM zt_project AS t1
LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
LEFT JOIN zt_story AS t3 ON t2.story = t3.id
WHERE t1.deleted = \'0\' AND t1.status = \'doing\' AND t1.type = \'project\'
AND t3.deleted = \'0\' AND t3.stage NOT IN (\'verified\', \'released\', \'closed\')', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10112, '年度进行中项目-剩余工时数', 2, 'card', '78', '0', '', '{"value": {"type": "agg", "field": "taskleft", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT t1.id, t1.name, `taskleft`
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)), 2) AS `taskleft`
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = \'0\' AND t21.type IN (\'sprint\', \'stage\', \'kanban\')
    AND t22.deleted = \'0\' AND t22.parent < 1
    GROUP BY t22.project
) AS t2 ON t1.id = t2.project 
WHERE t1.deleted = \'0\'
AND t1.status = \'doing\' 
AND t1.type = \'project\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10113, '年度进行中项目-投入总人次', 2, 'card', '78', '0', '', '{"value": {"type": "agg", "field": "id", "agg": "count"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT t1.id,t1.type,t1.account
FROM zt_team AS t1
LEFT JOIN zt_user AS t2 on t1.account = t2.account
WHERE t1.type = \'project\' AND t2.deleted = \'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10114, '年度进行中项目-项目进度分布图', 2, 'pie', '69', '0', '', '[{"type":"pie","group":[{"field":"status","name":"\\u72b6\\u6001","group":""}],"metric":[{"field":"id","name":"id","valOrAgg":"count"}]}]', '[]', 0, '{"id":{"name":"id","object":"zt_project","field":"id","type":"number"},"name":{"name":"name","object":"zt_project","field":"name","type":"string"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"},"prograss":{"name":"prograss","object":"task","field":"prograss","type":"number"},"planPrograss":{"name":"planPrograss","object":"task","field":"planPrograss","type":"number"},"endYear":{"name":"endYear","object":"task","field":"endYear","type":"string"}}', '{"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"id","de":"","fr":""},"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""},"prograss":{"zh-cn":"\\u9879\\u76ee\\u8fdb\\u5ea6","zh-tw":"","en":"prograss","de":"","fr":""},"planPrograss":{"zh-cn":"\\u8ba1\\u5212\\u8fdb\\u5ea6","zh-tw":"","en":"planPrograss","de":"","fr":""},"endYear":{"zh-cn":"\\u7ed3\\u675f\\u5e74\\u4efd","zh-tw":"","en":"endYear","de":"","fr":""}}', 'SELECT t1.id, t1.name, 
IF(
    DATEDIFF(t1.`end`, NOW()) < 0, 
    "延期", 
    (IF(
        (IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != \'2059\') 
        OR LEFT(t1.`end`, 4) = \'2059\' ,
        "顺利",
        "滞后"
    ))) AS \'status\',
IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss,LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = \'0\' AND t21.type IN (\'sprint\', \'kanban\')
    AND t22.deleted = \'0\' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = \'0\' AND t21.type = \'stage\'
        AND t22.deleted = \'0\' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project 
WHERE t1.deleted = \'0\'
AND t1.status = \'doing\' 
AND t1.type = \'project\'', 'published', 0, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10115, '年度进行中项目-迭代进度分布图', 2, 'pie', '69', '0', '', '[{"type":"pie","group":[{"field":"status","name":"\\u72b6\\u6001","group":""}],"metric":[{"field":"id","name":"\\u9879\\u76eeID","valOrAgg":"count"}]}]', '[]', 0, '{"id":{"name":"\\u9879\\u76eeID","object":"project","field":"id","type":"number"},"name":{"name":"\\u9879\\u76ee\\u540d\\u79f0","object":"project","field":"name","type":"string"},"status":{"name":"\\u72b6\\u6001","object":"project","field":"status","type":"option"},"prograss":{"name":"prograss","object":"task","field":"prograss","type":"number"},"planPrograss":{"name":"planPrograss","object":"task","field":"planPrograss","type":"number"},"end":{"name":"\\u8ba1\\u5212\\u5b8c\\u6210","object":"project","field":"end","type":"date"}}', '{"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"id","de":"","fr":""},"name":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"name","de":"","fr":""},"status":{"zh-cn":"\\u72b6\\u6001","zh-tw":"","en":"status","de":"","fr":""},"prograss":{"zh-cn":"\\u9879\\u76ee\\u8fdb\\u5ea6","zh-tw":"","en":"prograss","de":"","fr":""},"planPrograss":{"zh-cn":"\\u8ba1\\u5212\\u8fdb\\u5ea6","zh-tw":"","en":"planPrograss","de":"","fr":""},"end":{"zh-cn":"\\u8ba1\\u5212\\u5b8c\\u6210","zh-tw":"","en":"end","de":"","fr":""}}', 'SELECT id, name,IF(
    DATEDIFF(`end`, NOW()) < 0,
    "延期",
    (IF(
        prograss >= planPrograss,
        "顺利",
        "滞后"
    ))
) AS status,
prograss, planPrograss, `end`
FROM (
SELECT t1.id,t1.name,ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,t1.`end`,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
AND ((t2.deleted = \'0\' AND t2.parent < 1) OR t2.id IS NULL)
GROUP BY t1.id
) AS t', 'published', 0, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10116, '年度进行中项目-项目进度透视表', 2, 'table', '84', '0', '', '{"group":[],"column":[{"field":"program","valOrAgg":"value","name":"一级项目集"},{"field":"name","valOrAgg":"value","name":"项目"},{"field":"begin","valOrAgg":"value","name":"计划开始日期"},{"field":"end","valOrAgg":"value","name":"计划完成日期"},{"field":"planDuration","valOrAgg":"value","name":"计划工期"},{"field":"realBegan","valOrAgg":"value","name":"实际开始日期"},{"field":"realDuration","valOrAgg":"value","name":"剩余工期天数"},{"field":"prograss","valOrAgg":"value","name":"工期进度"},{"field":"status","valOrAgg":"value","name":"进度状态"}],"filter":[]}', '[]', 0, '', '', 'SELECT t1.id, t1.name, IFNULL(t3.name, \'/\') AS program,t1.`begin`, IF(YEAR(t1.`end`) = \'2059\', "长期", t1.`end`) AS `end`, IF(YEAR(t1.`end`) = \'2059\', "长期", DATEDIFF(t1.`end`, t1.`begin`) + 1) AS planDuration,
IF(LEFT(t1.realBegan, 4) = \'0000\', \'/\', t1.realBegan) AS realBegan, IF(YEAR(t1.`end`) = \'2059\', "长期", IF(DATEDIFF(t1.`end`, NOW()) >= 0, DATEDIFF(t1.`end`, NOW()) + 1, 0)) AS realDuration,
IF(
    DATEDIFF(t1.`end`, NOW()) < 0, 
    "延期", 
    (IF(
        (IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != \'2059\') 
        OR LEFT(t1.`end`, 4) = \'2059\' ,
        "顺利",
        "滞后"
    ))) AS \'status\',
CONCAT(IFNULL(prograss, 0), \'%\') AS prograss
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = \'0\' AND t21.type IN (\'sprint\', \'kanban\')
    AND t22.deleted = \'0\' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != \'closed\' && t22.status != \'cancel\', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = \'0\' AND t21.type = \'stage\'
        AND t22.deleted = \'0\' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project 
LEFT JOIN zt_project AS t3 ON SUBSTR(t1.path, 2, POSITION(\',\' IN SUBSTR(t1.path, 2)) -1) = t3.id AND t3.type = \'program\' AND t3.deleted = \'0\'
WHERE t1.deleted = \'0\'
AND t1.status = \'doing\' 
AND t1.type = \'project\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10117, '年度进行中项目-迭代进度透视表', 2, 'table', '84', '0', '', '{"group":[],"column":[{"field":"project","valOrAgg":"value","name":"项目"},{"field":"name","valOrAgg":"value","name":"迭代"},{"field":"begin","valOrAgg":"value","name":"计划开始日期"},{"field":"end","valOrAgg":"value","name":"计划完成日期"},{"field":"planDuration","valOrAgg":"value","name":"计划工期"},{"field":"realBegan","valOrAgg":"value","name":"实际开始日期"},{"field":"realDuration","valOrAgg":"value","name":"剩余工期天数"},{"field":"prograss","valOrAgg":"value","name":"工期进度"},{"field":"status","valOrAgg":"value","name":"进度状态"}],"filter":[]}', '[]', 0, '', '', 'SELECT id, name,project,`begin`, `end`, planDuration, IF(LEFT(realBegan, 4) = \'0000\', \'/\', realBegan) as realBegan, realDuration, CONCAT(prograss, \'%\') as prograss,
IF(
    DATEDIFF(`end`, NOW()) < 0,
    "延期",
    (IF(
        prograss >= planPrograss,
        "顺利",
        "滞后"
    ))
) AS status
FROM (
SELECT t1.id,t1.name,t1.`begin`,t1.`end`,t1.`realBegan`,IFNULL(t3.name, \'/\') AS project,t3.id AS projectID,
DATEDIFF(t1.`end`, t1.`begin`) + 1 AS planDuration, IF(DATEDIFF(t1.`end`, NOW()) >= 0, DATEDIFF(t1.`end`, NOW()) + 1, 0) AS realDuration,
ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != \'closed\' && t2.status != \'cancel\', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
LEFT JOIN zt_project AS t3 on t1.project = t3.id AND t3.type = \'project\' AND t3.deleted = \'0\'
WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
AND t2.deleted = \'0\' AND t2.parent < 1
GROUP BY t1.id
) AS t
ORDER BY projectID ASC, id ASC', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10118, '年度进行中项目-项目剩余工作量透视表', 2, 'table', '83', '0', '', '{"group":[],"column":[{"field":"program","valOrAgg":"value","name":"一级项目集"},{"field":"project","valOrAgg":"value","name":"项目"},{"field":"story","valOrAgg":"value","name":"剩余需求数"},{"field":"estimate","valOrAgg":"value","name":"剩余需求规模数"},{"field":"execution","valOrAgg":"value","name":"剩余执行数"},{"field":"workhour","valOrAgg":"value","name":"剩余工时"}],"filter":[]}', '[]', 0, '', '', 'SELECT
  t1.id,
  t1.name AS project,
  IFNULL(t2.name, \'/\') AS program,
  IFNULL(t3.story, 0) AS story,
  IFNULL(t3.estimate, 0) AS estimate,
  IFNULL(t4.execution, 0) AS execution,  
  IFNULL(t5.workhour, 0) AS workhour
FROM zt_project AS t1
LEFT JOIN zt_project AS t2 ON FIND_IN_SET(t2.id, t1.path) AND t2.deleted = \'0\' AND t2.type = \'program\' AND t2.grade = 1
LEFT JOIN (
  SELECT t1.parent AS project, COUNT(1) AS story, ROUND(SUM(t1.estimate), 1) AS estimate
  FROM (
    SELECT DISTINCT t1.parent, t3.id, t3.estimate
    FROM zt_project AS t1
    LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
    LEFT JOIN zt_story AS t3 ON t2.story = t3.id AND t3.deleted = \'0\' AND t3.stage NOT IN (\'verified\', \'released\', \'closed\')
    WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t3.id IS NOT NULL
  ) AS t1 GROUP BY project
) AS t3 ON t1.id = t3.project
LEFT JOIN (SELECT parent AS project, COUNT(1) AS execution FROM zt_project WHERE deleted = \'0\' AND type IN (\'sprint\', \'stage\', \'kanban\') AND status NOT IN (\'done\', \'closed\') GROUP BY project) AS t4 ON t1.id = t4.project
LEFT JOIN (
  SELECT t1.parent AS project, ROUND(SUM(t2.left), 1) AS workhour
  FROM zt_project AS t1
  LEFT JOIN zt_task AS t2 ON t1.id = t2.execution AND t2.deleted = \'0\' AND t2.parent < 1
  WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status NOT IN (\'done\', \'closed\') AND t2.id IS NOT NULL
  GROUP BY project
) AS t5 ON t1.id = t5.project
WHERE t1.deleted = \'0\' AND t1.type = \'project\' AND t1.status = \'doing\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10119, '年度进行中项目-迭代剩余工作量透视表', 2, 'table', '83', '0', '', '{"group":[],"column":[{"field":"project","valOrAgg":"value","name":"项目"},{"field":"execution","valOrAgg":"value","name":"迭代"},{"field":"story","valOrAgg":"value","name":"剩余需求数"},{"field":"estimate","valOrAgg":"value","name":"剩余需求规模数"},{"field":"task","valOrAgg":"value","name":"剩余任务数"},{"field":"workhour","valOrAgg":"value","name":"剩余工时"}],"filter":[]}', '[]', 0, '', '', 'SELECT
  t1.id,
  t1.name AS execution,
  IFNULL(t2.name, \'/\') AS project,
  IFNULL(t3.story, 0) AS story,
  IFNULL(t3.estimate, 0) AS estimate,
  IFNULL(t4.task, 0) AS task,  
  IFNULL(t4.workhour, 0) AS workhour
FROM zt_project AS t1
LEFT JOIN zt_project AS t2 ON t1.project = t2.id AND t2.type = \'project\'
LEFT JOIN (
  SELECT t1.id AS execution, COUNT(1) AS story, ROUND(SUM(t3.estimate), 1) AS estimate
  FROM zt_project AS t1
  LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
  LEFT JOIN zt_story AS t3 ON t2.story = t3.id AND t3.deleted = \'0\' AND t3.stage NOT IN (\'verified\', \'released\', \'closed\')
  WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
  GROUP BY execution
) AS t3 ON t1.id = t3.execution
LEFT JOIN (
  SELECT t1.id AS execution, SUM(IF(t2.status IN (\'wait\', \'doing\'), 1, 0)) AS task, ROUND(SUM(IF(t2.status IN (\'wait\', \'doing\', \'pause\'), t2.left, 0)), 1) AS workhour
  FROM zt_project AS t1
  LEFT JOIN zt_task AS t2 ON t1.id = t2.execution AND t2.deleted = \'0\' AND t2.parent < 1
  WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
  GROUP BY execution
) AS t4 ON t1.id = t4.execution
WHERE t1.deleted = \'0\' AND t1.type IN (\'sprint\', \'stage\', \'kanban\') AND t1.status = \'doing\' AND t1.multiple = \'1\'
ORDER BY t2.id ASC, t1.id ASC', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10201, '质量数据-研发完成需求数', 3, 'card', '93', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT COUNT(id) AS number FROM zt_story WHERE deleted=\'0\' AND (stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (status=\'closed\' AND closedReason=\'done\'))', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10202, '质量数据-研发完成需求规模数', 3, 'card', '93', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT ROUND(SUM(estimate),2) AS number FROM zt_story WHERE deleted=\'0\' AND (stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (status=\'closed\' AND closedReason=\'done\'))', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10203, '质量数据-研发完成需求用例数', 3, 'card', '95', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT SUM(t2.cases) AS number FROM (SELECT t1.story story, COUNT(t1.id) cases FROM (SELECT story,id FROM zt_case WHERE deleted=\'0\') t1 GROUP BY t1.story) t2 LEFT JOIN (SELECT id,stage,status,closedReason,deleted FROM zt_story) t3 ON t2.story=t3.id WHERE t3.deleted=\'0\' AND (t3.stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (t3.status=\'closed\' AND t3.closedReason=\'done\'))', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10204, '质量数据-Bug总数', 3, 'card', '94', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT COUNT(id) AS number FROM zt_bug WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10205, '质量数据-有效Bug数', 3, 'card', '94', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT SUM(CASE WHEN resolution IN (\'fixed\',\'postponed\') OR status=\'active\' THEN 1 ELSE 0 END) AS number FROM zt_bug WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10206, '质量数据-修复Bug数', 3, 'card', '94', '0', '', '{"value": {"type": "agg", "field": "number", "agg": "sum"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, '', '', 'SELECT SUM(CASE WHEN resolution=\'fixed\' THEN 1 ELSE 0 END) AS number FROM zt_bug WHERE deleted=\'0\'', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10207, '质量数据-研发完成需求用例覆盖率', 3, 'waterpolo', '92', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT ROUND(SUM(t3.havecasefixstory)/COUNT(t3.fixstory),2) AS status FROM (SELECT t2.storyid \'fixstory\', (CASE WHEN t2.cases=0 THEN 0 ELSE 1 END) havecasefixstory FROM (SELECT t1.storyid, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted=\'0\' AND (zt_story.stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (zt_story.status=\'closed\' AND zt_story.closedReason=\'done\'))) t1 GROUP BY t1.storyid ORDER BY cases DESC) t2) t3', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10208, '质量数据-研发完成需求用例密度', 3, 'waterpolo', '92', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT ROUND(SUM(t2.cases)/SUM(t2.estimate),2) AS status FROM (SELECT t1.storyid, t1.estimate, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, zt_story.estimate, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted=\'0\' AND (zt_story.stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (zt_story.status=\'closed\' AND zt_story.closedReason=\'done\'))) t1 GROUP BY t1.storyid, t1.estimate ORDER BY cases DESC) t2', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10209, '质量数据-Bug密度', 3, 'waterpolo', '91', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT SUM(t3.bug)/SUM(t3.estimate) AS status FROM (SELECT t1.product product, IFNULL(t1.estimate,0) estimate, IFNULL(t2.bug,0) bug FROM (SELECT product, ROUND(SUM(estimate),2) estimate FROM zt_story WHERE deleted=\'0\' AND (stage IN (\'developed\',\'testing\',\'verfied\',\'released\') OR (status=\'closed\' AND closedReason=\'done\')) GROUP BY product) t1 LEFT JOIN (SELECT product, COUNT(id) bug FROM zt_bug WHERE deleted=\'0\' GROUP BY product) t2 ON t1.product=t2.product) t3', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10210, '质量数据-Bug修复率', 3, 'waterpolo', '91', '0', '', '{"group":[{"field":"status","name":"状态"}],"metric":[{"type":"agg","field":"id","agg":"count","name":"需求数","valOrAgg":"count"}]}', '[]', 0, '', '', 'SELECT SUM(CASE WHEN resolution=\'fixed\' THEN 1 ELSE 0 END)/COUNT(id) AS status FROM zt_bug WHERE deleted = \'0\' ', 'published', 1, '', 'system', '2022-12-07 14:59:41', 'admin', '2022-12-07 14:59:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10211, '质量数据-Bug总数、有效Bug与解决Bug数近30天统计柱形图', 3, 'cluBarX', '91', '0', '', '[{"type":"cluBarX","xaxis":[{"field":"\\u65e5\\u671f","name":"\\u65e5\\u671f","group":"day"}],"yaxis":[{"field":"Bug\\u603b\\u6570","name":"Bug\\u603b\\u6570","valOrAgg":"count"},{"field":"\\u6709\\u6548Bug","name":"\\u6709\\u6548Bug","valOrAgg":"sum"},{"field":"\\u5df2\\u89e3\\u51b3Bug","name":"\\u5df2\\u89e3\\u51b3Bug","valOrAgg":"sum"}]}]', '[]', 4, '{"Bug\\u603b\\u6570":{"name":"Bug\\u603b\\u6570","object":"bug","field":"Bug\\u603b\\u6570","type":"number"},"\\u6709\\u6548Bug":{"name":"\\u6709\\u6548Bug","object":"bug","field":"\\u6709\\u6548Bug","type":"string"},"\\u5df2\\u89e3\\u51b3Bug":{"name":"\\u5df2\\u89e3\\u51b3Bug","object":"bug","field":"\\u5df2\\u89e3\\u51b3Bug","type":"string"},"\\u65e5\\u671f":{"name":"\\u65e5\\u671f","object":"bug","field":"\\u65e5\\u671f","type":"date"}}', '', 'select 
id "Bug总数",
(case when  resolution in (\'fixed\',\'postponed\') or status=\'active\' then 1 else 0 end) "有效Bug",
(case when  resolution=\'fixed\' then 1 else 0 end) "已解决Bug",
openedDate "日期"
from zt_bug
where left(openedDate,10) > (select DATE_sub(MAX(NOW()), INTERVAL \'30\' DAY)) 
and left(openedDate,10) < NOW()
and deleted=\'0\'', 'published', 1, '', 'system', '2023-04-06 12:51:56', 'admin', '2023-04-06 12:51:56', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10212, '质量数据-有效Bug率年度趋势图', 3, 'line', '91', '0', '', '[{"type":"line","xaxis":[{"field":"year","name":"year","group":""}],"yaxis":[{"field":"effectiveBugRate","name":"effectiveBugRate","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 4, '{"year":{"name":"year","object":"bug","field":"year","type":"string"},"totalBugCount":{"name":"totalBugCount","object":"bug","field":"totalBugCount","type":"string"},"effectiveBugCount":{"name":"effectiveBugCount","object":"bug","field":"effectiveBugCount","type":"number"},"effectiveBugRate":{"name":"effectiveBugRate","object":"bug","field":"effectiveBugRate","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"Year","de":"","fr":""},"totalBugCount":{"zh-cn":"Bug\\u603b\\u6570","zh-tw":"","en":"Total Bug Count","de":"","fr":""},"effectiveBugCount":{"zh-cn":"\\u6709\\u6548Bug\\u6570","zh-tw":"","en":"Effective Bug Count","de":"","fr":""},"effectiveBugRate":{"zh-cn":"\\u6709\\u6548Bug\\u7387","zh-tw":"","en":"Effective Bug Rate","de":"","fr":""}}', 'select
year,
count(a.id) as totalBugCount,
sum(a.effectivebug) as effectiveBugCount,
sum(a.effectivebug)/count(a.id) effectiveBugRate
from(
select 
left(openedDate,4) year,
id,
(case when  resolution in (\'fixed\',\'postponed\') or status=\'active\' then 1 else 0 end) effectivebug,
(case when  resolution=\'fixed\' then 1 else 0 end) fixedBug
from zt_bug
where zt_bug.deleted=\'0\'
) a
group by a.year
order by  a.year', 'published', 0, '', 'system', '2023-04-06 13:00:04', 'admin', '2023-04-06 13:00:04', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10213, '质量数据-Bug密度年度趋势图', 3, 'line', '91', '0', '', '[{"type":"line","xaxis":[{"field":"year","name":"year","group":""}],"yaxis":[{"field":"bugCount","name":"Bug\\u6570","valOrAgg":"sum"}]}]', '[{"field":"year","type":"select","name":"\\u5e74\\u4efd"}]', 4, '{"year":{"name":"year","object":"story","field":"year","type":"string"},"createdBugs":{"name":"createdBugs","object":"story","field":"createdBugs","type":"string"},"exfixedstoryestimate":{"name":"exfixedstoryestimate","object":"story","field":"exfixedstoryestimate","type":"number"},"bugCount":{"name":"Bug\\u6570","object":"story","field":"bugCount","type":"number"}}', '{"year":{"zh-cn":"\\u5e74\\u4efd","zh-tw":"","en":"Year","de":"","fr":""},"createdBugs":{"zh-cn":"\\u4ea7\\u751fBug","zh-tw":"","en":"Created Bug","de":"","fr":""},"exfixedstoryestimate":{"zh-cn":"\\u5b8c\\u6210\\u9700\\u6c42\\u6570","zh-tw":"","en":"Finished Story","de":"","fr":""},"bugCount":{"zh-cn":"\\u5355\\u4f4d\\u5b8c\\u6210\\u9700\\u6c42\\u89c4\\u6a21\\u4ea7\\u751f\\u7684Bug\\u6570","zh-tw":"","en":"Bug Density","de":"","fr":""}}', 'select
bug.year as year,
createdBugs, 
exfixedstoryestimate,
round(createdBugs/exfixedstoryestimate,2) as bugCount                                                                                                                                                             
from
(select 
left(openedDate,4) year,
count(id) createdBugs
from zt_bug
where zt_bug.deleted=\'0\'
group by year
) bug 
left join
(select
sum(estimate) exfixedstoryestimate,
left(closedDate,4) year
from
zt_story
where zt_story.deleted=\'0\' and zt_story.status=\'closed\' and zt_story.closedReason=\'done\'
group by year
) story
on story.year=bug.year
order by bug.year', 'published', 0, '', 'system', '2023-04-06 13:05:10', 'admin', '2023-04-06 13:05:10', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10214, '质量数据-Bug严重程度年度堆积柱状图', 3, 'stackedBar', '91', '0', '', '[{"type":"stackedBar","xaxis":[{"field":"\\u5e74\\u4efd","name":"\\u5e74\\u4efd","group":""}],"yaxis":[{"field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a1\\u7ea7\\u7684Bug","name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a1\\u7ea7\\u7684Bug","valOrAgg":"sum"},{"field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a2\\u7ea7\\u7684Bug","name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a2\\u7ea7\\u7684Bug","valOrAgg":"sum"},{"field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4f4e\\u4e8e2\\u7ea7\\u7684Bug","name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4f4e\\u4e8e2\\u7ea7\\u7684Bug","valOrAgg":"sum"}]}]', '[{"field":"\\u5e74\\u4efd","type":"select","name":"\\u5e74\\u4efd"}]', 4, '{"\\u6240\\u6709Bug\\u6570":{"name":"\\u6240\\u6709Bug\\u6570","object":"bug","field":"\\u6240\\u6709Bug\\u6570","type":"string"},"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a1\\u7ea7\\u7684Bug":{"name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a1\\u7ea7\\u7684Bug","object":"bug","field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a1\\u7ea7\\u7684Bug","type":"number"},"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a2\\u7ea7\\u7684Bug":{"name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a2\\u7ea7\\u7684Bug","object":"bug","field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e3a2\\u7ea7\\u7684Bug","type":"number"},"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4f4e\\u4e8e2\\u7ea7\\u7684Bug":{"name":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4f4e\\u4e8e2\\u7ea7\\u7684Bug","object":"bug","field":"\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4f4e\\u4e8e2\\u7ea7\\u7684Bug","type":"number"},"\\u5e74\\u4efd":{"name":"\\u5e74\\u4efd","object":"bug","field":"\\u5e74\\u4efd","type":"string"}}', '', 'select
count(id) "所有Bug数",
sum(case when severity=1 then 1 else 0 end) "严重程度为1级的Bug",
sum(case when severity=2 then 1 else 0 end) "严重程度为2级的Bug",
sum(case when severity not in (1,2) then 1 else 0 end) "严重程度低于2级的Bug",
left(openedDate,4) "年份"
from
zt_bug
where deleted=\'0\'
group by left(openedDate,4)
order by left(openedDate,4)', 'published', 1, '', 'system', '2023-04-06 13:12:34', 'admin', '2023-04-06 13:12:34', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10215, '质量数据-产品用例数量统计条形图', 3, 'cluBarY', '91', '0', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"name","group":""}],"yaxis":[{"field":"count","name":"count","valOrAgg":"sum"}]}]', '[{"field":"name","type":"select","name":"\\u4ea7\\u54c1"}]', 4, '{"name":{"name":"name","object":"product","field":"name","type":"string"},"count":{"name":"count","object":"testcase","field":"id","type":"string"}}', '{"name":{"zh-cn":"\\u4ea7\\u54c1\\u540d\\u79f0","zh-tw":"","en":"Product","de":"","fr":""},"count":{"zh-cn":"\\u7528\\u4f8b\\u8ba1\\u6570","zh-tw":"","en":"Case Count","de":"","fr":""}}', 'select 
t1.name,
ifnull(t2.cases,0) as count
from
zt_product t1
left join
(
select
product,
count(id) cases 
from
zt_case
where deleted=\'0\'
group by product )
t2 on t1.id=t2.product', 'published', 0, '', 'system', '2023-04-06 13:33:48', 'admin', '2023-04-06 13:33:48', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10216, '质量数据-产品Bug数量统计条形图', 3, 'cluBarY', '91', '0', '', '[{"type":"cluBarY","xaxis":[{"field":"name","name":"name","group":""}],"yaxis":[{"field":"bug","name":"bug","valOrAgg":"sum"}],"rotateX":"notuse"}]', '[{"field":"name","type":"select","name":"\\u4ea7\\u54c1"}]', 4, '{"name":{"name":"name","object":"product","field":"name","type":"string"},"bug":{"name":"bug","object":"bug","field":"id","type":"string"}}', '{"name":{"zh-cn":"\\u4ea7\\u54c1\\u540d\\u79f0","zh-tw":"","en":"Product","de":"","fr":""},"bug":{"zh-cn":"Bug\\u8ba1\\u6570","zh-tw":"","en":"Bug Count","de":"","fr":""}}', 'select 
t1.name,
ifnull(t2.bugs,0) bug
from
zt_product t1
left join
(
select
product,
count(id) bugs 
from
zt_bug
where zt_bug.deleted=\'0\'
group by product )
t2 on t1.id=t2.product', 'published', 0, '', 'system', '2023-04-06 13:35:24', 'admin', '2023-04-06 13:35:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10217, '质量数据-Bug状态分布图', 3, 'pie', '91', '0', '', '[{"type":"pie","group":[{"field":"status","name":"Bug\\u72b6\\u6001","group":""}],"metric":[{"field":"id","name":"Bug\\u7f16\\u53f7","valOrAgg":"count"}]}]', '[{"field":"openedDate","type":"date","name":"\\u521b\\u5efa\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"Bug\\u7f16\\u53f7","object":"bug","field":"id","type":"number"},"status":{"name":"Bug\\u72b6\\u6001","object":"bug","field":"status","type":"option"},"openedDate":{"name":"\\u521b\\u5efa\\u65e5\\u671f","object":"bug","field":"openedDate","type":"date"}}', '{"id":{"zh-cn":"Bug\\u7f16\\u53f7","zh-tw":"","en":"Bug ID","de":"","fr":""},"status":{"zh-cn":"Bug\\u72b6\\u6001","zh-tw":"","en":"Status","de":"","fr":""},"openedDate":{"zh-cn":"\\u521b\\u5efa\\u65e5\\u671f","zh-tw":"","en":"Opened Date","de":"","fr":""}}', 'select 
id,status,openedDate 
from zt_bug
where deleted=\'0\'', 'published', 0, '', 'system', '2023-04-06 12:25:00', 'admin', '2023-04-06 12:25:00', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10218, '质量数据-Bug类型分布', 3, 'pie', '91', '0', '', '[{"type":"pie","group":[{"field":"type","name":"Bug\\u7c7b\\u578b","group":""}],"metric":[{"field":"id","name":"Bug\\u7f16\\u53f7","valOrAgg":"count"}]}]', '[{"field":"openedDate","type":"date","name":"\\u521b\\u5efa\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"Bug\\u7f16\\u53f7","object":"bug","field":"id","type":"number"},"type":{"name":"Bug\\u7c7b\\u578b","object":"bug","field":"type","type":"option"},"openedDate":{"name":"\\u521b\\u5efa\\u65e5\\u671f","object":"bug","field":"openedDate","type":"date"}}', '{"id":{"zh-cn":"Bug\\u7f16\\u53f7","zh-tw":"","en":"Bug ID","de":"","fr":""},"type":{"zh-cn":"Bug\\u7c7b\\u578b","zh-tw":"","en":"Type","de":"","fr":""},"openedDate":{"zh-cn":"\\u521b\\u5efa\\u65e5\\u671f","zh-tw":"","en":"Opened Date","de":"","fr":""}}', 'select
id,type,openedDate
from
zt_bug
where deleted=\'0\'', 'published', 0, '', 'system', '2023-04-18 13:34:46', 'admin', '2023-04-18 13:34:46', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10219, '质量数据-Bug严重程度分布', 3, 'pie', '91', '0', '', '[{"type":"pie","group":[{"field":"severity","name":"\\u4e25\\u91cd\\u7a0b\\u5ea6","group":""}],"metric":[{"field":"id","name":"Bug\\u7f16\\u53f7","valOrAgg":"count"}]}]', '[{"field":"openedDate","type":"date","name":"\\u521b\\u5efa\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"Bug\\u7f16\\u53f7","object":"bug","field":"id","type":"number"},"severity":{"name":"\\u4e25\\u91cd\\u7a0b\\u5ea6","object":"bug","field":"severity","type":"option"},"openedDate":{"name":"\\u521b\\u5efa\\u65e5\\u671f","object":"bug","field":"openedDate","type":"date"}}', '{"id":{"zh-cn":"Bug\\u7f16\\u53f7","zh-tw":"","en":"Bug ID","de":"","fr":""},"severity":{"zh-cn":"\\u4e25\\u91cd\\u7a0b\\u5ea6","zh-tw":"","en":"Severity","de":"","fr":""},"openedDate":{"zh-cn":"\\u521b\\u5efa\\u65e5\\u671f","zh-tw":"","en":"Opened Date","de":"","fr":""}}', 'select
id,severity,openedDate
from
zt_bug
where deleted=\'0\'', 'published', 0, '', 'system', '2023-04-18 13:36:37', 'admin', '2023-04-18 13:36:37', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (10220, '质量数据-Bug解决方案分布', 3, 'pie', '91', '0', '', '[{"type":"pie","group":[{"field":"resolution","name":"\\u89e3\\u51b3\\u65b9\\u6848","group":""}],"metric":[{"field":"id","name":"Bug\\u7f16\\u53f7","valOrAgg":"count"}]}]', '[{"field":"resolvedDate","type":"date","name":"\\u89e3\\u51b3\\u65e5\\u671f","default":{"begin":"","end":""}}]', 0, '{"id":{"name":"Bug\\u7f16\\u53f7","object":"bug","field":"id","type":"number"},"resolution":{"name":"\\u89e3\\u51b3\\u65b9\\u6848","object":"bug","field":"resolution","type":"option"},"resolvedDate":{"name":"\\u89e3\\u51b3\\u65e5\\u671f","object":"bug","field":"resolvedDate","type":"date"}}', '{"id":{"zh-cn":"Bug\\u7f16\\u53f7","zh-tw":"","en":"Bug ID","de":"","fr":""},"resolution":{"zh-cn":"\\u89e3\\u51b3\\u65b9\\u6848","zh-tw":"","en":"Resolution","de":"","fr":""},"resolvedDate":{"zh-cn":"\\u89e3\\u51b3\\u65e5\\u671f","zh-tw":"","en":"Resolved Date","de":"","fr":""}}', 'select id,resolution,resolvedDate from zt_bug
where deleted=\'0\' and resolution!=\' \'', 'published', 0, '', 'system', '2023-04-18 13:37:24', '', '2023-04-18 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20002, '活跃账号情况-活跃账号数项目间对比', 1, 'table', '56', '0', ' ', '{"group":[],"column":[{"field":"name","valOrAgg":"value","name":"项目"},{"field":"activeAccount","valOrAgg":"value","name":"活跃账号数"},{"field":"totalAccount","valOrAgg":"value","name":"团队账号数"},{"field":"ratio","valOrAgg":"value","name":"活跃账号比"}],"filter":[]}', '[]', 0, ' ', null, 'SELECT t1.id, t1.name, t1.year, t1.month, t1.totalAccount, ifnull(t2.activeAccount,0) as activeAccount, ifnull(concat(truncate(t2.activeAccount/t1.totalAccount*100,2),\'%\'), 0) as ratio
FROM (
select t1.id, t1.name, t3.year, t3.month, count(distinct t2.`account`) as totalAccount
from zt_project as t1
left join zt_team as t2 on t1.id = t2.root
left join (
    SELECT DISTINCT YEAR(`date`) AS `year`, MONTH(`date`) AS `month`, cast(`date` as DATE) as date
    FROM zt_action
) as t3 on t2.`join` <= t3.date
left join zt_user as t4 on t2.account = t4.account
where t1.type = \'project\'
and t4.deleted = \'0\'
group by t1.id, t3.year, t3.month
) AS t1 LEFT JOIN (
SELECT t1.id, t1.name, t4.year,t4.month, COUNT(DISTINCT t3.id) AS activeAccount
FROM
  zt_project AS t1
  LEFT JOIN zt_team AS t2 ON t1.id = t2.root
  LEFT JOIN zt_user AS t3 ON t2.account = t3.account
  LEFT JOIN (
    SELECT objectID, YEAR(date) AS year, MONTH(date) AS month, cast(`date` as DATE) as date
    FROM zt_action
    WHERE objectType = \'user\' AND action = \'login\'
  ) AS t4 ON t3.id = t4.objectID and t2.`join` <= t4.date
WHERE
  t3.deleted = \'0\' AND t1.type = \'project\'
GROUP BY t1.id, t4.year, t4.month
) AS t2 ON t1.year = t2.year AND t1.month = t2.month AND t1.id = t2.id
ORDER BY t2.activeAccount DESC', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20003, '活跃账号情况-公司账号日活跃度趋势', 1, 'line', '56', '0', ' ', '{
  "xaxis":[{"field":"day","name":"日期","group":"value"}],
  "yaxis":[{"type":"value","field":"count","agg":"value","name":"数量","valOrAgg":"value"}]
}
', '[]', 0, ' ', null, 'SELECT YEAR(t2.date) AS year, MONTH(t2.date) AS month, DAY(t2.date) AS day, COUNT(DISTINCT t1.account) AS count FROM zt_user AS t1
LEFT JOIN zt_action AS t2 ON t1.account = t2.actor
WHERE t2.objectType = \'user\' AND t2.action = \'login\'
GROUP BY YEAR(t2.date), MONTH(t2.date), DAY(t2.date)', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20004, '应用数据-活跃产品数', 1, 'card', '47', '0', ' ', '{"value": {"type": "value", "field": "count", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'select count(distinct REPLACE(product, \',\', \'\')) as count, year(date) as year, month(date) as month 
from zt_action
where objectType not in (\'project\',\'execution\',\'task\')
and product != \',0,\'
and product != \',\'
and product != \'\'
group by year(date), month(date)', 'published', 1, '  ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20005, '应用数据-本月新增产品数', 1, 'card', '47', '0', ' ', '{"value": {"type": "value", "field": "count", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'SELECT DISTINCT YEAR(createdDate) AS year, MONTH(createdDate) AS month, count(id) as count FROM zt_product
WHERE deleted = \'0\' AND shadow = \'0\'
GROUP BY YEAR(createdDate), MONTH(createdDate)', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20006, '应用数据-本月新增产品名', 1, 'card', '47', '0', ' ', '{"value": {"type": "text", "field": "name", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'SELECT DISTINCT GROUP_CONCAT(name) AS name, YEAR(createdDate) AS year, MONTH(createdDate) AS month FROM zt_product
WHERE deleted = \'0\' AND shadow = \'0\'
GROUP BY YEAR(createdDate), MONTH(createdDate)
', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20007, '应用数据-活跃项目数', 1, 'card', '46', '0', ' ', '{"value": {"type": "value", "field": "count", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'select year(date) as year, month(date) as month, count(distinct project) as count
from zt_action
where project != 0
group by year(date), month(date)', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20008, '应用数据-本月新增项目数', 1, 'card', '46', '0', ' ', '{"value": {"type": "value", "field": "count", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'SELECT COUNT(id) as count, YEAR(openedDate) AS year, MONTH(openedDate) AS month FROM zt_project
WHERE deleted = \'0\' AND type = \'project\'
GROUP BY YEAR(openedDate), MONTH(openedDate)', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20009, '应用数据-本月新增项目名 ', 1, 'card', '46', '0', ' ', '{"value": {"type": "text", "field": "name", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'SELECT DISTINCT GROUP_CONCAT(name) AS name, YEAR(openedDate) AS year, MONTH(openedDate) AS month FROM zt_project
WHERE deleted = \'0\' AND type = \'project\'
GROUP BY YEAR(openedDate), MONTH(openedDate)
', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20010, '应用数据-项目任务概况表', 1, 'table', '39', '0', ' ', '{"group":[],"column":[{"field":"name","valOrAgg":"value","name":"项目"},{"field":"createdTasks","valOrAgg":"value","name":"新增任务数"},{"field":"contributors","valOrAgg":"value","name":"新增任务人数"},{"field":"finishedTasks","valOrAgg":"value","name":"完成任务数"}],"filter":[]}', '[]', 0, ' ', null, 'SELECT
	t1.name,
	t4.year,
	t4.month,
	t1.createdTasks,
	t2.finishedTasks,
	t3.contributors 
FROM
  (
select distinct year(date) as year, month(date) as month
from zt_action 
  ) as t4 
left join
	(
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.openedDate ) AS YEAR,
	MONTH ( t2.openedDate ) AS MONTH,
	COUNT( t2.id ) AS createdTasks 
FROM
	zt_project AS t1
	LEFT JOIN zt_task AS t2 ON t1.id = t2.project 
WHERE
	t1.type = \'project\' 
GROUP BY
	t1.id,
	YEAR ( t2.openedDate ),
	MONTH ( t2.openedDate ) 
	) AS t1 on t4.year = t1.year and t4.month = t1.month
	LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.finishedDate ) AS YEAR,
	MONTH ( t2.finishedDate ) AS MONTH,
	COUNT( t2.id ) AS finishedTasks 
FROM
	zt_project AS t1
	LEFT JOIN zt_task AS t2 ON t1.id = t2.project 
WHERE
	t1.type = \'project\' 
	AND t2.finishedDate IS NOT NULL 
GROUP BY
	t1.id,
	YEAR ( t2.finishedDate ),
	MONTH ( t2.finishedDate ) 
	) AS t2 ON t1.id = t2.id 
	AND t4.YEAR = t2.YEAR 
	AND t4.MONTH = t2.
	MONTH LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t3.date ) AS YEAR,
	MONTH ( t3.date ) AS MONTH,
	COUNT( DISTINCT t3.actor ) AS CONTRIBUTORS 
FROM
	zt_project AS t1
	LEFT JOIN zt_task AS t2 ON t1.id = t2.project
	LEFT JOIN zt_action AS t3 ON t2.id = t3.objectID 
WHERE
	t1.type = \'project\' 
	AND t3.objectType = \'task\' 
	AND t3.action IN ( \'opened\', \'closed\', \'finished\', \'canceled\', \'assigned\' ) 
GROUP BY
	t1.id,
	YEAR ( t3.date ),
	MONTH ( t3.date ) 
	) AS t3 ON t1.id = t3.id 
	AND t4.YEAR = t3.YEAR 
	AND t4.MONTH = t3.MONTH
	order by t1.id,t4.year', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20011, '应用数据-产品测试表', 1, 'table', '44', '0', ' ', '{"group":[],"column":[{"field":"name","valOrAgg":"value","name":"产品"},{"field":"createdCases","valOrAgg":"value","name":"新增用例数"},{"field":"avgBugsOfCase","valOrAgg":"value","name":"用例平均Bug数"},
{"field":"createdBugs","valOrAgg":"value","name":"新增Bug数"},{"field":"fixedBugs","valOrAgg":"value","name":"修复Bug数"},{"field":"avgFixedCycle","valOrAgg":"value","name":"Bug平均修复周期"}],"filter":[]}
', '[]', 0, ' ', null, 'SELECT * FROM
(
SELECT
	t1.name,
	t6.year,
	t6.month,
	IFNULL(t5.createdCases, 0) AS createdCases,
	IFNULL(t4.relativedBugs / t5.createdCases, 0) AS avgBugsOfCase,
	IFNULL(t1.createdBugs, 0) AS createdBugs,
	IFNULL(t2.fixedBugs, 0) AS fixedBugs,
	IFNULL(t3.fixedCycle / t2.fixedBugs, 0) AS avgFixedCycle 
FROM
	(
	select distinct year(date) as year, month(date) as month
	from zt_action
	) AS t6 left join
	(
	SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.openedDate ) AS YEAR,
	MONTH ( t2.openedDate ) AS MONTH,
	COUNT( t2.id ) AS createdBugs 
FROM
	zt_product AS t1
	LEFT JOIN zt_bug AS t2 ON t1.id = t2.product 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\' 
GROUP BY
	t1.id,
	YEAR ( t2.openedDate ),
	MONTH ( t2.openedDate )
	) AS t1 on t1.year = t6.year AND t1.month = t6.month
	LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.resolvedDate ) AS YEAR,
	MONTH ( t2.resolvedDate ) AS MONTH,
	COUNT( t2.id ) AS fixedBugs 
FROM
	zt_product AS t1
	LEFT JOIN zt_bug AS t2 ON t1.id = t2.product 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\' 
	AND t2.`status` = \'closed\' 
	AND t2.resolution = \'fixed\' 
GROUP BY
	t1.id,
	YEAR ( t2.resolvedDate ),
	MONTH ( t2.resolvedDate ) 
	) AS t2 ON t1.id = t2.id 
	AND t6.YEAR = t2.YEAR 
	AND t6.MONTH = t2.MONTH 
	LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.resolvedDate ) AS YEAR,
	MONTH ( t2.resolvedDate ) AS MONTH,
	SUM( DATEDIFF( t2.resolvedDate, t2.openedDate ) ) AS fixedCycle 
FROM
	zt_product AS t1
	LEFT JOIN zt_bug AS t2 ON t1.id = t2.product 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\'
	AND t2.`status` = \'closed\'
	AND t2.resolution = \'fixed\' 
GROUP BY
	t1.id,
	YEAR ( t2.resolvedDate ),
	MONTH ( t2.resolvedDate ) 
	) AS t3 ON t1.id = t3.id 
	AND t6.YEAR = t3.YEAR 
	AND t6.MONTH = t3.
	MONTH LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.openedDate ) AS YEAR,
	MONTH ( t2.openedDate ) AS MONTH,
	COUNT( t3.id ) AS relativedBugs 
FROM
	zt_product AS t1
	LEFT JOIN zt_case AS t2 ON t1.id = t2.product
	LEFT JOIN zt_bug AS t3 ON t2.id = t3.`case` 
WHERE
	t2.id IS NOT NULL 
	AND t3.id IS NOT NULL
  AND t1.deleted = \'0\'
	AND t2.deleted = \'0\'
	AND t3.deleted = \'0\'
GROUP BY
	t1.id,
	YEAR ( t2.openedDate ),
	MONTH ( t2.openedDate ) 
	) AS t4 ON t1.id = t4.id 
	AND t6.YEAR = t4.YEAR 
	AND t6.MONTH = t4.
	MONTH LEFT JOIN (
 SELECT
	t1.id,
	t1.NAME,
	YEAR ( t2.openedDate ) AS YEAR,
	MONTH ( t2.openedDate ) AS MONTH,
	COUNT( t2.id ) AS createdCases 
FROM
	zt_product AS t1
	LEFT JOIN zt_case AS t2 ON t1.id = t2.product 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\' 
GROUP BY
	t1.id,
	YEAR ( t2.openedDate ),
	MONTH ( t2.openedDate )
	) AS t5 ON t1.id = t5.id 
	AND t6.YEAR = t5.YEAR 
	AND t6.MONTH = t5.MONTH
) AS t WHERE t.name IS NOT NULL', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20012, '应用数据-产品需求概况表', 1, 'table', '36', '0', ' ', '{"group":[],"column":[{"field":"name","valOrAgg":"value","name":"产品"},{"field":"createdStories","valOrAgg":"value","name":"新增研发需求数"},{"field":"deliveredStories","valOrAgg":"value","name":"交付需求数"}],"filter":[]}', '[]', 0, ' ', null, 'SELECT * FROM
(
SELECT
	t1.id,
	t1.name,
	t3.year,
	t3.month,
	IFNULL(t1.count, 0) AS createdStories,
	IFNULL(t2.count, 0) AS deliveredStories 
FROM
	(
	select distinct year(date) as year, month(date) as month
	from zt_action
	)
	as t3 left join
	(
SELECT
	t2.id,
	t2.NAME,
	YEAR ( t1.openedDate ) AS YEAR,
	MONTH ( t1.openedDate ) AS MONTH,
	COUNT( t1.id ) AS count 
FROM
	zt_story AS t1
	LEFT JOIN zt_product AS t2 ON t1.product = t2.id 
WHERE
	t2.deleted = \'0\'
	AND t1.deleted = \'0\'
	AND t1.type = \'story\'
GROUP BY
	t2.id,
	YEAR,
MONTH 
	) AS t1 on t3.year = t1.year and t3.month = t1.month
	LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	t1.YEAR,
	t1.MONTH,
	COUNT( distinct t1.story ) AS count 
FROM
	(
SELECT
	t2.id,
	t2.NAME, 
	YEAR ( t3.date ) AS YEAR,
	MONTH ( t3.date ) AS MONTH,
	t1.id AS story 
FROM
	zt_story AS t1
	LEFT JOIN zt_product AS t2 ON t1.product = t2.id
	LEFT JOIN ( SELECT objectID, MAX( date ) AS date FROM zt_action WHERE objectType = \'story\' AND action = \'linked2release\' GROUP BY objectID ) AS t3 ON t1.id = t3.objectID 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\' 
	AND EXISTS ( SELECT 1 FROM zt_action WHERE objectID = t1.id AND objectType = \'story\' AND action = \'linked2release\' ) 
UNION
SELECT
	t2.id,
	t2.NAME,
	YEAR ( t1.closedDate ) AS YEAR,
	MONTH ( t1.closedDate ) AS MONTH,
	t1.id AS story 
FROM
	zt_story AS t1
	LEFT JOIN zt_product AS t2 ON t1.product = t2.id 
WHERE
	t1.deleted = \'0\' 
	AND t2.deleted = \'0\' 
	AND t1.status = \'closed\' 
	AND t1.closedReason = \'done\' 
	) AS t1 
GROUP BY
	t1.id,
	t1.name,
	t1.YEAR,
	t1.MONTH order by id asc 
	) AS t2 ON t1.id = t2.id 
	AND t3.YEAR = t2.YEAR 
	AND t3.MONTH = t2.MONTH
) AS t
WHERE t.name IS NOT NULL', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20013, '应用数据-项目需求概况表', 1, 'table', '43', '0', ' ', '{"group":[],"column":[{"field":"name","valOrAgg":"value","name":"项目"},{"field":"createdStories","valOrAgg":"value","name":"新增研发需求数"},{"field":"deliveredStories","valOrAgg":"value","name":"交付需求数"}],"filter":[]}', '[]', 0, ' ', null, 'SELECT * FROM (
SELECT
	t1.id,
	t1.name,
	t3.year,
	t3.month,
	IFNULL(t1.count, 0) AS createdStories,
	IFNULL(t2.count, 0) AS deliveredStories 
FROM
	(
	select distinct year(date) as year, month(date) as month
	from zt_action
	)
	as t3 left join
	(
SELECT
	t3.id,
	t3.NAME,
	YEAR ( t1.openedDate ) AS YEAR,
	MONTH ( t1.openedDate ) AS MONTH,
	COUNT( t1.id ) AS count 
FROM
	zt_story AS t1
	LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.story
	LEFT JOIN zt_project AS t3 ON t2.project = t3.id 
WHERE
	t3.type = \'project\' 
	AND t1.deleted = \'0\' 
	AND t3.deleted = \'0\' 
GROUP BY
	t3.id,
	YEAR,
MONTH 
	) AS t1 on t1.year = t3.year and t1.month = t3.month
	LEFT JOIN (
SELECT
	t1.id,
	t1.NAME,
	t1.YEAR,
	t1.MONTH,
	COUNT( t1.story ) AS count 
FROM
	(
SELECT
	t3.id,
	t3.NAME,
	YEAR ( t4.date ) AS YEAR,
	MONTH ( t4.date ) AS MONTH,
	t1.id AS story 
FROM
	zt_story AS t1
	LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.story
	LEFT JOIN zt_project AS t3 ON t2.project = t3.id
	LEFT JOIN ( SELECT objectID, MAX( date ) AS date FROM zt_action WHERE objectType = \'story\' AND action = \'linked2release\' GROUP BY objectID ) AS t4 ON t1.id = t4.objectID 
WHERE
	t3.type = \'project\' 
	AND t1.deleted = \'0\' 
	AND t3.deleted = \'0\' 
	AND EXISTS ( SELECT 1 FROM zt_action WHERE objectID = t1.id AND objectType = \'story\' AND action = \'linked2release\' ) UNION
SELECT
	t3.id,
	t3.NAME,
	YEAR ( t1.closedDate ) AS YEAR,
	MONTH ( t1.closedDate ) AS MONTH,
	t1.id AS story 
FROM
	zt_story AS t1
	LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.story
	LEFT JOIN zt_project AS t3 ON t2.project = t3.id 
WHERE
	t3.type = \'project\' 
	AND t1.STATUS = \'closed\' 
	AND t1.closedReason = \'done\' 
	AND t1.deleted = \'0\' 
	AND t3.deleted = \'0\' 
	) AS t1 
GROUP BY
	t1.id,
	t1.name,
	t1.YEAR,
	t1.MONTH 
	) AS t2 ON t1.id = t2.id 
	AND t3.YEAR = t2.YEAR 
	AND t3.MONTH = t2.MONTH
) AS t WHERE t.id IS NOT NULL', 'published', 1, ' ', 'system', '2023-08-16 13:37:24', 'admin', '2023-08-16 13:37:24', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20014, '使用数据分析-当前版本', 1, 'card', '58', '0', ' ', '{"value": {"type": "value", "field": "version", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', ' ', 0, ' ', null, 'SELECT REPLACE(REPLACE(REPLACE(value, \'max\', \'旗舰版\'), \'biz\', \'企业版\'), \'pro\', \'专业版\') as version FROM zt_config WHERE owner = \'system\' AND module = \'common\' AND section = \'global\' AND `key` = \'version\'', 'published', 1, ' ', 'system', '2023-08-16 15:28:35', 'admin', '2023-08-16 15:28:41', 0);
INSERT INTO zt_chart (id, name, dimension, type, `group`, dataset, `desc`, settings, filters, step, fields, langs, `sql`, stage, builtin, objects, createdBy, createdDate, editedBy, editedDate, deleted) VALUES (20015, '使用数据分析-上线时间', 1, 'card', '58', '0', ' ', '{"value": {"type": "value", "field": "date", "agg": "value"}, "title": {"type": "text", "name": ""}, "type": "value"}', '[]', 0, ' ', null, 'select `value` as date from zt_config where `owner` = \'system\' and `key` = \'installedDate\'', 'published', 1, ' ', 'system', '2023-08-16 15:32:10', 'admin', '2023-08-16 15:32:17', 0);
