<?php
$config->bi->builtin->pivots = array();

$config->bi->builtin->pivots[] = array
(
    'id'        => 1000,
    'name'      => array('zh-cn' => '完成项目工期透视表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'      => 'finishedProjectDuration',
    'dimension' => '2',
    'group'     => '86',
    'sql'       => <<<EOT
select
t1.name,
t2.program1,
t1.begin,
t1.end,
t1.realBegan,
t1.realEnd,
t1.closedDate,
t1.realduration,
t1.realduration-t1.planduration duration_deviation,
round((t1.realduration-t1.planduration)/t1.planduration,3) rate
from
(select
name,
substr(`path`,2,4) program1,
begin,
end,
realBegan,
realEnd,
left(closedDate,10) closedDate,
datediff(`end`,`begin`) planduration,
ifnull(if(left(`realEnd`,4) != '0000',datediff(`realEnd`,`realBegan`),datediff(`closedDate`,`realBegan`)),0) realduration
from
zt_project
where type='project' and status='closed' and deleted='0') t1
left join
(select
id programid,
name program1
from
zt_project
where type='program' and grade=1) t2
on t1.program1=t2.programid
EOT,
    'settings'  => array
    (
        'columns'  => array
        (
            array('field' => 'begin', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'end', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'realBegan', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'realEnd', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'closedDate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'realduration', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'duration_deviation', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'rate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow')
        ),
        'group3'   => 'program1',
        'group6'   => 'name',
        'lastStep' => '4'
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'name'               => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'program1'           => array('name' => 'program1', 'object' => 'project', 'field' => 'program1', 'type' => 'string'),
        'begin'              => array('name' => '计划开始日期', 'object' => 'project', 'field' => 'begin', 'type' => 'date'),
        'end'                => array('name' => '计划完成日期', 'object' => 'project', 'field' => 'end', 'type' => 'date'),
        'realBegan'          => array('name' => '实际开始日期', 'object' => 'project', 'field' => 'realBegan', 'type' => 'date'),
        'realEnd'            => array('name' => '实际完成日期', 'object' => 'project', 'field' => 'realEnd', 'type' => 'date'),
        'closedDate'         => array('name' => '关闭日期', 'object' => 'project', 'field' => 'closedDate', 'type' => 'date'),
        'realduration'       => array('name' => 'realduration', 'object' => 'project', 'field' => 'realduration', 'type' => 'number'),
        'duration_deviation' => array('name' => 'duration_deviation', 'object' => 'project', 'field' => 'duration_deviation', 'type' => 'number'),
        'rate'               => array('name' => 'rate', 'object' => 'project', 'field' => 'rate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'name'               => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'program1'           => array('zh-cn' => '一级项目集', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'begin'              => array('zh-cn' => '计划开始日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'end'                => array('zh-cn' => '计划完成日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'realBegan'          => array('zh-cn' => '实际开始日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'realEnd'            => array('zh-cn' => '实际完成日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'closedDate'         => array('zh-cn' => '关闭日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'realduration'       => array('zh-cn' => '实际工期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'duration_deviation' => array('zh-cn' => '工期偏差', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'rate'               => array('zh-cn' => '工期偏差率', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1001,
    'name'      => array('zh-cn' => '完成项目工时透视表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'      => 'finishedProjectHour',
    'dimension' => '2',
    'group'     => '85',
    'sql'       => <<<EOT
select
t1.name "项目名称",
t4.program1 "一级项目集",
round(t2.estimate,2) "预计工时",
round(t2.consumed,2) "消耗工时",
round(t2.consumed-t2.estimate,2) "工时偏差",
round((t2.consumed-t2.estimate)/t2.estimate,2) "工时偏差率",
ifnull(t3.storys,0) "完成需求数",
ifnull(t3.storyestimate,0) "完成需求规模数",
round(ifnull(t3.storyestimate,0)/ifnull(t2.consumed,0),2) "单位时间交付需求规模数",
t1.closedDate closedDate
from(
select
id,
name,
substr(`path`,2,4) program1,
closedDate
from
zt_project
where deleted='0' and type='project' and status='closed') t1
left join(
select
project,
sum(estimate) estimate,
sum(consumed) consumed
from
zt_task
where deleted='0' and project !=0
group by project) t2
on t1.id=t2.project
left join (
select
tt3.project,
count(tt3.id) storys,
sum(estimate) storyestimate
from(
select
tt1.id,
tt1.estimate,
tt2.project
from
zt_story tt1
left join
zt_projectstory tt2
on tt1.id=tt2.story
where tt1.deleted='0' and tt1.status='closed' and tt1.closedReason='done') tt3
group by tt3.project) t3
on t1.id=t3.project
left join
(select
id programid,
name program1
from
zt_project
where type='program' and grade=1) t4
on t1.program1=t4.programid
EOT,
    'settings'  => array
    (
        'columns'     => array
        (
            array('field' => '预计工时', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '消耗工时', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '工时偏差', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '工时偏差率', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '完成需求数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '完成需求规模数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '单位时间交付需求规模数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow')
        ),
        'columnTotal' => 'noShow',
        'group1'      => '一级项目集',
        'group2'      => '项目名称',
        'lastStep'    => '4'
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'closedDate',
            'type'    => 'date',
            'name'    => '关闭日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        '项目名称'                      => array('name' => '项目名称', 'object' => 'project', 'field' => '项目名称', 'type' => 'string'),
        '一级项目集'                   => array('name' => '一级项目集', 'object' => 'project', 'field' => '一级项目集', 'type' => 'string'),
        '预计工时'                      => array('name' => '预计工时', 'object' => 'project', 'field' => '预计工时', 'type' => 'number'),
        '消耗工时'                      => array('name' => '消耗工时', 'object' => 'project', 'field' => '消耗工时', 'type' => 'number'),
        '工时偏差'                      => array('name' => '工时偏差', 'object' => 'project', 'field' => '工时偏差', 'type' => 'number'),
        '工时偏差率'                   => array('name' => '工时偏差率', 'object' => 'project', 'field' => '工时偏差率', 'type' => 'number'),
        '完成需求数'                   => array('name' => '完成需求数', 'object' => 'project', 'field' => '完成需求数', 'type' => 'string'),
        '完成需求规模数'             => array('name' => '完成需求规模数', 'object' => 'project', 'field' => '完成需求规模数', 'type' => 'number'),
        '单位时间交付需求规模数' => array('name' => '单位时间交付需求规模数', 'object' => 'project', 'field' => '单位时间交付需求规模数', 'type' => 'number'),
        'closedDate'                        => array('name' => '关闭日期', 'object' => 'project', 'field' => 'closedDate', 'type' => 'date')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1002,
    'name'      => array('zh-cn' => '产品缺陷数据汇总表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'      => 'productBugSummary',
    'dimension' => '3',
    'group'     => '100',
    'sql'       => <<<EOT
SELECT
  t1.name AS  "产品",
  IFNULL(t2.name, '/') AS "一级项目集",
  IFNULL(t3.name, '/') AS "产品线",
  IFNULL(t6.exfixedstorys, 0) AS "研发完成需求数",
  round(IFNULL(t6.exfixedstorysmate, 0),3) AS "研发完成需求规模数",
  IFNULL(t8.storycases, 0) AS "需求用例数",
  round(storycases/exfixedstorysmate,3) AS "用例密度",
  round(IFNULL(t10.casestorys/t6.exfixedstorys,0),3) AS "用例覆盖率",
  IFNULL(t7.bug, 0) AS "Bug数",
  IFNULL(t7.effbugs, 0) AS "有效Bug数",
  IFNULL(t7.pri12bugs, 0) AS "优先级为1，2的Bug数",
  round(bug/exfixedstorysmate,3) AS "Bug密度",
  IFNULL(t7.fixedbugs, 0) AS "修复Bug数",
  round(t7.fixedbugs/bug,3) "Bug修复率"
FROM
  zt_product AS t1
  LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = 'program' AND t2.grade = 1
  LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = 'line'
  LEFT JOIN (
  SELECT
  product,
  count(id) exfixedstorys,
  sum(estimate) exfixedstorysmate
  FROM zt_story
  WHERE deleted = '0'  and (stage in ('developed','testing','verfied','released') or (status='closed' and closedReason='done'))
  GROUP BY product) AS t6 ON t1.id = t6.product
  LEFT JOIN (SELECT product, COUNT(id)  AS bug,
  SUM(case when  resolution in ('fixed','postponed') or status='active' then 1 else 0 end) effbugs,
  SUM(CASE WHEN  resolution='fixed' then 1 else 0 end) fixedbugs,
  SUM(CASE WHEN severity in (1,2) then 1 else 0 end) pri12bugs
  FROM zt_bug WHERE deleted = '0' GROUP BY product) AS t7 ON t1.id = t7.product
  LEFT JOIN (SELECT product, COUNT(id) AS storycases FROM zt_case WHERE deleted='0' GROUP BY product) AS t8 ON t1.id=t8.product
  LEFT JOIN (
   select
   t9.product,
   count(t9.story) casestorys
   from(
     SELECT
     zt_case.product,zt_case.story
     FROM zt_case
     left join zt_story
     on zt_case.story=zt_story.id
     WHERE zt_case.deleted='0' and zt_case.story !='0' and zt_story.deleted='0' and  (zt_story.stage in ('developed','testing','verfied','released') or (zt_story.status='closed' and zt_story.closedReason='done'))
     GROUP BY product, story) t9
     group by product) t10
     on t1.id=t10.product
WHERE t1.deleted = '0' AND t1.status != 'closed' AND t1.shadow = '0'AND t1.vision = 'rnd'
ORDER BY t1.order
EOT,
    'settings'  => array
    (
        'columns'     => array
        (
            array('field' => '研发完成需求数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '研发完成需求规模数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '需求用例数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '用例密度', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '用例覆盖率', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'Bug数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '有效Bug数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '优先级为1，2的Bug数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'Bug密度', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => '修复Bug数', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'Bug修复率', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow')
        ),
        'columnTotal' => 'noShow',
        'group1'      => '一级项目集',
        'group3'      => '产品线',
        'group2'      => '产品',
        'lastStep'    => '4'
    ),
    'filters'   => array(),
    'fields'    => array
    (
        '产品'                      => array('name' => '产品', 'object' => 'story', 'field' => '产品', 'type' => 'string'),
        '一级项目集'             => array('name' => '一级项目集', 'object' => 'story', 'field' => '一级项目集', 'type' => 'string'),
        '产品线'                   => array('name' => '产品线', 'object' => 'story', 'field' => '产品线', 'type' => 'string'),
        '研发完成需求数'       => array('name' => '研发完成需求数', 'object' => 'story', 'field' => '研发完成需求数', 'type' => 'string'),
        '研发完成需求规模数' => array('name' => '研发完成需求规模数', 'object' => 'story', 'field' => '研发完成需求规模数', 'type' => 'number'),
        '需求用例数'             => array('name' => '需求用例数', 'object' => 'story', 'field' => '需求用例数', 'type' => 'string'),
        '用例密度'                => array('name' => '用例密度', 'object' => 'story', 'field' => '用例密度', 'type' => 'number'),
        '用例覆盖率'             => array('name' => '用例覆盖率', 'object' => 'story', 'field' => '用例覆盖率', 'type' => 'number'),
        'Bug数'                      => array('name' => 'Bug数', 'object' => 'story', 'field' => 'Bug数', 'type' => 'string'),
        '有效Bug数'                => array('name' => '有效Bug数', 'object' => 'story', 'field' => '有效Bug数', 'type' => 'number'),
        '优先级为1，2的Bug数'  => array('name' => '优先级为1，2的Bug数', 'object' => 'story', 'field' => '优先级为1，2的Bug数', 'type' => 'number'),
        'Bug密度'                   => array('name' => 'Bug密度', 'object' => 'story', 'field' => 'Bug密度', 'type' => 'number'),
        '修复Bug数'                => array('name' => '修复Bug数', 'object' => 'story', 'field' => '修复Bug数', 'type' => 'number'),
        'Bug修复率'                => array('name' => 'Bug修复率', 'object' => 'story', 'field' => 'Bug修复率', 'type' => 'number')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1003,
    'name'      => array('zh-cn' => '产品完成度统计表', 'zh-tw' => '產品完成度統計表', 'en' => 'Product Progress', 'de' => 'Product Progress', 'fr' => 'Product Progress'),
    'code'      => 'productProgress',
    'desc'      => array('zh-cn' => '按照产品列出需求总数，完成的总数(状态是关闭，或者研发阶段是发布)，完成的百分比。', 'zh-tw' => '按照產品列出需求總數，完成的總數(狀態是關閉，或者研發階段是發布)，完成的百分比。', 'en' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.', 'de' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.', 'fr' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.'),
    'dimension' => '1',
    'group'     => '59',
    'sql'       => <<<EOT
select t1.*,t2.name, (case when t1.status = 'closed' or t1.stage = 'released' then 1 else 0 end) as done, 1 as count from zt_story as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'count', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'done', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '1')
        )
    ),
    'fields'    => array
    (
        'id'               => array('object' => 'story', 'field' => 'id', 'type' => 'string'),
        'vision'           => array('object' => 'project', 'field' => 'vision', 'type' => 'string'),
        'parent'           => array('object' => 'story', 'field' => 'parent', 'type' => 'string'),
        'product'          => array('object' => 'story', 'field' => 'product', 'type' => 'string'),
        'branch'           => array('object' => 'story', 'field' => 'branch', 'type' => 'string'),
        'module'           => array('object' => 'story', 'field' => 'module', 'type' => 'string'),
        'plan'             => array('object' => 'story', 'field' => 'plan', 'type' => 'string'),
        'source'           => array('object' => 'story', 'field' => 'source', 'type' => 'string'),
        'sourceNote'       => array('object' => 'story', 'field' => 'sourceNote', 'type' => 'string'),
        'fromBug'          => array('object' => 'story', 'field' => 'fromBug', 'type' => 'string'),
        'feedback'         => array('object' => 'story', 'field' => 'feedback', 'type' => 'string'),
        'title'            => array('object' => 'story', 'field' => 'title', 'type' => 'string'),
        'keywords'         => array('object' => 'story', 'field' => 'keywords', 'type' => 'string'),
        'type'             => array('object' => 'story', 'field' => 'type', 'type' => 'string'),
        'category'         => array('object' => 'story', 'field' => 'category', 'type' => 'string'),
        'pri'              => array('object' => 'story', 'field' => 'pri', 'type' => 'string'),
        'estimate'         => array('object' => 'story', 'field' => 'estimate', 'type' => 'string'),
        'status'           => array('object' => 'story', 'field' => 'status', 'type' => 'string'),
        'subStatus'        => array('object' => 'story', 'field' => 'subStatus', 'type' => 'string'),
        'color'            => array('object' => 'story', 'field' => 'color', 'type' => 'string'),
        'stage'            => array('object' => 'story', 'field' => 'stage', 'type' => 'string'),
        'stagedBy'         => array('object' => 'story', 'field' => 'stagedBy', 'type' => 'string'),
        'mailto'           => array('object' => 'story', 'field' => 'mailto', 'type' => 'string'),
        'lib'              => array('object' => 'project', 'field' => 'lib', 'type' => 'string'),
        'fromStory'        => array('object' => 'project', 'field' => 'fromStory', 'type' => 'string'),
        'fromVersion'      => array('object' => 'project', 'field' => 'fromVersion', 'type' => 'string'),
        'openedBy'         => array('object' => 'story', 'field' => 'openedBy', 'type' => 'string'),
        'openedDate'       => array('object' => 'story', 'field' => 'openedDate', 'type' => 'string'),
        'assignedTo'       => array('object' => 'story', 'field' => 'assignedTo', 'type' => 'string'),
        'assignedDate'     => array('object' => 'story', 'field' => 'assignedDate', 'type' => 'string'),
        'approvedDate'     => array('object' => 'project', 'field' => 'approvedDate', 'type' => 'string'),
        'lastEditedBy'     => array('object' => 'story', 'field' => 'lastEditedBy', 'type' => 'string'),
        'lastEditedDate'   => array('object' => 'story', 'field' => 'lastEditedDate', 'type' => 'string'),
        'changedBy'        => array('object' => 'story', 'field' => 'changedBy', 'type' => 'string'),
        'changedDate'      => array('object' => 'story', 'field' => 'changedDate', 'type' => 'string'),
        'reviewedBy'       => array('object' => 'story', 'field' => 'reviewedBy', 'type' => 'string'),
        'reviewedDate'     => array('object' => 'story', 'field' => 'reviewedDate', 'type' => 'string'),
        'closedBy'         => array('object' => 'story', 'field' => 'closedBy', 'type' => 'string'),
        'closedDate'       => array('object' => 'story', 'field' => 'closedDate', 'type' => 'string'),
        'closedReason'     => array('object' => 'story', 'field' => 'closedReason', 'type' => 'string'),
        'activatedDate'    => array('object' => 'story', 'field' => 'activatedDate', 'type' => 'string'),
        'toBug'            => array('object' => 'story', 'field' => 'toBug', 'type' => 'string'),
        'childStories'     => array('object' => 'story', 'field' => 'childStories', 'type' => 'string'),
        'linkStories'      => array('object' => 'story', 'field' => 'linkStories', 'type' => 'string'),
        'linkRequirements' => array('object' => 'story', 'field' => 'linkRequirements', 'type' => 'string'),
        'twins'            => array('object' => 'story', 'field' => 'twins', 'type' => 'string'),
        'duplicateStory'   => array('object' => 'story', 'field' => 'duplicateStory', 'type' => 'string'),
        'version'          => array('object' => 'story', 'field' => 'version', 'type' => 'string'),
        'storyChanged'     => array('object' => 'project', 'field' => 'storyChanged', 'type' => 'string'),
        'feedbackBy'       => array('object' => 'story', 'field' => 'feedbackBy', 'type' => 'string'),
        'notifyEmail'      => array('object' => 'story', 'field' => 'notifyEmail', 'type' => 'string'),
        'URChanged'        => array('object' => 'story', 'field' => 'URChanged', 'type' => 'string'),
        'deleted'          => array('object' => 'story', 'field' => 'deleted', 'type' => 'string'),
        'name'             => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'done'             => array('object' => 'project', 'field' => 'done', 'type' => 'string'),
        'count'            => array('object' => 'project', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1004,
    'name'      => array('zh-cn' => '产品需求状态分布表', 'zh-tw' => '產品需求狀態分布表', 'en' => 'Story Status', 'de' => 'Story Status', 'fr' => 'Story Status'),
    'code'      => 'productStoryStatus',
    'desc'      => array('zh-cn' => '按照产品列出需求总数，状态的分布情况。', 'zh-tw' => '按照產品列出需求總數，狀態的分布情況。', 'en' => 'Total number and status distribution of stories.', 'de' => 'Total number and status distribution of stories.', 'fr' => 'Total number and status distribution of stories.'),
    'dimension' => '1',
    'group'     => '59',
    'sql'       => <<<EOT
select t1.*,t2.name from zt_story as t1
 left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'fields'    => array
    (
        'id'               => array('object' => 'story', 'field' => 'id', 'type' => 'string'),
        'vision'           => array('object' => 'project', 'field' => 'vision', 'type' => 'string'),
        'parent'           => array('object' => 'story', 'field' => 'parent', 'type' => 'string'),
        'product'          => array('object' => 'story', 'field' => 'product', 'type' => 'string'),
        'branch'           => array('object' => 'story', 'field' => 'branch', 'type' => 'string'),
        'module'           => array('object' => 'story', 'field' => 'module', 'type' => 'string'),
        'plan'             => array('object' => 'story', 'field' => 'plan', 'type' => 'string'),
        'source'           => array('object' => 'story', 'field' => 'source', 'type' => 'string'),
        'sourceNote'       => array('object' => 'story', 'field' => 'sourceNote', 'type' => 'string'),
        'fromBug'          => array('object' => 'story', 'field' => 'fromBug', 'type' => 'string'),
        'feedback'         => array('object' => 'story', 'field' => 'feedback', 'type' => 'string'),
        'title'            => array('object' => 'story', 'field' => 'title', 'type' => 'string'),
        'keywords'         => array('object' => 'story', 'field' => 'keywords', 'type' => 'string'),
        'type'             => array('object' => 'story', 'field' => 'type', 'type' => 'string'),
        'category'         => array('object' => 'story', 'field' => 'category', 'type' => 'string'),
        'pri'              => array('object' => 'story', 'field' => 'pri', 'type' => 'string'),
        'estimate'         => array('object' => 'story', 'field' => 'estimate', 'type' => 'string'),
        'status'           => array('object' => 'story', 'field' => 'status', 'type' => 'string'),
        'subStatus'        => array('object' => 'story', 'field' => 'subStatus', 'type' => 'string'),
        'color'            => array('object' => 'story', 'field' => 'color', 'type' => 'string'),
        'stage'            => array('object' => 'story', 'field' => 'stage', 'type' => 'string'),
        'stagedBy'         => array('object' => 'story', 'field' => 'stagedBy', 'type' => 'string'),
        'mailto'           => array('object' => 'story', 'field' => 'mailto', 'type' => 'string'),
        'lib'              => array('object' => 'project', 'field' => 'lib', 'type' => 'string'),
        'fromStory'        => array('object' => 'project', 'field' => 'fromStory', 'type' => 'string'),
        'fromVersion'      => array('object' => 'project', 'field' => 'fromVersion', 'type' => 'string'),
        'openedBy'         => array('object' => 'story', 'field' => 'openedBy', 'type' => 'string'),
        'openedDate'       => array('object' => 'story', 'field' => 'openedDate', 'type' => 'string'),
        'assignedTo'       => array('object' => 'story', 'field' => 'assignedTo', 'type' => 'string'),
        'assignedDate'     => array('object' => 'story', 'field' => 'assignedDate', 'type' => 'string'),
        'approvedDate'     => array('object' => 'project', 'field' => 'approvedDate', 'type' => 'string'),
        'lastEditedBy'     => array('object' => 'story', 'field' => 'lastEditedBy', 'type' => 'string'),
        'lastEditedDate'   => array('object' => 'story', 'field' => 'lastEditedDate', 'type' => 'string'),
        'changedBy'        => array('object' => 'story', 'field' => 'changedBy', 'type' => 'string'),
        'changedDate'      => array('object' => 'story', 'field' => 'changedDate', 'type' => 'string'),
        'reviewedBy'       => array('object' => 'story', 'field' => 'reviewedBy', 'type' => 'string'),
        'reviewedDate'     => array('object' => 'story', 'field' => 'reviewedDate', 'type' => 'string'),
        'closedBy'         => array('object' => 'story', 'field' => 'closedBy', 'type' => 'string'),
        'closedDate'       => array('object' => 'story', 'field' => 'closedDate', 'type' => 'string'),
        'closedReason'     => array('object' => 'story', 'field' => 'closedReason', 'type' => 'string'),
        'activatedDate'    => array('object' => 'story', 'field' => 'activatedDate', 'type' => 'string'),
        'toBug'            => array('object' => 'story', 'field' => 'toBug', 'type' => 'string'),
        'childStories'     => array('object' => 'story', 'field' => 'childStories', 'type' => 'string'),
        'linkStories'      => array('object' => 'story', 'field' => 'linkStories', 'type' => 'string'),
        'linkRequirements' => array('object' => 'story', 'field' => 'linkRequirements', 'type' => 'string'),
        'twins'            => array('object' => 'story', 'field' => 'twins', 'type' => 'string'),
        'duplicateStory'   => array('object' => 'story', 'field' => 'duplicateStory', 'type' => 'string'),
        'version'          => array('object' => 'story', 'field' => 'version', 'type' => 'string'),
        'storyChanged'     => array('object' => 'project', 'field' => 'storyChanged', 'type' => 'string'),
        'feedbackBy'       => array('object' => 'story', 'field' => 'feedbackBy', 'type' => 'string'),
        'notifyEmail'      => array('object' => 'story', 'field' => 'notifyEmail', 'type' => 'string'),
        'URChanged'        => array('object' => 'story', 'field' => 'URChanged', 'type' => 'string'),
        'deleted'          => array('object' => 'story', 'field' => 'deleted', 'type' => 'string'),
        'name'             => array('object' => 'product', 'field' => 'name', 'type' => 'string')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1005,
    'name'      => array('zh-cn' => '产品需求阶段分布表', 'zh-tw' => '產品需求階段分布表', 'en' => 'Story Stage', 'de' => 'Story Stage', 'fr' => 'Story Stage'),
    'code'      => 'productStoryStage',
    'desc'      => array('zh-cn' => '按照产品列出需求总数，研发阶段的分布情况。', 'zh-tw' => '按照產品列出需求總數，研發階段的分布情況。', 'en' => 'Total number and stage distribution of stories ', 'de' => 'Total number and stage distribution of stories ', 'fr' => 'Total number and stage distribution of stories '),
    'dimension' => '1',
    'group'     => '59',
    'sql'       => <<<EOT
select t1.*,t2.name from zt_story as t1
 left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stage', 'slice' => 'stage', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'fields'    => array
    (
        'id'               => array('object' => 'story', 'field' => 'id', 'type' => 'string'),
        'vision'           => array('object' => 'project', 'field' => 'vision', 'type' => 'string'),
        'parent'           => array('object' => 'story', 'field' => 'parent', 'type' => 'string'),
        'product'          => array('object' => 'story', 'field' => 'product', 'type' => 'string'),
        'branch'           => array('object' => 'story', 'field' => 'branch', 'type' => 'string'),
        'module'           => array('object' => 'story', 'field' => 'module', 'type' => 'string'),
        'plan'             => array('object' => 'story', 'field' => 'plan', 'type' => 'string'),
        'source'           => array('object' => 'story', 'field' => 'source', 'type' => 'string'),
        'sourceNote'       => array('object' => 'story', 'field' => 'sourceNote', 'type' => 'string'),
        'fromBug'          => array('object' => 'story', 'field' => 'fromBug', 'type' => 'string'),
        'feedback'         => array('object' => 'story', 'field' => 'feedback', 'type' => 'string'),
        'title'            => array('object' => 'story', 'field' => 'title', 'type' => 'string'),
        'keywords'         => array('object' => 'story', 'field' => 'keywords', 'type' => 'string'),
        'type'             => array('object' => 'story', 'field' => 'type', 'type' => 'string'),
        'category'         => array('object' => 'story', 'field' => 'category', 'type' => 'string'),
        'pri'              => array('object' => 'story', 'field' => 'pri', 'type' => 'string'),
        'estimate'         => array('object' => 'story', 'field' => 'estimate', 'type' => 'string'),
        'status'           => array('object' => 'story', 'field' => 'status', 'type' => 'string'),
        'subStatus'        => array('object' => 'story', 'field' => 'subStatus', 'type' => 'string'),
        'color'            => array('object' => 'story', 'field' => 'color', 'type' => 'string'),
        'stage'            => array('object' => 'story', 'field' => 'stage', 'type' => 'string'),
        'stagedBy'         => array('object' => 'story', 'field' => 'stagedBy', 'type' => 'string'),
        'mailto'           => array('object' => 'story', 'field' => 'mailto', 'type' => 'string'),
        'lib'              => array('object' => 'project', 'field' => 'lib', 'type' => 'string'),
        'fromStory'        => array('object' => 'project', 'field' => 'fromStory', 'type' => 'string'),
        'fromVersion'      => array('object' => 'project', 'field' => 'fromVersion', 'type' => 'string'),
        'openedBy'         => array('object' => 'story', 'field' => 'openedBy', 'type' => 'string'),
        'openedDate'       => array('object' => 'story', 'field' => 'openedDate', 'type' => 'string'),
        'assignedTo'       => array('object' => 'story', 'field' => 'assignedTo', 'type' => 'string'),
        'assignedDate'     => array('object' => 'story', 'field' => 'assignedDate', 'type' => 'string'),
        'approvedDate'     => array('object' => 'project', 'field' => 'approvedDate', 'type' => 'string'),
        'lastEditedBy'     => array('object' => 'story', 'field' => 'lastEditedBy', 'type' => 'string'),
        'lastEditedDate'   => array('object' => 'story', 'field' => 'lastEditedDate', 'type' => 'string'),
        'changedBy'        => array('object' => 'story', 'field' => 'changedBy', 'type' => 'string'),
        'changedDate'      => array('object' => 'story', 'field' => 'changedDate', 'type' => 'string'),
        'reviewedBy'       => array('object' => 'story', 'field' => 'reviewedBy', 'type' => 'string'),
        'reviewedDate'     => array('object' => 'story', 'field' => 'reviewedDate', 'type' => 'string'),
        'closedBy'         => array('object' => 'story', 'field' => 'closedBy', 'type' => 'string'),
        'closedDate'       => array('object' => 'story', 'field' => 'closedDate', 'type' => 'string'),
        'closedReason'     => array('object' => 'story', 'field' => 'closedReason', 'type' => 'string'),
        'activatedDate'    => array('object' => 'story', 'field' => 'activatedDate', 'type' => 'string'),
        'toBug'            => array('object' => 'story', 'field' => 'toBug', 'type' => 'string'),
        'childStories'     => array('object' => 'story', 'field' => 'childStories', 'type' => 'string'),
        'linkStories'      => array('object' => 'story', 'field' => 'linkStories', 'type' => 'string'),
        'linkRequirements' => array('object' => 'story', 'field' => 'linkRequirements', 'type' => 'string'),
        'twins'            => array('object' => 'story', 'field' => 'twins', 'type' => 'string'),
        'duplicateStory'   => array('object' => 'story', 'field' => 'duplicateStory', 'type' => 'string'),
        'version'          => array('object' => 'story', 'field' => 'version', 'type' => 'string'),
        'storyChanged'     => array('object' => 'project', 'field' => 'storyChanged', 'type' => 'string'),
        'feedbackBy'       => array('object' => 'story', 'field' => 'feedbackBy', 'type' => 'string'),
        'notifyEmail'      => array('object' => 'story', 'field' => 'notifyEmail', 'type' => 'string'),
        'URChanged'        => array('object' => 'story', 'field' => 'URChanged', 'type' => 'string'),
        'deleted'          => array('object' => 'story', 'field' => 'deleted', 'type' => 'string'),
        'name'             => array('object' => 'product', 'field' => 'name', 'type' => 'string')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1006,
    'name'      => array('zh-cn' => '产品发布数量统计表', 'zh-tw' => '產品發布數量統計表', 'en' => 'Product Release', 'de' => 'Product Release', 'fr' => 'Product Release'),
    'code'      => 'productRelease',
    'desc'      => array('zh-cn' => '按照产品列出发布的数量。', 'zh-tw' => '按照產品列出發布的數量。', 'en' => 'Product Release.', 'de' => 'Product Release.', 'fr' => 'Product Release.'),
    'dimension' => '1',
    'group'     => '59',
    'sql'       => <<<EOT
select t2.name, 1 as releases from zt_release as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'releases', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'fields'    => array
    (
        'name'     => array('object' => 'release', 'field' => 'name', 'type' => 'string'),
        'releases' => array('object' => 'product', 'field' => 'releases', 'type' => 'string')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1007,
    'name'      => array('zh-cn' => '任务状态统计表', 'zh-tw' => '任務狀態統計表', 'en' => 'Task Status Report', 'de' => 'Task Status Report', 'fr' => 'Task Status Report', 'vi' => 'Task Status Report', 'ja' => 'Task Status Report'),
    'code'      => 'taskStatus',
    'desc'      => array('zh-cn' => '按照执行统计任务的状态分布情况。', 'zh-tw' => '按照執行統計任務的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t1.name,t2.status,IF(t3.multiple="1",t1.name,"") as execution,t2.id as taskID,  t1.status as projectstatus, (case when t2.deadline < CURDATE() and t2.deadline != '0000-00-00' and t2.status != 'closed' and t2.status != 'done' and t2.status != 'cancel' then 1 else 0 end) as timeout from zt_project as t1
 left join zt_task as t2 on t1.id=t2.execution
 left join zt_project as t3 on t3.id=t1.project
 where t1.deleted='0' and t1.type in ('sprint','stage') and t2.deleted='0' and if(\$project='',1,t3.id=\$project) and if(\$status='',1,t1.status=\$status) and if(\$beginDate='',1,t1.begin>=\$beginDate) and if(\$endDate='',1,t1.end<=\$endDate)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => ''),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        'name'          => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'        => array('object' => 'project', 'field' => 'status', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'taskID'        => array('object' => 'task', 'field' => 'taskID', 'type' => 'string'),
        'projectstatus' => array('object' => 'task', 'field' => 'projectstatus', 'type' => 'string'),
        'timeout'       => array('object' => 'task', 'field' => 'timeout', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'status', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'date', 'date'),
        'selectList'  => array('project', 'project.status', 'user', 'user'),
        'default'     => array('', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1008,
    'name'      => array('zh-cn' => '任务类型统计表', 'zh-tw' => '任務類型統計表', 'en' => 'Task Type Report', 'de' => 'Task Type Report', 'fr' => 'Task Type Report', 'vi' => 'Task Type Report', 'ja' => 'Task Type Report'),
    'code'      => 'taskType',
    'desc'      => array('zh-cn' => '按照项目统计任务的类型分布情况。', 'zh-tw' => '按照項目統計任務的類型分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t3.name as project,IF(t3.multiple="1",t1.name,"") as execution,t2.type,t2.id as taskID, t1.status as projectstatus from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t1.type in ('sprint','stage') and t2.deleted='0' and if(\$project='',1,t3.id=\$project) and if(\$status='',1,t1.status=\$status) and if(\$beginDate='',1,t1.begin>=\$beginDate) and if(\$endDate='',1,t1.end<=\$endDate)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'type', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => ''),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'type'          => array('object' => 'task', 'field' => 'type', 'type' => 'option'),
        'taskID'        => array('object' => 'task', 'field' => 'taskID', 'type' => 'string'),
        'projectstatus' => array('object' => 'task', 'field' => 'projectstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'       => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'id'            => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'          => array('zh-cn' => '任务类型', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'taskID'        => array('zh-cn' => 'taskID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectstatus' => array('zh-cn' => 'projectstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'status', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'date', 'date'),
        'selectList'  => array('project', 'project.status', 'user', 'user'),
        'default'     => array('', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1009,
    'name'      => array('zh-cn' => '项目任务指派统计表', 'zh-tw' => '項目任務指派統計表', 'en' => 'Task Assign Report', 'de' => 'Task Assign Report', 'fr' => 'Task Assign Report', 'vi' => 'Task Assign Report', 'ja' => 'Task Assign Report'),
    'code'      => 'projectTaskAssign',
    'desc'      => array('zh-cn' => '按照项目统计任务的指派给分布情况。', 'zh-tw' => '按照項目統計任務的指派給分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t4.name as project,IF(t4.multiple="1",t1.name,"") as execution,if(t3.account is not null, t3.account,t2.assignedTo) as assignedTo,t2.id as taskID, t1.status as projectstatus from zt_project as t1
 left join zt_task as t2 on t1.id=t2.execution
 left join zt_team as t3 on t3.type='task' && t3.root=t2.id
left join zt_project as t4 on t1.project=t4.id
where t1.deleted='0' and t1.type in ('sprint','stage') and t2.deleted='0' and if(\$project='',1,t4.id=\$project) and if(\$status='',1,t1.status=\$status) and if(\$beginDate='',1,t1.begin>=\$beginDate) and if(\$endDate='',1,t1.end<=\$endDate)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'assignedTo', 'slice' => 'assignedTo', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => ''),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'assignedTo'    => array('object' => 'task', 'field' => 'assignedTo', 'type' => 'string'),
        'taskID'        => array('object' => 'team', 'field' => 'taskID', 'type' => 'string'),
        'projectstatus' => array('object' => 'team', 'field' => 'projectstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'assignedTo' => array('zh-cn' => '指派给'),
        'execution'  => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'status', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'date', 'date'),
        'selectList'  => array('project', 'project.status', 'user', 'user'),
        'default'     => array('', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1010,
    'name'      => array('zh-cn' => '项目任务完成者统计表', 'zh-tw' => '項目任務完成者統計表', 'en' => 'Task Finish Report', 'de' => 'Task Finish Report', 'fr' => 'Task Finish Report', 'vi' => 'Task Finish Report', 'ja' => 'Task Finish Report'),
    'code'      => 'projectTaskFinished',
    'desc'      => array('zh-cn' => '按照项目统计任务的完成者分布情况。', 'zh-tw' => '按照項目統計任務的完成者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t3.name as project,IF(t3.multiple="1",t1.name,"") as execution,t2.finishedBy,t2.id as taskID, t1.status as projectstatus from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_project as t3 on t1.project=t3.id
where t1.deleted='0' and t1.type in ('sprint','stage') and t2.deleted='0' and t2.finishedBy!='' and if(\$project='',1,t3.id=\$project) and if(\$status='',1,t1.status=\$status) and if(\$beginDate='',1,t1.begin>=\$beginDate) and if(\$endDate='',1,t1.end<=\$endDate)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'finishedBy', 'slice' => 'finishedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => ''),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'finishedBy'    => array('object' => 'task', 'field' => 'finishedBy', 'type' => 'string'),
        'taskID'        => array('object' => 'task', 'field' => 'taskID', 'type' => 'string'),
        'projectstatus' => array('object' => 'task', 'field' => 'projectstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'status', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'date', 'date'),
        'selectList'  => array('project', 'project.status', 'user', 'user'),
        'default'     => array('', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1011,
    'name'      => array('zh-cn' => '项目投入统计表', 'zh-tw' => '項目投入統計表', 'en' => 'Project Invest Report', 'de' => 'Project Invest Report', 'fr' => 'Project Invest Report', 'vi' => 'Project Invest Report', 'ja' => 'Project Invest Report'),
    'code'      => 'projectInvested',
    'desc'      => array('zh-cn' => '按照项目列出：任务数，需求数，人数，总消耗工时。', 'zh-tw' => '按照項目列出：任務數，需求數，人數，總消耗工時。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t5.name as project,IF(t5.multiple="1",t1.name,"") as execution,CONCAT(t1.begin,' ~ ',t1.end) as timeLimit,t2.teams,t3.stories,round(t4.consumed,1) as consumed,t4.number, t1.status as projectstatus
from zt_project as t1
 left join ztv_projectteams as t2 on t1.id=t2.execution
left join ztv_projectstories as t3 on t1.id=t3.execution
 left join ztv_executionsummary as t4 on t1.id=t4.execution
left join zt_project as t5 on t1.project=t5.id
where t1.deleted='0' and t1.type in ('sprint','stage') and if(\$project='',1,t5.id=\$project) and if(\$status='',1,t1.status=\$status) and if(\$beginDate='',1,t1.begin>=\$beginDate) and if(\$endDate='',1,t1.end<=\$endDate)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'number', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'teams', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => ''),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'timeLimit'     => array('object' => 'project', 'field' => 'timeLimit', 'type' => 'string'),
        'teams'         => array('object' => 'project', 'field' => 'teams', 'type' => 'string'),
        'stories'       => array('object' => 'project', 'field' => 'stories', 'type' => 'string'),
        'consumed'      => array('object' => 'project', 'field' => 'consumed', 'type' => 'string'),
        'number'        => array('object' => 'project', 'field' => 'number', 'type' => 'string'),
        'projectstatus' => array('object' => 'project', 'field' => 'projectstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'timeLimit' => array('zh-cn' => '工期'),
        'teams'     => array('zh-cn' => '人数'),
        'stories'   => array('zh-cn' => '需求数'),
        'consumed'  => array('zh-cn' => '总消耗'),
        'number'    => array('zh-cn' => '任务数'),
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'status', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'date', 'date'),
        'selectList'  => array('project', 'project.status', 'user', 'user'),
        'default'     => array('', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1012,
    'name'      => array('zh-cn' => '项目需求状态分布表', 'zh-tw' => '項目需求狀態分布表', 'en' => 'Project Story Status', 'de' => 'Project Story Status', 'fr' => 'Project Story Status', 'vi' => 'Project Story Status', 'ja' => 'Project Story Status'),
    'code'      => 'projectStoryStatus',
    'desc'      => array('zh-cn' => '按照项目统计需求的状态分布情况。', 'zh-tw' => '按照項目統計需求的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t2.id, t4.name as project,IF(t4.multiple="1",t2.name,"") as execution,t3.status from zt_projectstory as t1
left join zt_project as t2 on t1.project=t2.id
left join zt_story as t3 on t1.story=t3.id
left join zt_project as t4 on t4.id=t2.project
where t2.deleted='0' and t2.type in('sprint', 'stage') and if(\$project='',1,t4.id=\$project) and if(\$execution='',1,t2.id=\$execution) and if(\$status='',1,t2.status=\$status)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => '')
    ),
    'fields'    => array
    (
        'id'        => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'   => array('object' => 'projectstory', 'field' => 'project', 'type' => 'string'),
        'execution' => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'status'    => array('object' => 'story', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution', 'status'),
        'showName'    => array('项目列表', '执行列表', '执行状态'),
        'requestType' => array('select', 'select', 'select'),
        'selectList'  => array('project', 'execution', 'project.status'),
        'default'     => array('', '', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1013,
    'name'      => array('zh-cn' => '项目需求阶段分布表', 'zh-tw' => '項目需求階段分布表', 'en' => 'Project Stage Report', 'de' => 'Project Stage Report', 'fr' => 'Project Stage Report', 'vi' => 'Project Stage Report', 'ja' => 'Project Stage Report'),
    'code'      => 'projectStoryStage',
    'desc'      => array('zh-cn' => '按照项目统计需求阶段分布情况。', 'zh-tw' => '按照項目統計需求階段分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t2.id, t4.name as project,IF(t4.multiple="1",t2.name,"") as execution,t3.stage from zt_projectstory as t1
left join zt_project as t2 on t1.project=t2.id
left join zt_story as t3 on t1.story=t3.id
left join zt_project as t4 on t4.id=t2.project
where t2.deleted='0' and t2.type in('sprint', 'stage') and if(\$project='',1,t4.id=\$project) and if(\$execution='',1,t2.id=\$execution) and if(\$status='',1,t2.status=\$status)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stage', 'slice' => 'stage', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => '')
    ),
    'fields'    => array
    (
        'id'        => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'   => array('object' => 'projectstory', 'field' => 'project', 'type' => 'string'),
        'execution' => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'stage'     => array('object' => 'story', 'field' => 'stage', 'type' => 'option')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'id'        => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stage'     => array('zh-cn' => '阶段', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution', 'status'),
        'showName'    => array('项目列表', '执行列表', '执行状态'),
        'requestType' => array('select', 'select', 'select'),
        'selectList'  => array('project', 'execution', 'project.status'),
        'default'     => array('', '', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1014,
    'name'      => array('zh-cn' => '项目Bug解决方案分布表', 'zh-tw' => '項目Bug解決方案分布表', 'en' => 'Project Bug Resolution', 'de' => 'Project Bug Resolution', 'fr' => 'Project Bug Resolution', 'vi' => 'Project Bug Resolution', 'ja' => 'Project Bug Resolution'),
    'code'      => 'projectBugResolution',
    'desc'      => array('zh-cn' => '按照项目统计Bug的解决方案分布情况。', 'zh-tw' => '按照項目統計Bug的解決方案分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t3.id as projectID,IF(t3.multiple="1",t1.name,"") as execution,t1.id as bugID,t2.resolution from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
 where t1.deleted='0' and t2.deleted='0' and t2.resolution!='' having bugID!='' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'         => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'    => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'       => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution'  => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'      => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'resolution' => array('object' => 'bug', 'field' => 'resolution', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1015,
    'name'      => array('zh-cn' => '项目Bug状态分布表', 'zh-tw' => '項目Bug狀態分布表', 'en' => 'Project Bug Status', 'de' => 'Project Bug Status', 'fr' => 'Project Bug Status', 'vi' => 'Project Bug Status', 'ja' => 'Project Bug Status'),
    'code'      => 'projectBugStatus',
    'desc'      => array('zh-cn' => '按照项目统计Bug的状态分布情况。', 'zh-tw' => '按照項目統計Bug的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t3.id as projectID,IF(t3.multiple="1",t1.name,"") as execution,t1.id as bugID,t2.status from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t2.deleted='0' having bugID!=' ' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'        => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'   => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'      => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution' => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'     => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'status'    => array('object' => 'bug', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1016,
    'name'      => array('zh-cn' => '项目Bug创建者分布表', 'zh-tw' => '項目Bug創建者分布表', 'en' => 'Project Bug Opened', 'de' => 'Project Bug Opened', 'fr' => 'Project Bug Opened', 'vi' => 'Project Bug Opened', 'ja' => 'Project Bug Opened'),
    'code'      => 'projectBugOpenedBy',
    'desc'      => array('zh-cn' => '按照项目统计Bug的创建者分布情况。', 'zh-tw' => '按照項目統計Bug的創建者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t3.id as projectID,IF(t3.multiple="1",t1.name,"") as execution,t1.id as bugID,t2.openedBy from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t2.deleted='0' having bugID!='' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'openedBy', 'slice' => 'openedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'        => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'   => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'      => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution' => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'     => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'openedBy'  => array('object' => 'project', 'field' => 'openedBy', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1017,
    'name'      => array('zh-cn' => '项目Bug解决者分布表', 'zh-tw' => '項目Bug解決者分布表', 'en' => 'Project Bug Resolve', 'de' => 'Project Bug Resolve', 'fr' => 'Project Bug Resolve', 'vi' => 'Project Bug Resolve', 'ja' => 'Project Bug Resolve'),
    'code'      => 'projectBugResolvedBy',
    'desc'      => array('zh-cn' => '按照项目统计Bug的解决者分布情况。', 'zh-tw' => '按照項目統計Bug的解決者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t3.id as projectID,IF(t3.multiple="1",t1.name,"") as execution,t1.id as bugID,t2.resolvedBy from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t2.deleted='0' and t2.status!='active' and t2.resolvedBy!='' having bugID!='' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'resolvedBy', 'slice' => 'resolvedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'         => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'    => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'       => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution'  => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'      => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'resolvedBy' => array('object' => 'bug', 'field' => 'resolvedBy', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1018,
    'name'      => array('zh-cn' => '项目Bug指派给分布表', 'zh-tw' => '項目Bug指派給分布表', 'en' => 'Project Bug Assign', 'de' => 'Project Bug Assign', 'fr' => 'Project Bug Assign', 'vi' => 'Project Bug Assign', 'ja' => 'Project Bug Assign'),
    'code'      => 'projectBugAssignedBy',
    'desc'      => array('zh-cn' => '按照项目统计Bug的指派给分布情况。', 'zh-tw' => '按照項目統計Bug的指派給分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,t3.id as projectID,IF(t3.multiple="1",t1.name,"") as execution,t1.id as bugID,t2.assignedTo from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t2.deleted='0' having bugID!='' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'assignedTo', 'slice' => 'assignedTo', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'         => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'    => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'       => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution'  => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'      => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'assignedTo' => array('object' => 'bug', 'field' => 'assignedTo', 'type' => 'string')
    ),
    'langs'     => array
    (
        'project'   => array('zh-cn' => '项目名称'),
        'execution' => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1019,
    'name'      => array('zh-cn' => '项目质量表', 'zh-tw' => '項目質量表', 'en' => 'Project Quality Report', 'de' => 'Project Quality Report', 'fr' => 'Project Quality Report', 'vi' => 'Project Quality Report', 'ja' => 'Project Quality Report'),
    'code'      => 'projectQuality',
    'desc'      => array('zh-cn' => '列出项目的需求总数，完成需求数，任务总数，完成的任务数，Bug数，解决的Bug数，Bug/需求，Bug/任务，重要Bug数量(严重程度不大于3）。', 'zh-tw' => '列出項目的需求總數，完成需求數，任務總數，完成的任務數，Bug數，解決的Bug數，Bug/需求，Bug/任務，重要Bug數量(嚴重程度不大於3）。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id, t5.name as project,IF(t5.multiple="1",t1.name,"") as execution,t2.stories,(t2.stories-t2.undone) as doneStory,t3.number,(t3.number-t3.undone) as doneTask,t4.bugs,t4.resolutions, round(t4.bugs/(t2.stories-t2.undone),2) as bugthanstory,round(t4.bugs/(t3.number-t3.undone),2) as bugthantask,t4.seriousBugs from zt_project as t1
left join ztv_projectstories as t2 on t1.id=t2.execution
left join ztv_executionsummary as t3 on t1.id=t3.execution
left join ztv_projectbugs as t4 on t1.id=t4.execution
left join zt_project as t5 on t5.id=t1.project
where t1.deleted='0' and t1.type in ('sprint','stage') and t1.grade='1' and if(\$project='',1,t5.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'doneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'number', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'doneTask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'resolutions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugthanstory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugthantask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'seriousBugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'           => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'      => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't5id'         => array('object' => 'project', 'field' => 't5id', 'type' => 'string'),
        'execution'    => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'stories'      => array('object' => 'project', 'field' => 'stories', 'type' => 'string'),
        'doneStory'    => array('object' => 'project', 'field' => 'doneStory', 'type' => 'string'),
        'number'       => array('object' => 'project', 'field' => 'number', 'type' => 'string'),
        'doneTask'     => array('object' => 'project', 'field' => 'doneTask', 'type' => 'string'),
        'bugs'         => array('object' => 'project', 'field' => 'bugs', 'type' => 'string'),
        'resolutions'  => array('object' => 'project', 'field' => 'resolutions', 'type' => 'string'),
        'bugthanstory' => array('object' => 'project', 'field' => 'bugthanstory', 'type' => 'string'),
        'bugthantask'  => array('object' => 'project', 'field' => 'bugthantask', 'type' => 'string'),
        'seriousBugs'  => array('object' => 'project', 'field' => 'seriousBugs', 'type' => 'string')
    ),
    'langs'     => array
    (
        'stories'            => array('zh-cn' => '需求总数'),
        'doneStory'          => array('zh-cn' => '完成需求数'),
        'number'             => array('zh-cn' => '任务总数'),
        'doneTask'           => array('zh-cn' => '完成任务数'),
        'bugs'               => array('zh-cn' => 'Bug数'),
        'resolutions'        => array('zh-cn' => '解决Bug数'),
        'bugthanstory'       => array('zh-cn' => 'Bug/完成需求'),
        'bugthantask'        => array('zh-cn' => 'Bug/完成任务'),
        'seriousBugs'        => array('zh-cn' => '重要Bug数'),
        'seriousBugsPercent' => array('zh-cn' => '严重Bug比率'),
        'project'            => array('zh-cn' => '项目名称'),
        'execution'          => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1020,
    'name'      => array('zh-cn' => '产品Bug类型统计表', 'zh-tw' => '產品Bug類型統計表', 'en' => 'Bug Type of Product', 'de' => 'Bug Type of Product', 'fr' => 'Bug Type of Product'),
    'code'      => 'productBugType',
    'desc'      => array('zh-cn' => '按照产品统计Bug的类型分布情况。', 'zh-tw' => '按照產品統計Bug的類型分布情況。', 'en' => 'Type distribution of Bugs.', 'de' => 'Type distribution of Bugs.', 'fr' => 'Type distribution of Bugs.'),
    'dimension' => '1',
    'group'     => '59,61',
    'sql'       => <<<EOT
select t1.id,t1.name,t2.id as bugID,t2.type from zt_product as t1
left join zt_bug as t2 on t1.id=t2.product
left join zt_project as t3 on t1.program=t3.id
where t1.deleted='0' and t2.deleted='0'
order by t3.`order` asc, t1.line desc, t1.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'type', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'fields'    => array
    (
        'id'    => array('object' => 'product', 'field' => 'id', 'type' => 'string'),
        'name'  => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'bugID' => array('object' => 'project', 'field' => 'bugID', 'type' => 'string'),
        'type'  => array('object' => 'bug', 'field' => 'type', 'type' => 'option')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done'),
        'id'    => array('zh-cn' => '编号', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'  => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID' => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'  => array('zh-cn' => 'Bug类型', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1021,
    'name'      => array('zh-cn' => '产品质量表', 'zh-tw' => '產品質量表', 'en' => 'Product Quality', 'de' => 'Product Quality', 'fr' => 'Product Quality'),
    'code'      => 'productQuality',
    'desc'      => array('zh-cn' => '列出产品的需求数，完成的需求总数，Bug数，解决的Bug总数，Bug/需求，重要Bug数量(严重程度小于3)。', 'zh-tw' => '列出產品的需求數，完成的需求總數，Bug數，解決的Bug總數，Bug/需求，重要Bug數量(嚴重程度小於3)。', 'en' => 'Serious Bug (severity is less than 3).', 'de' => 'Serious Bug (severity is less than 3).', 'fr' => 'Serious Bug (severity is less than 3).'),
    'dimension' => '1',
    'group'     => '59',
    'sql'       => <<<EOT
select t1.id,t1.name,t2.stories,(t2.stories-t2.undone) as doneStory,t3.bugs,t3.resolutions,round(t3.bugs/(t2.stories-t2.undone),2) as bugthanstory,t3.seriousBugs from zt_product as t1
left join ztv_productstories as t2 on t1.id=t2.product
left join ztv_productbugs as t3 on t1.id=t3.product
left join zt_project as t4 on t1.program=t4.id
where t1.deleted='0' and t1.vision like '%rnd%'
order by t4.`order` asc, t1.line desc, t1.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'doneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'resolutions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugthanstory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'seriousBugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '1')
        )
    ),
    'fields'    => array
    (
        'id'           => array('object' => 'product', 'field' => 'id', 'type' => 'string'),
        'name'         => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'stories'      => array('object' => 'project', 'field' => 'stories', 'type' => 'string'),
        'doneStory'    => array('object' => 'project', 'field' => 'doneStory', 'type' => 'string'),
        'bugs'         => array('object' => 'product', 'field' => 'bugs', 'type' => 'string'),
        'resolutions'  => array('object' => 'project', 'field' => 'resolutions', 'type' => 'string'),
        'bugthanstory' => array('object' => 'project', 'field' => 'bugthanstory', 'type' => 'string'),
        'seriousBugs'  => array('object' => 'project', 'field' => 'seriousBugs', 'type' => 'string')
    ),
    'langs'     => array
    (
        'stories'            => array('zh-cn' => '需求总数', 'zh-tw' => '需求总数', 'en' => 'Stories'),
        'doneStory'          => array('zh-cn' => '完成需求数', 'zh-tw' => '完成需求数', 'en' => 'Finished Stories'),
        'bugs'               => array('zh-cn' => 'Bug数', 'zh-tw' => 'Bug数', 'en' => 'Bugs'),
        'resolutions'        => array('zh-cn' => '解决Bug数', 'zh-tw' => '解决Bug数', 'en' => 'Solved Bugs'),
        'bugthanstory'       => array('zh-cn' => 'Bug/完成需求', 'zh-tw' => 'Bug/完成需求', 'en' => 'Bug/Finished Story'),
        'seriousBugs'        => array('zh-cn' => '重要Bug数', 'zh-tw' => '重要Bug数', 'en' => 'Serious Bugs'),
        'seriousBugsPercent' => array('zh-cn' => '严重bug比率', 'zh-tw' => '严重bug比率', 'en' => 'Serious Bugs %')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1022,
    'name'      => array('zh-cn' => '员工登录次数统计表', 'zh-tw' => '員工登錄次數統計表', 'en' => 'Login Times', 'de' => 'Login Times', 'fr' => 'Login Times'),
    'code'      => 'loginTimes',
    'desc'      => array('zh-cn' => '实现员工登录次数统计报表，按照天统计每天每个人的登录次数，以及总数。', 'zh-tw' => '實現員工登錄次數統計報表，按照天統計每天每個人的登錄次數，以及總數。', 'en' => 'The summary of user login times.', 'de' => 'The summary of user login times.', 'fr' => 'The summary of user login times.'),
    'dimension' => '1',
    'group'     => '62',
    'sql'       => <<<EOT
select actor,LEFT(`date`,10) as `day` from zt_action where `action`='login' and if(\$startDate='',1,`date`>=\$startDate) and if(\$endDate='',1,`date`<=\$endDate) order by `date` asc, actor asc
EOT,
    'settings'  => array
    (
        'group1'      => 'actor',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'day', 'slice' => 'day', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        ),
        'lastStep'    => '4'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'actor' => array('object' => 'action', 'field' => 'actor', 'type' => 'user', 'name' => '操作者'),
        'day'   => array('object' => 'action', 'field' => 'day', 'type' => 'string', 'name' => 'day')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done'),
        'actor' => array('zh-cn' => '操作者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'day'   => array('zh-cn' => 'day', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('startDate', 'endDate'),
        'showName'    => array('起始时间', '结束时间'),
        'requestType' => array('date', 'date'),
        'selectList'  => array('user', 'user'),
        'default'     => array('$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1023,
    'name'      => array('zh-cn' => '日志汇总表', 'zh-tw' => '日誌匯總表', 'en' => 'Effort Summary', 'de' => 'Effort Summary', 'fr' => 'Effort Summary'),
    'code'      => 'effortSummary',
    'desc'      => array('zh-cn' => '查看某个时间段内的日志情况，可以按照部门选择。', 'zh-tw' => '查看某個時間段內的日誌情況，可以按照部門選擇。', 'en' => 'Effort summary of users.', 'de' => 'Effort summary of users', 'fr' => 'Effort summary of users'),
    'dimension' => '1',
    'group'     => '62',
    'sql'       => <<<EOT
select t1.account, t1.consumed, t1.`date`, if(\$dept='', 0, t2.dept) as dept from zt_effort as t1 left join zt_user as t2 on t1.account = t2.account left join zt_dept as t3 on t2.dept = t3.id where t1.`deleted` = '0' and if(\$startDate='', 1, t1.`date` >= \$startDate) and if(\$endDate='', 1, t1.`date` <= \$endDate) and (t3.path like concat((select path from zt_dept where id=\$dept), '%') or \$dept=0) order by t1.`date` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'account',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'consumed', 'slice' => 'date', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        ),
        'lastStep'    => '4'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'dept', 'name' => '部门', 'type' => 'select', 'typeOption' => 'dept', 'default' => ''),
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'account'  => array('object' => 'effort', 'field' => 'account', 'type' => 'user', 'name' => 'account'),
        'consumed' => array('object' => 'effort', 'field' => 'consumed', 'type' => 'number', 'name' => 'consumed'),
        'date'     => array('object' => 'effort', 'field' => 'date', 'type' => 'object', 'name' => 'date'),
        'dept'     => array('object' => 'effort', 'field' => 'dept', 'type' => 'object', 'name' => 'dept')
    ),
    'langs'     => array
    (
        'date'     => array('zh-cn' => '日期', 'zh-tw' => '日期', 'en' => 'Date', 'de' => '', 'fr' => ''),
        'consumed' => array('zh-cn' => '消耗工时', 'zh-tw' => '消耗工时', 'en' => 'Cost', 'de' => '', 'fr' => ''),
        'account'  => array('zh-cn' => '名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'dept'     => array('zh-cn' => 'dept', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('dept', 'startDate', 'endDate'),
        'showName'    => array('部门', '起始时间', '结束时间'),
        'requestType' => array('select', 'date', 'date'),
        'selectList'  => array('dept', 'user', 'user'),
        'default'     => array('', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1024,
    'name'      => array('zh-cn' => '公司动态汇总表', 'zh-tw' => '公司動態匯總表', 'en' => 'Company Dynamics', 'de' => 'Company Dynamics', 'fr' => 'Company Dynamics'),
    'code'      => 'companyDynamics',
    'desc'      => array('zh-cn' => '可以指定一个时期，列出相应的数据：1. 每天的登录次数。2. 每天的日志工时量。3. 每天新增的需求数。4. 每天关闭的需求数。5. 每天新增的任务数。6. 每天完成的任务数。7. 每天新增的Bug数。8. 每天解决的Bug数。9. 每天的动态数。', 'zh-tw' => '可以指定一個時期，列出相應的數據：1. 每天的登錄次數。2. 每天的日誌工時量。3. 每天新增的需求數。4. 每天關閉的需求數。5. 每天新增的任務數。6. 每天完成的任務數。7. 每天新增的Bug數。8. 每天解決的Bug數。9. 每天的動態數。', 'en' => 'The summary of company dynamics', 'de' => 'The summary of company dynamics', 'fr' => 'The summary of company dynamics'),
    'dimension' => '1',
    'group'     => '62',
    'sql'       => <<<'EOT'
select
    t1.day,
    sum(t1.userlogin) as userlogin,
    sum(t1.consumed) as consumed,
    sum(t1.storyopen) as storyopen,
    sum(t1.storyclose) as storyclose,
    sum(t1.taskopen) as taskopen,
    sum(t1.taskfinish) as taskfinish,
    sum(t1.bugopen) as bugopen,
    sum(t1.bugresolve) as bugresolve,
    sum(t1.actions) as actions
from (
	select
        cast(t1.date as date) as day,
        count(case when t1.objectType = 'user' and t1.action = 'login' then 1 end) as userlogin,
        0 as consumed,
        0 as storyopen,
        0 as storyclose,
        0 as taskopen,
        0 as taskfinish,
        0 as bugopen,
        0 as bugresolve,
        count(*) as actions
    from zt_action t1
    where if($startDate='',1,t1.date>=$startDate) and if($endDate='',1,t1.date<=$endDate)
    group by cast(t1.date as date)
    union all
    select
        cast(t1.date as date) as day,
        0 as userlogin,
        0 as consumed,
        sum(case when t1.action = 'opened' then 1 else 0 end) as storyopen,
        count(distinct case when t1.action = 'closed' and t2.status = 'closed' then t2.id else 0 end) - 1 as storyclose,
        0 as taskopen,
        0 as taskfinish,
        0 as bugopen,
        0 as bugresolve,
        0 as actions
    from zt_action t1
    left join zt_story t2 on t1.objectID = t2.id
    where t2.deleted = '0' and t1.objectType = 'story' and t1.action in ('opened', 'closed') and if($startDate='',1,t1.date>=$startDate) and if($endDate='',1,t1.date<=$endDate)
    group by cast(t1.date as date)
    union all
    select
        cast(t1.date as date) as day,
        0 as userlogin,
        0 as consumed,
        0 as storyopen,
        0 as storyclose,
        sum(case when t1.action = 'opened' then 1 else 0 end) as taskopen,
        count(distinct case when t1.action = 'finished' and t2.status in ('done', 'closed') then t2.id else 0 end) - 1 as taskfinish,
        0 as bugopen,
        0 as bugresolve,
        0 as actions
    from zt_action t1
    left join zt_task t2 on t1.objectID = t2.id
    where t2.deleted = '0' and t1.objectType = 'task' and t1.action in ('opened', 'finished') and if($startDate='',1,t1.date>=$startDate) and if($endDate='',1,t1.date<=$endDate)
    group by cast(t1.date as date)
    union all
    select
        cast(t1.date as date) as day,
        0 as userlogin,
        0 as consumed,
        0 as storyopen,
        0 as storyclose,
        0 as taskopen,
        0 as taskfinish,
        sum(case when t1.action = 'opened' then 1 else 0 end) as bugopen,
        count(distinct case when t1.action = 'resolved' and t2.status in ('resolved', 'closed') then t2.id else 0 end) - 1 as bugresolve,
        0 as actions
    from zt_action t1
    left join zt_bug t2 on t1.objectID = t2.id
    where t2.deleted = '0' and t1.objectType = 'bug' and t1.action in ('opened', 'resolved') and if($startDate='',1,t1.date>=$startDate) and if($endDate='',1,t1.date<=$endDate)
    group by cast(t1.date as date)
    union all
    select
        t2.date as day,
        0 as userlogin,
        round(sum(t2.`consumed`), 1) as consumed,
        0 as storyopen,
        0 as storyclose,
        0 as taskopen,
        0 as taskfinish,
        0 as bugopen,
        0 as bugresolve,
        0 as actions
    from zt_effort t2
    where if($startDate='',1,t2.date>=$startDate) and if($endDate='',1,t2.date<=$endDate)
    group by t2.date
) as t1
group by t1.day
order by t1.day desc limit 99999999
EOT,
    'settings'  => array
    (
        'group1'      => 'day',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'userlogin', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'storyopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'storyclose', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'taskopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'taskfinish', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'bugresolve', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'actions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'day'        => array('object' => '', 'field' => 'day', 'type' => 'string'),
        'userlogin'  => array('object' => '', 'field' => 'userlogin', 'type' => 'string'),
        'consumed'   => array('object' => '', 'field' => 'consumed', 'type' => 'string'),
        'storyopen'  => array('object' => '', 'field' => 'storyopen', 'type' => 'string'),
        'storyclose' => array('object' => '', 'field' => 'storyclose', 'type' => 'string'),
        'taskopen'   => array('object' => '', 'field' => 'taskopen', 'type' => 'string'),
        'taskfinish' => array('object' => '', 'field' => 'taskfinish', 'type' => 'string'),
        'bugopen'    => array('object' => '', 'field' => 'bugopen', 'type' => 'string'),
        'bugresolve' => array('object' => '', 'field' => 'bugresolve', 'type' => 'string'),
        'actions'    => array('object' => '', 'field' => 'actions', 'type' => 'string')
    ),
    'langs'     => array
    (
        'day'        => array('zh-cn' => '日期', 'zh-tw' => '日期', 'en' => 'Date'),
        'userlogin'  => array('zh-cn' => '登录次数', 'zh-tw' => '登錄次數', 'en' => 'Login'),
        'consumed'   => array('zh-cn' => '日志工时', 'zh-tw' => '日誌工時', 'en' => 'Cost(h)'),
        'storyopen'  => array('zh-cn' => '新增需求数', 'zh-tw' => '新增需求數', 'en' => 'Open Story'),
        'storyclose' => array('zh-cn' => '关闭需求数', 'zh-tw' => '關閉需求數', 'en' => 'Closed Story'),
        'taskopen'   => array('zh-cn' => '新增任务数', 'zh-tw' => '新增任務數', 'en' => 'Open Task'),
        'taskfinish' => array('zh-cn' => '完成任务数', 'zh-tw' => '完成任務數', 'en' => 'Finished Task'),
        'bugopen'    => array('zh-cn' => '新增Bug数', 'zh-tw' => '新增Bug數', 'en' => 'Open Bug'),
        'bugresolve' => array('zh-cn' => '解决Bug数', 'zh-tw' => '解决Bug數', 'en' => 'Resolved bug'),
        'actions'    => array('zh-cn' => '动态数', 'zh-tw' => '動態數', 'en' => 'Dynamics')
    ),
    'vars'      => array
    (
        'varName'     => array('startDate', 'endDate'),
        'showName'    => array('起始时间', '结束时间'),
        'requestType' => array('date', 'date'),
        'selectList'  => array('user', 'user'),
        'default'     => array('$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1025,
    'name'      => array('zh-cn' => 'Bug解决表', 'zh-tw' => 'Bug解決表', 'en' => 'Solved Bugs', 'de' => 'Solved Bugs', 'fr' => 'Solved Bugs'),
    'code'      => 'slovedBugs',
    'desc'      => array('zh-cn' => '列出解决的Bug总数，解决方案的分布，占的比例（该用户解决的Bug的数量占所有的解决的Bug的数量)。', 'zh-tw' => '列出解決的Bug總數，解決方案的分布，占的比例（該用戶解決的Bug的數量占所有的解決的Bug的數量)。', 'en' => 'percentage:self resolved / all resolved', 'de' => 'percentage:self resolved / all resolved', 'fr' => 'percentage:self resolved / all resolved'),
    'dimension' => '1',
    'group'     => '61',
    'sql'       => <<<EOT
select t1.*,if(\$product='',0,t1.product) as customproduct from zt_bug as t1 left join zt_product as t2 on t1.product = t2.id where t1.deleted='0' and t2.deleted='0' and t1.resolution!='' and if(\$startDate='',1,t1.resolvedDate>=\$startDate) and if(\$endDate='',1,t1.resolvedDate<=\$endDate) having customproduct=\$product
EOT,
    'settings'  => array
    (
        'group1'      => 'resolvedBy',
        'group2'      => '',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'total', 'monopolize' => '1')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'product', 'name' => '产品', 'type' => 'select', 'typeOption' => 'product', 'default' => ''),
        array('from' => 'query', 'field' => 'startDate', 'name' => '解决日期开始', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '解决日期结束', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'id'             => array('object' => 'bug', 'field' => 'id', 'type' => 'string'),
        'project'        => array('object' => 'bug', 'field' => 'project', 'type' => 'string'),
        'product'        => array('object' => 'bug', 'field' => 'product', 'type' => 'string'),
        'injection'      => array('object' => 'bug', 'field' => 'injection', 'type' => 'string'),
        'identify'       => array('object' => 'bug', 'field' => 'identify', 'type' => 'string'),
        'branch'         => array('object' => 'bug', 'field' => 'branch', 'type' => 'string'),
        'module'         => array('object' => 'bug', 'field' => 'module', 'type' => 'string'),
        'execution'      => array('object' => 'bug', 'field' => 'execution', 'type' => 'string'),
        'plan'           => array('object' => 'bug', 'field' => 'plan', 'type' => 'string'),
        'story'          => array('object' => 'bug', 'field' => 'story', 'type' => 'string'),
        'storyVersion'   => array('object' => 'bug', 'field' => 'storyVersion', 'type' => 'string'),
        'task'           => array('object' => 'bug', 'field' => 'task', 'type' => 'string'),
        'toTask'         => array('object' => 'bug', 'field' => 'toTask', 'type' => 'string'),
        'toStory'        => array('object' => 'bug', 'field' => 'toStory', 'type' => 'string'),
        'title'          => array('object' => 'bug', 'field' => 'title', 'type' => 'string'),
        'keywords'       => array('object' => 'bug', 'field' => 'keywords', 'type' => 'string'),
        'severity'       => array('object' => 'bug', 'field' => 'severity', 'type' => 'string'),
        'pri'            => array('object' => 'bug', 'field' => 'pri', 'type' => 'string'),
        'type'           => array('object' => 'bug', 'field' => 'type', 'type' => 'string'),
        'os'             => array('object' => 'bug', 'field' => 'os', 'type' => 'string'),
        'browser'        => array('object' => 'bug', 'field' => 'browser', 'type' => 'string'),
        'hardware'       => array('object' => 'bug', 'field' => 'hardware', 'type' => 'string'),
        'found'          => array('object' => 'bug', 'field' => 'found', 'type' => 'string'),
        'steps'          => array('object' => 'bug', 'field' => 'steps', 'type' => 'string'),
        'status'         => array('object' => 'bug', 'field' => 'status', 'type' => 'string'),
        'subStatus'      => array('object' => 'bug', 'field' => 'subStatus', 'type' => 'string'),
        'color'          => array('object' => 'bug', 'field' => 'color', 'type' => 'string'),
        'confirmed'      => array('object' => 'bug', 'field' => 'confirmed', 'type' => 'string'),
        'activatedCount' => array('object' => 'bug', 'field' => 'activatedCount', 'type' => 'string'),
        'activatedDate'  => array('object' => 'bug', 'field' => 'activatedDate', 'type' => 'string'),
        'feedbackBy'     => array('object' => 'bug', 'field' => 'feedbackBy', 'type' => 'string'),
        'notifyEmail'    => array('object' => 'bug', 'field' => 'notifyEmail', 'type' => 'string'),
        'mailto'         => array('object' => 'bug', 'field' => 'mailto', 'type' => 'string'),
        'openedBy'       => array('object' => 'bug', 'field' => 'openedBy', 'type' => 'string'),
        'openedDate'     => array('object' => 'bug', 'field' => 'openedDate', 'type' => 'string'),
        'openedBuild'    => array('object' => 'bug', 'field' => 'openedBuild', 'type' => 'string'),
        'assignedTo'     => array('object' => 'bug', 'field' => 'assignedTo', 'type' => 'string'),
        'assignedDate'   => array('object' => 'bug', 'field' => 'assignedDate', 'type' => 'string'),
        'deadline'       => array('object' => 'bug', 'field' => 'deadline', 'type' => 'string'),
        'resolvedBy'     => array('object' => 'bug', 'field' => 'resolvedBy', 'type' => 'string'),
        'resolution'     => array('object' => 'bug', 'field' => 'resolution', 'type' => 'string'),
        'resolvedBuild'  => array('object' => 'bug', 'field' => 'resolvedBuild', 'type' => 'string'),
        'resolvedDate'   => array('object' => 'bug', 'field' => 'resolvedDate', 'type' => 'string'),
        'closedBy'       => array('object' => 'bug', 'field' => 'closedBy', 'type' => 'string'),
        'closedDate'     => array('object' => 'bug', 'field' => 'closedDate', 'type' => 'string'),
        'duplicateBug'   => array('object' => 'bug', 'field' => 'duplicateBug', 'type' => 'string'),
        'linkBug'        => array('object' => 'bug', 'field' => 'linkBug', 'type' => 'string'),
        'case'           => array('object' => 'bug', 'field' => 'case', 'type' => 'string'),
        'caseVersion'    => array('object' => 'bug', 'field' => 'caseVersion', 'type' => 'string'),
        'feedback'       => array('object' => 'bug', 'field' => 'feedback', 'type' => 'string'),
        'result'         => array('object' => 'bug', 'field' => 'result', 'type' => 'string'),
        'repo'           => array('object' => 'bug', 'field' => 'repo', 'type' => 'string'),
        'mr'             => array('object' => 'bug', 'field' => 'mr', 'type' => 'string'),
        'entry'          => array('object' => 'bug', 'field' => 'entry', 'type' => 'string'),
        'lines'          => array('object' => 'bug', 'field' => 'lines', 'type' => 'string'),
        'v1'             => array('object' => 'bug', 'field' => 'v1', 'type' => 'string'),
        'v2'             => array('object' => 'bug', 'field' => 'v2', 'type' => 'string'),
        'repoType'       => array('object' => 'bug', 'field' => 'repoType', 'type' => 'string'),
        'issueKey'       => array('object' => 'bug', 'field' => 'issueKey', 'type' => 'string'),
        'testtask'       => array('object' => 'bug', 'field' => 'testtask', 'type' => 'string'),
        'lastEditedBy'   => array('object' => 'bug', 'field' => 'lastEditedBy', 'type' => 'string'),
        'lastEditedDate' => array('object' => 'bug', 'field' => 'lastEditedDate', 'type' => 'string'),
        'deleted'        => array('object' => 'bug', 'field' => 'deleted', 'type' => 'string'),
        'customproduct'  => array('object' => 'bug', 'field' => 'customproduct', 'type' => 'string')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done')
    ),
    'vars'      => array
    (
        'varName'     => array('product', 'startDate', 'endDate'),
        'showName'    => array('产品', '解决日期开始', '解决日期结束'),
        'requestType' => array('select', 'date', 'date'),
        'selectList'  => array('product', 'user', 'user'),
        'default'     => array('', '$MONTHBEGIN', '$MONTHEND')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1026,
    'name'      => array('zh-cn' => '项目进展表', 'zh-tw' => '項目進展表', 'en' => 'Project Progress Report', 'de' => 'Project Progress Report', 'fr' => 'Project Progress Report', 'vi' => 'Project Progress Report', 'ja' => 'Project Progress Report'),
    'code'      => 'projectProgress',
    'desc'      => array('zh-cn' => '项目的需求数，任务数，已消耗工时，剩余工时，剩余需求数，剩余任务数，进度。', 'zh-tw' => '項目的需求數，任務數，已消耗工時，剩餘工時，剩餘需求數，剩餘任務數，進度。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60',
    'sql'       => <<<EOT
select t1.id,t4.name as project,IF(t4.multiple="1",t1.name,"") as execution,t1.status,t2.number as tasks,round(t2.consumed,2) as consumed,round(t2.`left`,2) as `left`,t3.stories,t2.undone as undoneTask,t3.undone as undoneStory,t2.totalReal from zt_project as t1
left join ztv_executionsummary as t2 on t1.id=t2.execution
left join ztv_projectstories as t3 on t1.id=t3.execution
left join zt_project as t4 on t4.id=t1.project
where t1.deleted='0' and t1.type in ('sprint','stage') and if(\$project='',1,t4.id=\$project) and if(\$execution='',1,t1.id=\$execution) and if(\$status='',1,t1.status=\$status)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'undoneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'tasks', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'undoneTask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'left', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0'),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '1')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => ''),
        array('from' => 'query', 'field' => 'status', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => '')
    ),
    'fields'    => array
    (
        'id'          => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'     => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't4id'        => array('object' => 'project', 'field' => 't4id', 'type' => 'string'),
        'execution'   => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'status'      => array('object' => 'project', 'field' => 'status', 'type' => 'string'),
        'tasks'       => array('object' => 'project', 'field' => 'tasks', 'type' => 'string'),
        'consumed'    => array('object' => 'project', 'field' => 'consumed', 'type' => 'string'),
        'left'        => array('object' => 'project', 'field' => 'left', 'type' => 'string'),
        'stories'     => array('object' => 'project', 'field' => 'stories', 'type' => 'string'),
        'undoneTask'  => array('object' => 'project', 'field' => 'undoneTask', 'type' => 'string'),
        'undoneStory' => array('object' => 'project', 'field' => 'undoneStory', 'type' => 'string'),
        'totalReal'   => array('object' => 'project', 'field' => 'totalReal', 'type' => 'string')
    ),
    'langs'     => array
    (
        'stories'         => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'tasks'           => array('zh-cn' => '任务数', 'zh-tw' => '任务数', 'en' => 'Tasks'),
        'undoneStory'     => array('zh-cn' => '剩余需求数', 'zh-tw' => '剩余需求数', 'en' => 'Undone Story'),
        'undoneTask'      => array('zh-cn' => '剩余任务数', 'zh-tw' => '剩余任务数', 'en' => 'Undone Task'),
        'consumed'        => array('zh-cn' => '已消耗工时', 'zh-tw' => '已消耗工时', 'en' => 'Cost(h)'),
        'left'            => array('zh-cn' => '剩余工时', 'zh-tw' => '剩余工时', 'en' => 'Left(h)'),
        'consumedPercent' => array('zh-cn' => '进度', 'zh-tw' => '进度', 'en' => 'Process'),
        'execution'       => array('zh-cn' => '执行名称')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution', 'status'),
        'showName'    => array('项目列表', '执行列表', '执行状态'),
        'requestType' => array('select', 'select', 'select'),
        'selectList'  => array('project', 'execution', 'project.status'),
        'default'     => array('', '', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1027,
    'name'      => array('zh-cn' => '项目Bug类型统计表', 'zh-tw' => '項目Bug類型統計表', 'en' => 'Project Bug Type', 'de' => 'Project Bug Type', 'fr' => 'Project Bug Type', 'vi' => 'Project Bug Type', 'ja' => 'Project Bug Type'),
    'code'      => 'projectBugType',
    'desc'      => array('zh-cn' => '按照项目统计Bug的类型分布情况。', 'zh-tw' => '按照項目統計Bug的類型分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension' => '1',
    'group'     => '60,61',
    'sql'       => <<<EOT
select t1.id,t3.name as project,IF(t3.multiple="1",t1.name,"") as execution,t2.id as bugID,t2.type from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0' and t2.deleted='0' and if(\$project='',1,t3.id=\$project) and if(\$execution='',1,t1.id=\$execution)
EOT,
    'settings'  => array
    (
        'group1'      => 'project',
        'group2'      => 'execution',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'type', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => ''),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '')
    ),
    'fields'    => array
    (
        'id'        => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'project'   => array('object' => 'project', 'field' => 'project', 'type' => 'string'),
        't3id'      => array('object' => 'project', 'field' => 't3id', 'type' => 'string'),
        'execution' => array('object' => 'project', 'field' => 'execution', 'type' => 'string'),
        'bugID'     => array('object' => 'bug', 'field' => 'bugID', 'type' => 'string'),
        'type'      => array('object' => 'bug', 'field' => 'type', 'type' => 'option')
    ),
    'langs'     => array
    (
        'stories'         => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'tasks'           => array('zh-cn' => '任务数', 'zh-tw' => '任务数', 'en' => 'Tasks'),
        'undoneStory'     => array('zh-cn' => '剩余需求数', 'zh-tw' => '剩余需求数', 'en' => 'Undone Story'),
        'undoneTask'      => array('zh-cn' => '剩余任务数', 'zh-tw' => '剩余任务数', 'en' => 'Undone Task'),
        'consumed'        => array('zh-cn' => '已消耗工时', 'zh-tw' => '已消耗工时', 'en' => 'Cost(h)'),
        'left'            => array('zh-cn' => '剩余工时', 'zh-tw' => '剩余工时', 'en' => 'Left(h)'),
        'consumedPercent' => array('zh-cn' => '进度', 'zh-tw' => '进度', 'en' => 'Process'),
        'execution'       => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'id'              => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'         => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'           => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'            => array('zh-cn' => 'Bug类型', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->pivots[] = array
(
    'id'        => 1028,
    'name'      => array('zh-cn' => '产品Bug解决方案统计表', 'zh-tw' => '産品Bug解決方案統計表', 'en' => 'Bug Solution of Product'),
    'code'      => 'productBugSolution',
    'desc'      => array('zh-cn' => '按照产品统计Bug的解决方案分布情况。', 'zh-tw' => '按照産品統計Bug的解決方案分布情況。', 'en' => 'Solution distribution of bugs.'),
    'dimension' => '1',
    'group'     => '59,61',
    'sql'       => <<<EOT
select t1.id,t1.name,t2.id as bugID,t2.resolution from zt_product as t1
left join zt_bug as t2 on t1.id=t2.product
left join zt_project as t3 on t1.program=t3.id
where t1.deleted='0' and t2.deleted='0'
and t2.resolution != ''
order by t3.`order` asc, t1.line desc, t1.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0')
        )
    ),
    'fields'    => array
    (
        'id'         => array('object' => 'product', 'field' => 'id', 'type' => 'string'),
        'name'       => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'bugID'      => array('object' => 'project', 'field' => 'bugID', 'type' => 'string'),
        'resolution' => array('object' => 'bug', 'field' => 'resolution', 'type' => 'string')
    ),
    'langs'     => array
    (
        'count' => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories'),
        'done'  => array('zh-cn' => '完成数', 'zh-tw' => '完成数', 'en' => 'Done')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);
