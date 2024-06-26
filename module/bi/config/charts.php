<?php
$config->bi->builtin->charts = array();

$config->bi->builtin->charts[] = array
(
    'id'        => 1001,
    'name'      => '年度总结-登录次数',
    'code'      => 'annualSummary_countLogin',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '0',
    'sql'       => <<<EOT
select
    sum(t2.login) as login,
    "year",
    account,
    realname
from zt_user t1
left join
    (select
         count(1) as login,
         actor, year("date") as "year"
     from zt_action
     group by actor, "year"
    ) t2 on t1.account = t2.actor
where t1.deleted = '0'
group by "year", account, realname
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'login', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1002,
    'name'      => '年度总结-操作次数',
    'code'      => 'annualSummary_countAction',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '0',
    'sql'       => <<<EOT
select
    sum(t2.allAction) as allAction,
    "year",
    account,
    realname
from zt_user t1
left join
    (select
     count(1) as allAction,
         actor,
         year("date") as "year"
     from zt_action
     group by actor, "year"
    ) t2 on t1.account = t2.actor
where t1.deleted = '0'
group by "year", account, realname
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'allAction', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1003,
    'name'      => '年度总结-消耗工时',
    'code'      => 'annualSummary_consumed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '0',
    'sql'       => <<<EOT
select
    round(sum(t2.consumed)) as consumed,
    "year",
    t1.account,
    realname
from zt_user t1
left join
    (select
         sum(consumed) as consumed,
         account,
         year("date")  as "year"
     from zt_effort
     where deleted = '0'
     group by account, "year"
    ) t2 on t1.account = t2.account
where t1.deleted = '0'
group by "year", t1.account, realname
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'consumed', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1004,
    'name'      => '年度总结-待办数',
    'code'      => 'annualSummary_countTodo',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '0',
    'sql'       => <<<EOT
select
    sum(t2.todo) as todo,
    sum(t2.undone) as undone,
    sum(t2.done) as done,
    t2.year,
    t1.account,
    realname,
    dept
from zt_user t1
left join (
    select
        count(1) as 'todo',
        sum(if((status != 'done'), 1, 0)) as undone,
        sum(if((status = 'done'), 1, 0)) as done,
        account,
        year(date) as 'year'
    from zt_todo
    where deleted = '0'
    group by account, year
) t2 on t1.account = t2.account
where t1.deleted = '0'
group by t2.year, t1.account, realname, dept
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'todo', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1005,
    'name'      => '年度总结-贡献数',
    'code'      => 'annualSummary_countContributions',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '0',
    'sql'       => <<<EOT
select
    year(t1.date) as year,
    t1.actor as account,
    count(1) as num
from zt_action t1
inner join (
    select 'bug' as objecttype, id from zt_bug where deleted = '0'
    union all
    select 'task' as objecttype, id from zt_task where deleted = '0'
    union all
    select 'story' as objecttype, id from zt_story where deleted = '0'
    union all
    select 'execution' as objecttype, id from zt_project where deleted = '0'
    union all
    select 'product' as objecttype, id from zt_product where deleted = '0'
    union all
    select 'case' as objecttype, id from zt_case where deleted = '0'
    union all
    select 'testtask' as objecttype, id from zt_testtask where deleted = '0'
    union all
    select 'productplan' as objecttype, id from zt_productplan where deleted = '0'
    union all
    select 'release' as objecttype, id from zt_release where deleted = '0'
    union all
    select 'doc' as objecttype, id from zt_doc where deleted = '0'
    union all
    select 'build' as objecttype, id from zt_build where deleted = '0'
) as sub on t1.objecttype = sub.objecttype and t1.objectid = sub.id
where
    (
        (t1.objecttype = 'bug' and t1.action in ('resolved', 'opened', 'closed', 'activated'))
        or (t1.objecttype = 'task' and t1.action in ('finished', 'opened', 'closed', 'activated', 'assigned'))
        or (t1.objecttype = 'story' and t1.action in ('opened', 'reviewed', 'closed'))
        or (t1.objecttype = 'execution' and t1.action in ('opened', 'edited', 'started', 'closed'))
        or (t1.objecttype = 'product' and t1.action in ('opened', 'edited', 'closed'))
        or (t1.objecttype = 'case' and t1.action in ('opened', 'run'))
        or (t1.objecttype = 'testtask' and t1.action in ('opened', 'edited'))
        or (t1.objecttype = 'productplan' and t1.action = 'opened')
        or (t1.objecttype = 'release' and t1.action = 'opened')
        or (t1.objecttype = 'doc' and t1.action in ('created', 'edited'))
        or (t1.objecttype = 'build' and t1.action = 'opened')
    )
    or (t1.action in ('gitcommited', 'svncommited') and t1.objecttype = 'task')
group by account, year
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'num', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1006,
    'name'      => '年度总结-贡献数据',
    'code'      => 'annualSummary_contributions',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'bar',
    'group'     => '0',
    'sql'       => <<<EOT
select
    t2.year, t1.dept, t2.account, t2.objecttype, t2.create, t2.edit
from zt_user as t1
left join (
    select
        year(t1.date) as year, t1.actor as account, '产品' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_product as t2 on t1.objectid = t2.id
    where t1.objecttype = 'product' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '需求' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_story as t2 on t1.objectid = t2.id
    where t1.objecttype = 'story' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '计划' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_productplan as t2 on t1.objectid = t2.id
    where t1.objecttype = 'productplan' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '发布' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_release as t2 on t1.objectid = t2.id
    where t1.objecttype = 'release' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '执行' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_project as t2 on t1.objectid = t2.id
    where t1.objecttype = 'execution' and t1.action in ('opened', 'edited') and t2.deleted = '0' and t2.type in ('sprint', 'stage', 'kanban')
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '任务' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_task as t2 on t1.objectid = t2.id
    where t1.objecttype = 'task' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, 'bug' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_bug as t2 on t1.objectid = t2.id
    where t1.objecttype = 'bug' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '版本' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_build as t2 on t1.objectid = t2.id
    where t1.objecttype = 'build' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '用例' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_case as t2 on t1.objectid = t2.id
    where t1.objecttype = 'case' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '测试单' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_testtask as t2 on t1.objectid = t2.id
    where t1.objecttype = 'testtask' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
    union all
    select
        year(t1.date) as year, t1.actor, '文档' as objecttype,
        sum(if(t1.action = 'opened', 1, 0)) as create,
        sum(if(t1.action = 'edited', 1, 0)) as edit
    from zt_action as t1
    left join zt_doc as t2 on t1.objectid = t2.id
    where t1.objecttype = 'doc' and t1.action in ('opened', 'edited') and t2.deleted = '0'
    group by year, actor, objecttype
) as t2 on t1.account = t2.account
where t2.account is not null
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'objectType', 'name' => '对象类型')
        ),
        'yaxis' => array
        (
            array('type' => 'agg', 'field' => 'create', 'agg' => 'sum', 'name' => '创建', 'valOrAgg' => 'sum'),
            array('type' => 'value', 'field' => 'edit', 'agg' => 'sum', 'name' => '编辑', 'valOrAgg' => 'sum')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1007,
    'name'      => '年度总结-能力雷达图',
    'code'      => 'annualSummary_capabilityRadar',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'radar',
    'group'     => '0',
    'sql'       => <<<EOT
select tt.year, tt.actor as account, tt.dimension, count(1) as num
from (
    select year(try_cast(t1.date as date)) as "year", t1.actor, 'product' as dimension
    from zt_action t1
    where (
        (t1.objecttype = 'product' and t1.action in('opened','edited') and (select deleted from zt_product where id = t1.objectid) = '0')
        or (t1.objecttype = 'story' and t1.action in('opened','reviewed','closed') and (select deleted from zt_story where id = t1.objectid) = '0')
        or (t1.objecttype = 'productplan' and t1.action in('opened') and (select deleted from zt_productplan where id = t1.objectid) = '0')
        or (t1.objecttype = 'release' and t1.action in('opened') and (select deleted from zt_release where id = t1.objectid) = '0')
    )
    union all
    select year(t1.date) as "year", t1.actor, 'execution' as dimension
    from zt_action t1
    where (
        (t1.objecttype = 'execution' and t1.action in('opened','edited','started','closed') and (select deleted from zt_project where id = t1.objectid) = '0')
        or (t1.objecttype = 'build' and t1.action in('opened') and (select deleted from zt_build where id = t1.objectid) = '0')
        or (t1.objecttype = 'task' and t1.action in('opened','closed','activated','assigned') and (select deleted from zt_task where id = t1.objectid) = '0')
    )
    union all
    select year(t1.date) as "year", t1.actor, 'devel' as dimension
    from zt_action t1
    where (
        (t1.objecttype = 'execution' and t1.action in('opened','edited','started','closed') and (select deleted from zt_project where id = t1.objectid) = '0')
        or (t1.objecttype = 'build' and t1.action in('opened') and (select deleted from zt_build where id = t1.objectid) = '0')
        or (t1.objecttype = 'task' and t1.action in('opened','closed','assigned') and (select deleted from zt_task where id = t1.objectid) = '0')
        or (t1.objecttype = 'task' and t1.action in ('gitcommited', 'svncommited'))
        or (t1.objecttype = 'bug' and t1.action in('resolved') and (select deleted from zt_bug where id = t1.objectid) = '0')
    )
    union all
    select year(t1.date) as "year", t1.actor, 'qa' as dimension
    from zt_action t1
    where (
        (t1.objecttype = 'bug' and t1.action in('opened','closed','activated') and (select deleted from zt_bug where id = t1.objectid) = '0')
        or (t1.objecttype = 'case' and t1.action in('opened','run') and (select deleted from zt_case where id = t1.objectid) = '0')
        or (t1.objecttype = 'testtask' and t1.action in('opened','edited') and (select deleted from zt_testtask where id = t1.objectid) = '0')
    )
) tt
where tt.year is not null
group by tt.year, tt.dimension, account
EOT,
    'settings'  => array
    (
        'group'  => array
        (
            array('field' => 'dimension', 'name' => '维度')
        ),
        'metric' => array
        (
            array('type' => 'value', 'field' => 'num', 'agg' => 'value', 'name' => '产品管理', 'key' => 'product', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'num', 'agg' => 'value', 'name' => '项目管理', 'key' => 'project', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'num', 'agg' => 'value', 'name' => '研发', 'key' => 'dev', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'num', 'agg' => 'value', 'name' => '测试', 'key' => 'qa', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'num', 'agg' => 'value', 'name' => '其他', 'key' => 'other', 'valOrAgg' => 'value')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1008,
    'name'      => '年度总结-迭代数据',
    'code'      => 'annualSummary_executions',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '0',
    'sql'       => <<<EOT
select tt3.id, tt3.name, tt3.year, tt3.account, tt3.finishedstory, tt3.finishedtask, count(t3.id) as resolvedbug
from (
    select tt2.id, t2.name as name, tt2.year, tt2.account, sum(if((t1.story != 0), 1 , 0)) as finishedstory, count(t1.id) as finishedtask
    from (
        select *
        from (
            select id, year(begin) as year, openedby as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year(begin) is not null
            union all
            select id, year(begin) as year, po as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year(begin) is not null
            union all
            select id, year(begin) as year, pm as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year(begin) is not null
            union all
            select id, year(begin) as year, qd as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year(begin) is not null
            union all
            select id, year(begin) as year, rd as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year(begin) is not null
            union all
            select id, year("end") as year, openedby as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year("end") is not null
            union all
            select id, year("end") as year, po as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year("end") is not null
            union all
            select id, year("end") as year, pm as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year("end") is not null
            union all
            select id, year("end") as year, qd as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year("end") is not null
            union all
            select id, year("end") as year, rd as account from zt_project where deleted = '0' and type = 'sprint' and multiple = '1' and year("end") is not null
            union all
            select t1.root as id, year(t1."join") as year, t1.account from zt_team t1
            right join zt_project t2 on t2.id = t1.root and t2.deleted = '0' and t2.type = 'sprint' where t1.type = 'execution' and year(t1."join") is not null
            union all
            select t1.execution as id, year(t1.finisheddate) as year, t1.finishedby as account from zt_task t1
            right join zt_project t2 on t2.id = t1.execution and t2.deleted = '0' and t2.type = 'sprint' where t1.deleted = '0' and year(t1.finisheddate) is not null
        ) tt1
        where tt1.account != ''
        group by tt1.id, tt1."year", tt1.account
    ) tt2
    left join zt_task t1 on t1.execution = tt2.id and year(t1.finisheddate) = tt2.year and t1.deleted = '0' and t1.finishedby = tt2.account
    left join zt_project t2 on t2.id = tt2.id
    group by tt2.id, tt2."year", tt2.account, t2.name
) tt3
left join zt_bug t2 on t2.resolvedby = tt3.account and year(t2.resolveddate) = tt3.year and try_cast(t2.resolvedbuild as int) is not null
left join zt_build t3 on t2.resolvedbuild = t3.id and t3.execution = tt3.id
where t2.deleted = '0'
group by tt3.account, tt3."year", tt3.id, tt3.name, tt3.finishedstory, tt3.finishedtask
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '迭代名称'),
            array('field' => 'finishedStory', 'valOrAgg' => 'value', 'name' => '完成需求数'),
            array('field' => 'finishedTask', 'valOrAgg' => 'value', 'name' => '完成任务数'),
            array('field' => 'resolvedBug', 'valOrAgg' => 'value', 'name' => '解决Bug数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1009,
    'name'      => '年度总结-产品数据',
    'code'      => 'annualSummary_products',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '0',
    'sql'       => <<<EOT
select * from (
    select tt4.id, t2.name, tt4.year, tt4.account, tt4.plans, tt4.requirement, tt4.story, count(t1.id) as closedstory
    from (
        select tt3.id, tt3.year, tt3.account, tt3.plans, sum(if((type = 'requirement'), 1, 0)) as requirement, sum(if((type = 'story'), 1, 0)) as story
        from (
            select tt2.id, tt2.year, tt2.account, count(t2.id) as plans
            from (
                select * from (
                    select id, year(createddate) as "year", createdby as account from zt_product where deleted = '0' and shadow = '0'
                    union all
                    select id, year(createddate) as "year", po as account from zt_product where deleted = '0' and shadow = '0'
                    union all
                    select id, year(createddate) as "year", qd as account from zt_product where deleted = '0' and shadow = '0'
                    union all
                    select id, year(createddate) as "year", rd as account from zt_product where deleted = '0' and shadow = '0'
                ) tt1
                where tt1.account != '' and tt1.year is not null
                group by tt1.account, tt1.year, tt1.id
            ) tt2
            left join zt_productplan t1 on t1.product = tt2.id
            left join zt_action t2 on t1.id = t2.objectid and year(t2.date) = tt2.year and t2.objecttype = 'productplan' and t1.deleted = '0' and t2.actor = tt2.account and t2.action = 'opened'
            group by tt2.account, tt2.year, tt2.id
        ) tt3
        left join zt_story t1 on t1.product = tt3.id and year(t1.openeddate) = tt3.year and t1.openedby = tt3.account and t1.deleted = '0'
        group by tt3.account, tt3.year, tt3.id, tt3.plans
    ) tt4
    left join zt_story t1 on t1.product = tt4.id and year(t1.closeddate) = tt4.year and t1.closedby = tt4.account and t1.deleted = '0'
    left join zt_product t2 on t2.id = tt4.id
    group by tt4.account, tt4.year, tt4.id, tt4.plans, t2.name, tt4.requirement, tt4.story
) tt
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '产品名称'),
            array('field' => 'plan', 'valOrAgg' => 'value', 'name' => '计划数'),
            array('field' => 'requirement', 'valOrAgg' => 'value', 'name' => '创建用户需求数'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '创建需求数'),
            array('field' => 'closedStory', 'valOrAgg' => 'value', 'name' => '关闭需求数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1010,
    'name'      => '年度总结-任务状态分布',
    'code'      => 'annualSummary_taskStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '0',
    'sql'       => <<<EOT
select
    year(t1.date) as "year",
    t3.account,
    t3.realname,
    t2.status,
    t1.id
from zt_action t1
    left join zt_task t2 on t1.objectid = t2.id
    right join zt_user t3 on t1.actor = t3.account
where t1.objecttype = 'task' and t2.deleted = '0'
EOT,
    'settings'  => array
    (
        'group'  => array
        (
            array('field' => 'status', 'name' => '状态')
        ),
        'metric' => array
        (
            array('type' => 'agg', 'field' => 'id', 'agg' => 'count', 'name' => '任务数', 'valOrAgg' => 'count')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1011,
    'name'      => '年度总结-每月任务操作情况',
    'code'      => 'annualSummary_monthlyTaskAction',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'bar',
    'group'     => '0',
    'sql'       => <<<EOT
select
    t2.opened,t2.started,t2.finished,t2.paused,t2.activated,t2.canceled,t2.closed,t1.account,t2.actiondate,t2.year,realname,t3.name as deptname
from
    zt_user as t1
left join (
    select
        t21.actor,
        printf('%04d-%02d', year(t21."date"), month(t21."date")) as actiondate,
        year(t21."date") as year,
        sum(if(t21.action = 'opened', 1, 0)) as opened,
        sum(if(t21.action = 'started', 1, 0)) as started,
        sum(if(t21.action = 'finished', 1, 0)) as finished,
        sum(if(t21.action = 'paused', 1, 0)) as paused,
        sum(if(t21.action = 'activated', 1, 0)) as activated,
        sum(if(t21.action = 'canceled', 1, 0)) as canceled,
        sum(if(t21.action = 'closed', 1, 0)) as closed
    from zt_action as t21
    left join zt_story as t22 on t21.objectid = t22.id
    where t21.objecttype = 'bug'
    and t22.deleted = '0'
    group by  t21.actor, actiondate, year
) as t2 on t1.account = t2.actor
left join zt_dept as t3 on t1.dept = t3.id
where t1.deleted = '0'
and t2.actor is not null
group by t2.actiondate,deptname,t1.account,realname,t2.opened,t2.started,t2.finished,t2.paused,t2.activated,t2.canceled,t2.closed,year
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'actionDate', 'name' => '日期', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'agg', 'field' => 'opened', 'agg' => 'sum', 'name' => '创建', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'started', 'agg' => 'sum', 'name' => '开始', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'finished', 'agg' => 'sum', 'name' => '完成', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'paused', 'agg' => 'sum', 'name' => '暂停', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'activated', 'agg' => 'sum', 'name' => '激活', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'canceled', 'agg' => 'sum', 'name' => '取消', 'valOrAgg' => 'sum'),
            array('type' => 'agg', 'field' => 'closed', 'agg' => 'sum', 'name' => '关闭', 'valOrAgg' => 'sum')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1012,
    'name'      => '年度总结-需求状态分布',
    'code'      => 'annualSummary_storyStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '0',
    'sql'       => <<<EOT
select
    year(t1.date) as "year",
    t3.account,
    t3.realname,
    t2.status,
    t1.id
from zt_action t1
left join zt_task t2 on t1.objectid = t2.id
right join zt_user t3 on t1.actor = t3.account
where t1.objecttype = 'story'
and t2.deleted = '0'
EOT,
    'settings'  => array
    (
        'group'  => array
        (
            array('field' => 'status', 'name' => '状态')
        ),
        'metric' => array
        (
            array('type' => 'agg', 'field' => 'id', 'agg' => 'count', 'name' => '需求数', 'valOrAgg' => 'count')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1013,
    'name'      => '年度总结-每月需求操作情况',
    'code'      => 'annualSummary_monthlyStoryAction',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'bar',
    'group'     => '0',
    'sql'       => <<<EOT
select t2.opened, t2.activated, t2.closed, t2.`changed`, t1.account, t2.actiondate, t2.year, realname, t3.`name` as deptname
from zt_user as t1
left join (
    select
        t21.actor,
        printf('%04d-%02d', year(t21."date"), month(t21."date")) as actiondate,
        year(t21."date") as year,
        sum(if(t21.action='opened', 1, 0)) as opened,
        sum(if(t21.action='activated', 1, 0)) as activated,
        sum(if(t21.action='closed', 1, 0)) as closed,
        sum(if(t21.action='changed', 1, 0)) as `changed`
    from zt_action as t21
    left join zt_story as t22 on t21.objectid = t22.id
    where t21.objecttype = 'story'
    and t22.deleted = '0'
    group by t21.actor, actiondate, year
) as t2 on t1.account = t2.actor
left join zt_dept as t3 on t1.dept = t3.id
where t1.deleted = '0'
and t2.actor is not null
group by t2.actiondate, deptname, t1.account, realname, t2.opened, t2.activated, t2.closed, t2.`changed`, year
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'actionDate', 'name' => '日期', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'value', 'field' => 'opened', 'agg' => 'value', 'name' => '创建', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'activated', 'agg' => 'value', 'name' => '激活', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'changed', 'agg' => 'value', 'name' => '变更', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'closed', 'agg' => 'value', 'name' => '关闭', 'valOrAgg' => 'value')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1014,
    'name'      => '年度总结-Bug状态分布',
    'code'      => 'annualSummary_bugStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '0',
    'sql'       => <<<EOT
select
    year(t1.date) as "year",
    t3.account,
    t3.realname,
    t2.status,
    t1.id
from zt_action t1
left join zt_task t2 on t1.objectid = t2.id
right join zt_user t3 on t1.actor = t3.account
where t1.objecttype = 'bug'
and t2.deleted = '0'
EOT,
    'settings'  => array
    (
        'group'  => array
        (
            array('field' => 'status', 'name' => '状态')
        ),
        'metric' => array
        (
            array('type' => 'agg', 'field' => 'id', 'agg' => 'count', 'name' => 'Bug数', 'valOrAgg' => 'count')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1015,
    'name'      => '年度总结-每月Bug操作情况',
    'code'      => 'annualSummary_monthlyBugAction',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'bar',
    'group'     => '0',
    'sql'       => <<<EOT
select t2.opened,t2.bugconfirmed,t2.activated,t2.resolved,t2.closed,t1.account,t2.actiondate,t2.year,realname,t3.name as deptname
from zt_user as t1
left join (
    select t21.actor,
    printf('%04d-%02d', year(t21."date"), month(t21."date")) as actiondate,
    year(t21."date") as year,
    sum(if(t21.action='opened', 1, 0)) as opened,
    sum(if(t21.action='bugconfirmed', 1, 0)) as bugconfirmed,
    sum(if(t21.action='activated', 1, 0)) as activated,
    sum(if(t21.action='resolved', 1, 0)) as resolved,
    sum(if(t21.action='closed', 1, 0)) as closed
    from zt_action as t21
    left join zt_story as t22 on t21.objectid = t22.id
    where t21.objecttype = 'bug'
    and t22.deleted = '0'
    group by t21.actor, actiondate, year
) as t2 on t1.account = t2.actor
left join zt_dept as t3 on t1.dept = t3.id
where t1.deleted = '0'
and t2.actor is not null
group by t2.actiondate, deptname, t1.account, realname, t2.opened, t2.bugconfirmed, t2.activated, t2.resolved, t2.closed, year
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'actionDate', 'name' => '日期', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'value', 'field' => 'opened', 'agg' => 'value', 'name' => '创建', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'bugconfirmed', 'agg' => 'value', 'name' => '确认', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'activated', 'agg' => 'value', 'name' => '激活', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'resolved', 'agg' => 'value', 'name' => '解决', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'closed', 'agg' => 'value', 'name' => '关闭', 'valOrAgg' => 'value')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1016,
    'name'      => '年度总结-用例结果分布',
    'code'      => 'annualSummary_caseResult',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '0',
    'sql'       => <<<EOT
select
    tt.join as year,
    count(1) as number,
    tt.setname
from (
    select
        year(t1.join) as join,
        t4.name as setname
    from zt_team as t1
    right join zt_project as t2 on t2.id = t1.root
    left join zt_project as t4 on (',' || t2.path || ',' like '%,' || t4.id || ',%') and t4.grade = 1
    right join zt_user as t3 on t3.account = t1.account
    where t1.type = 'project'
    and t2.deleted = '0'
    and t3.deleted = '0'
) as tt
group by tt.setname, tt.join
order by tt.join, number desc, tt.setname
EOT,
    'settings'  => array
    (
        'group'  => array
        (
            array('field' => 'status', 'name' => '状态')
        ),
        'metric' => array
        (
            array('type' => 'agg', 'field' => 'id', 'agg' => 'count', 'name' => '个数', 'valOrAgg' => 'count')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1017,
    'name'      => '年度总结-每月用例操作情况',
    'code'      => 'annualSummary_monthlyCaseAction',
    'dimension' => '1',
    'type'      => 'bar',
    'group'     => '0',
    'sql'       => <<<EOT
SELECT SUM(createdCases) AS createdCases, SUM(toBugCases) AS toBugCases, SUM(runCases) AS runCases, YEAR(CONCAT(t2.actionDate, '-01')) AS `year`, t1.account, realname, dept
FROM zt_user t1
LEFT JOIN (
    SELECT t21.actor, LEFT(t21.`date`, 7) as actionDate,
    SUM(IF((t22.id IS NOT NULL AND t23.id IS NULL), 1, 0)) AS createdCases,
    SUM(IF((t22.id IS NOT NULL AND t23.id IS NOT NULL), 1, 0)) AS toBugCases,
    SUM(IF((t24.lastRunner = t21.actor AND t21.action = 'run' AND t21.`date` = t24.`date`), 1, 0)) AS runCases
    FROM zt_action t21
    LEFT JOIN zt_case t22 on t21.objectID = t22.id
    LEFT JOIN zt_bug t23 on t22.id = t23.case
    LEFT JOIN zt_testresult t24 on t22.id = t24.`case` AND t24.lastRunner = t21.actor AND t21.action = 'run' AND t21.`date` = t24.`date`
    WHERE t21.objectType = 'case'
    AND t21.action in ('opened', 'run')
    AND t22.deleted = '0'
    AND (t23.deleted = '0' OR t23.id IS NULL)
    GROUP BY t21.actor, actionDate
) t2 on t1.account = t2.actor
WHERE t1.deleted = '0'
AND t2.actor is not null
GROUP BY t2.actionDate, t1.account, realname, dept
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'actionDate', 'name' => '日期', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'value', 'field' => 'createdCases', 'agg' => 'value', 'name' => '创建', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'toBugCases', 'agg' => 'value', 'name' => '转Bug', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'runCases', 'agg' => 'value', 'name' => '执行', 'valOrAgg' => 'value')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1018,
    'name'      => '宏观数据-一级项目集个数',
    'code'      => 'macro_countTopProgram',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '45',
    'sql'       => <<<EOT
SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'fields'    => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1019,
    'name'      => '宏观数据-项目个数',
    'code'      => 'macro_countProject',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE type='project' AND deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1020,
    'name'      => '宏观数据-产品个数',
    'code'      => 'macro_countProduct',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
SELECT id FROM zt_product WHERE deleted='0' AND shadow = '0' AND vision = 'rnd'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1021,
    'name'      => '宏观数据-计划个数',
    'code'      => 'macro_countPlan',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '48',
    'sql'       => <<<EOT
SELECT id FROM zt_productplan WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1022,
    'name'      => '宏观数据-执行个数',
    'code'      => 'macro_countExecution',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '49',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE type IN ('sprint','stage','kanban') AND deleted='0' AND multiple = '1'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1023,
    'name'      => '宏观数据-发布个数',
    'code'      => 'macro_countRelease',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '50',
    'sql'       => <<<EOT
SELECT id FROM zt_release WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1024,
    'name'      => '宏观数据-需求个数',
    'code'      => 'macro_countStory',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '51',
    'sql'       => <<<EOT
SELECT id FROM zt_story WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1025,
    'name'      => '宏观数据-任务个数',
    'code'      => 'macro_countTask',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '52',
    'sql'       => <<<EOT
SELECT id FROM zt_task WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1026,
    'name'      => '宏观数据-缺陷个数',
    'code'      => 'macro_countBug',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '53',
    'sql'       => <<<EOT
SELECT id FROM zt_bug WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1027,
    'name'      => '宏观数据-文档个数',
    'code'      => 'macro_countDoc',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '54',
    'sql'       => <<<EOT
SELECT id FROM zt_doc WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1028,
    'name'      => '宏观数据-现有人员个数',
    'code'      => 'macro_activeAccounts',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '55',
    'sql'       => <<<EOT
SELECT id FROM zt_user WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1029,
    'name'      => '宏观数据-累计消耗工时',
    'code'      => 'macro_consumed',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '55',
    'sql'       => <<<EOT
SELECT consumed FROM zt_effort WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'consumed', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1030,
    'name'      => '宏观数据-禅道使用时长',
    'code'      => 'macro_useZentao',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '58',
    'sql'       => <<<EOT
	SELECT if(t2.`year` > 0, concat(t2.`year`, '年', t2.`day`, '天'), concat(t2.`day`, '天')) as period from (
SELECT TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) AS `year`,DATEDIFF(DATE_SUB(t1.today,INTERVAL TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) YEAR), t1.firstDay) AS `day`
FROM (SELECT `value` AS firstDay, now() AS today FROM zt_config WHERE `owner` = 'system' AND `key` = 'installedDate') AS t1
) t2
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'period', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1031,
    'name'      => '宏观数据-需求完成率',
    'code'      => 'macro_storyFinishedRate',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'waterpolo',
    'group'     => '36',
    'sql'       => <<<EOT
select id, if(closedReason='done', 'done', 'undone') as bugstatus from zt_story where deleted='0' and (status != 'closed' or closedReason='done')
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'bugstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'        => array('name' => '编号', 'object' => 'story', 'field' => 'id', 'type' => 'number'),
        'bugstatus' => array('name' => 'bugstatus', 'object' => 'story', 'field' => 'bugstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'        => array('zh-cn' => '编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'bugstatus' => array('zh-cn' => 'Bug状态', 'zh-tw' => '', 'en' => 'bugstatus', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1032,
    'name'      => '宏观数据-Bug修复率',
    'code'      => 'macro_bugFixedRate',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'waterpolo',
    'group'     => '44',
    'sql'       => <<<EOT
select id, if("status"='closed' and resolution='fixed', 'done', 'undone') as bugstatus from zt_bug where deleted='0' and (status = 'active' or resolution in ('fixed', 'postponed'))
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'bugstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'        => array('name' => 'Bug编号', 'object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'bugstatus' => array('name' => 'bugstatus', 'object' => 'bug', 'field' => 'bugstatus', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'        => array('zh-cn' => 'Bug编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'bugstatus' => array('zh-cn' => 'Bug状态', 'zh-tw' => '', 'en' => 'bugstatus', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1033,
    'name'      => '宏观数据-未完成的一级项目集个数',
    'code'      => 'macro_countTopProgram_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '45',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE type='program' AND `status`!='closed' AND deleted='0' AND grade='1'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1034,
    'name'      => '宏观数据-未完成的需求',
    'code'      => 'macro_countStory_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '51',
    'sql'       => <<<EOT
SELECT id FROM zt_story WHERE `status`!='closed' AND deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1035,
    'name'      => '宏观数据-未完成的产品',
    'code'      => 'macro_countProduct_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
SELECT id FROM zt_product WHERE `status`!='closed' AND deleted='0' AND shadow='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1036,
    'name'      => '宏观数据-未完成的项目',
    'code'      => 'macro_countProject_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE type='project' AND `status`!='closed' AND deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1037,
    'name'      => '宏观数据-未完成的计划',
    'code'      => 'macro_countPlan_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '48',
    'sql'       => <<<EOT
SELECT id FROM (SELECT id,deleted FROM zt_productplan WHERE NOT ((`status`='closed' AND closedReason='done') OR `status`='done')) AS plan WHERE plan.deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1038,
    'name'      => '宏观数据-未完成的执行',
    'code'      => 'macro_countExecution_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '49',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE type IN ('sprint','stage','kanban') AND `status`!='closed' AND deleted='0' AND multiple = '1'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1039,
    'name'      => '宏观数据-未完成的缺陷',
    'code'      => 'macro_countBug_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '53',
    'sql'       => <<<EOT
SELECT id FROM zt_bug WHERE `status`!='closed' AND deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1040,
    'name'      => '宏观数据-未完成的任务',
    'code'      => 'macro_countTask_undone',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '52',
    'sql'       => <<<EOT
SELECT id FROM (SELECT id,deleted FROM zt_task WHERE `status` NOT IN ('closed','cancel','done')) AS task WHERE task.deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1041,
    'name'      => '宏观数据-项目集数据概览',
    'code'      => 'macro_programOverview',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '64',
    'sql'       => <<<EOT
SELECT
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
LEFT JOIN (SELECT SUBSTR(path, 2, POSITION(',' IN SUBSTR(path, 2)) -1) AS topProgram, COUNT(1) AS subProgram FROM zt_project WHERE deleted = '0' AND type = 'program' AND grade > 1 GROUP BY topProgram) AS t2 ON t1.id = t2.topProgram
LEFT JOIN zt_product AS t3 ON t1.id = t3.program AND t3.deleted = '0' AND t3.shadow = '0' AND t3.vision = 'rnd'
LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = '0' GROUP BY product) AS t4 ON t3.id = t4.product
LEFT JOIN (SELECT product, COUNT(1) AS "release" FROM zt_release WHERE deleted = '0' GROUP BY product) AS t5 ON t3.id = t5.product
LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = '0' GROUP BY product) AS t6 ON t3.id = t6.product
LEFT JOIN (
  SELECT t1.topProgram, COUNT(DISTINCT t1.project) AS project, SUM(t2.task) AS task, SUM(t3.execution) AS execution
  FROM (SELECT SUBSTR(path, 2, POSITION(',' IN SUBSTR(path, 2)) -1) AS topProgram, id AS project FROM zt_project WHERE deleted = '0' AND type = 'project') AS t1
  LEFT JOIN (SELECT COUNT(1) AS task, project FROM zt_task WHERE deleted = '0' GROUP BY project) AS t2 ON t1.project = t2.project
  LEFT JOIN (SELECT COUNT(1) AS execution,project FROM zt_project WHERE deleted = '0' AND type IN ('sprint', 'stage', 'kanban') GROUP BY project) AS t3 ON t1.project = t3.project
  GROUP BY t1.topProgram
) AS t7 ON t1.id = t7.topProgram
WHERE t1.deleted = '0' AND t1.type = 'program' AND t1.grade = 1
GROUP BY t1.name
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'topProgram', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'subProgram', 'valOrAgg' => 'value', 'name' => '子项目集数'),
            array('field' => 'product', 'valOrAgg' => 'value', 'name' => '产品数'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '研发需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数'),
            array('field' => 'project', 'valOrAgg' => 'value', 'name' => '项目数'),
            array('field' => 'execution', 'valOrAgg' => 'value', 'name' => '执行数'),
            array('field' => 'task', 'valOrAgg' => 'value', 'name' => '任务数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1042,
    'name'      => '宏观数据-项目集需求完成率与Bug修复率',
    'code'      => 'macro_programStoryFinishedRateAndBugFixedRate',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '45',
    'sql'       => <<<EOT
select
    t1.name as topProgram,
    sum(ifnull(t3.doneStory,0)) as doneStory,
    sum(ifnull(t4.allStory,0)) as allStory,
    cast(if(sum(ifnull(t4.allStory,0)) <= 0, 0, sum(ifnull(t3.doneStory,0)) / sum(ifnull(t4.allStory,0))*100) as decimal(10,2)) as storyDoneRate,
    sum(ifnull(t5.solvedBug,0)) as solvedBug,
    sum(ifnull(t6.allBug,0)) as allBug,
    cast(if(sum(ifnull(t6.allBug,0)) <= 0, 0, sum(ifnull(t5.solvedBug,0)) / sum(ifnull(t6.allBug,0))*100) as decimal(10,2)) as bugSolvedRate
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program
left join (select count(1) as doneStory, product from zt_story where deleted = '0' and closedReason = 'done' and status = 'closed' group by product) as t3 on t2.id = t3.product
left join (select count(1) as allStory, product from zt_story where deleted = '0' and ((closedReason = 'done' and status = 'closed') or status != 'closed') group by product) as t4 on t2.id = t4.product
left join (select count(1) as solvedBug, product from zt_bug where deleted = '0' and resolution = 'fixed' and status = 'closed' group by product) as t5 on t2.id = t5.product
left join (select count(1) as allBug, product from zt_bug where deleted = '0' and (resolution in ('fixed', 'postponed') or status = 'active') group by product) as t6 on t2.id = t6.product
where t1.type = 'program' and t1.grade = 1 and t1.deleted = '0'
and t2.deleted = '0'
group by t1.name, t1."order"
order by t1."order" desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'topProgram', 'name' => 'topProgram', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'storyDoneRate', 'name' => 'storyDoneRate', 'valOrAgg' => 'sum'),
                array('field' => 'bugSolvedRate', 'name' => 'bugSolvedRate', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'topProgram'    => array('name' => 'topProgram', 'object' => 'bug', 'field' => 'topProgram', 'type' => 'string'),
        'doneStory'     => array('name' => 'doneStory', 'object' => 'bug', 'field' => 'doneStory', 'type' => 'number'),
        'allStory'      => array('name' => 'allStory', 'object' => 'bug', 'field' => 'allStory', 'type' => 'number'),
        'storyDoneRate' => array('name' => 'storyDoneRate', 'object' => 'bug', 'field' => 'storyDoneRate', 'type' => 'number'),
        'solvedBug'     => array('name' => 'solvedBug', 'object' => 'bug', 'field' => 'solvedBug', 'type' => 'number'),
        'allBug'        => array('name' => 'allBug', 'object' => 'bug', 'field' => 'allBug', 'type' => 'number'),
        'bugSolvedRate' => array('name' => 'bugSolvedRate', 'object' => 'bug', 'field' => 'bugSolvedRate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'topProgram'    => array('zh-cn' => '一级项目集', 'zh-tw' => '', 'en' => 'topProgram', 'de' => '', 'fr' => ''),
        'doneStory'     => array('zh-cn' => '完成需求数', 'zh-tw' => '', 'en' => 'doneStory', 'de' => '', 'fr' => ''),
        'allStory'      => array('zh-cn' => '需求数', 'zh-tw' => '', 'en' => 'allStory', 'de' => '', 'fr' => ''),
        'storyDoneRate' => array('zh-cn' => '需求完成率', 'zh-tw' => '', 'en' => 'storyDoneRate', 'de' => '', 'fr' => ''),
        'solvedBug'     => array('zh-cn' => '解决bug数', 'zh-tw' => '', 'en' => 'solvedBug', 'de' => '', 'fr' => ''),
        'allBug'        => array('zh-cn' => 'bug数', 'zh-tw' => '', 'en' => 'allBug', 'de' => '', 'fr' => ''),
        'bugSolvedRate' => array('zh-cn' => 'bug修复率', 'zh-tw' => '', 'en' => 'bugSolvedRate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1043,
    'name'      => '宏观数据-公司项目集状态分布',
    'code'      => 'macro_programStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '45',
    'sql'       => <<<EOT
select id, case "status" when 'wait' then '未开始' when 'doing' then '进行中' when 'suspended' then '已挂起' else '已关闭' end status from zt_project where type = 'program' and grade = 1 and deleted = '0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'status', 'name' => '状态', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => '项目ID', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'     => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'status' => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'     => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'status' => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1044,
    'name'      => '宏观数据-公司项目状态分布',
    'code'      => 'macro_projectStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '38',
    'sql'       => <<<EOT
select id, case "status" when 'wait' then '未开始' when 'doing' then '进行中' when 'suspended' then '已挂起' else '已关闭' end status from zt_project where type = 'project' and deleted = '0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'status', 'name' => '状态', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => '项目ID', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'     => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'status' => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'     => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'status' => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1045,
    'name'      => '宏观数据-产品数据概览',
    'code'      => 'macro_productOverview',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '63',
    'sql'       => <<<EOT
SELECT
  t1.name AS product,
  IFNULL(t2.name, '/') AS program,
  IFNULL(t3.name, '/') AS productLine,
  IFNULL(t4.plan, 0) AS plan,
  IFNULL(t5.release, 0) AS `release`,
  IFNULL(t6.story, 0) AS story,
  IFNULL(t7.bug, 0) AS bug
FROM
  zt_product AS t1
  LEFT JOIN zt_project AS t2 ON t1.program = t2.id AND t2.type = 'program' AND t2.grade = 1
  LEFT JOIN zt_module AS t3 ON t1.line = t3.id AND t3.type = 'line'
  LEFT JOIN (SELECT product, COUNT(1) AS plan FROM zt_productplan WHERE deleted = '0' GROUP BY product) AS t4 ON t1.id = t4.product
  LEFT JOIN (SELECT product, COUNT(1) AS `release` FROM zt_release WHERE deleted = '0' GROUP BY product) AS t5 ON t1.id = t5.product
  LEFT JOIN (SELECT product, COUNT(1) AS story FROM zt_story WHERE deleted = '0' GROUP BY product) AS t6 ON t1.id = t6.product
  LEFT JOIN (SELECT product, COUNT(1) AS bug FROM zt_bug WHERE deleted = '0' GROUP BY product) AS t7 ON t1.id = t7.product
WHERE t1.deleted = '0' AND t1.status != 'closed' AND t1.shadow = '0'AND t1.vision = 'rnd'
ORDER BY t1.order
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'program', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'productLine', 'valOrAgg' => 'value', 'name' => '产品线'),
            array('field' => 'product', 'valOrAgg' => 'value', 'name' => '产品'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数'),
            array('field' => 'plan', 'valOrAgg' => 'value', 'name' => '计划数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1046,
    'name'      => '宏观数据-产品需求完成率',
    'code'      => 'macro_productStoryFinishedRate',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select
  t1.name as product,
  ifnull(t2.name, '/') as program,
  ifnull(t3.name, '/') as productLine,
  ifnull(t4.story, 0) as closedStory,
  t5.story as totalStory,
  round(ifnull(t4.story, 0) / t5.story * 100, 2) as closedRate
from zt_product as t1
left join zt_project as t2 on t1.program = t2.id and t2.type = 'program' and t2.grade = 1
left join zt_module as t3 on t1.line = t3.id and t3.type = 'line'
left join (select product, count(1) as story from zt_story where deleted = '0' and closedReason = 'done' group by product) as t4 on t1.id = t4.product
left join (select product, count(1) as story from zt_story where deleted = '0' and (closedReason = 'done' or status != 'closed') group by product) as t5 on t1.id = t5.product
where t1.deleted = '0' and t1.status != 'closed' and t1.shadow = '0' and t1.vision = 'rnd' and t5.story is not null
order by t1.order desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => '所属产品', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'closedRate', 'name' => 'closedRate', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'product'     => array('name' => '所属产品', 'object' => 'story', 'field' => 'product', 'type' => 'string'),
        'program'     => array('name' => 'program', 'object' => 'story', 'field' => 'program', 'type' => 'string'),
        'productLine' => array('name' => 'productLine', 'object' => 'story', 'field' => 'productLine', 'type' => 'string'),
        'closedStory' => array('name' => '需求：%s 已关闭，将不会被关闭。', 'object' => 'story', 'field' => 'closedStory', 'type' => 'string'),
        'totalStory'  => array('name' => 'totalStory', 'object' => 'story', 'field' => 'totalStory', 'type' => 'string'),
        'closedRate'  => array('name' => 'closedRate', 'object' => 'story', 'field' => 'closedRate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'product'     => array('zh-cn' => '所属产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'program'     => array('zh-cn' => '项目集', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'productLine' => array('zh-cn' => '产品线', 'zh-tw' => '', 'en' => 'productLine', 'de' => '', 'fr' => ''),
        'closedStory' => array('zh-cn' => '完成需求数', 'zh-tw' => '', 'en' => 'closedStory', 'de' => '', 'fr' => ''),
        'totalStory'  => array('zh-cn' => '需求数', 'zh-tw' => '', 'en' => 'totalStory', 'de' => '', 'fr' => ''),
        'closedRate'  => array('zh-cn' => '需求完成率', 'zh-tw' => '', 'en' => 'closedRate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1047,
    'name'      => '宏观数据-产品Bug修复率',
    'code'      => 'macro_productBugFixedRate',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '44',
    'sql'       => <<<EOT
select
  t1.name as product,
  ifnull(t2.name, '/') as program,
  ifnull(t3.name, '/') as productLine,
  ifnull(t4.bug, 0) as fixedBug,
  t5.bug as totalBug,
  round(ifnull(t4.bug, 0) / t5.bug * 100, 2) as fixedRate
from zt_product as t1
left join zt_project as t2 on t1.program = t2.id and t2.type = 'program' and t2.grade = 1
left join zt_module as t3 on t1.line = t3.id and t3.type = 'line'
left join (select product, count(1) as bug from zt_bug where deleted = '0' and resolution = 'fixed' and status = 'closed' group by product) as t4 on t1.id = t4.product
left join (select product, count(1) as bug from zt_bug where deleted = '0' and (resolution = 'fixed' or resolution = 'postponed' or status = 'active') group by product) as t5 on t1.id = t5.product
where t1.deleted = '0' and t1.status != 'closed' and t1.shadow = '0' and t1.vision = 'rnd' and t5.bug is not null
order by t1.order desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => '所属产品', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'fixedRate', 'name' => '修复率', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'product'     => array('name' => '所属产品', 'object' => 'bug', 'field' => 'product', 'type' => 'string'),
        'program'     => array('name' => 'program', 'object' => 'bug', 'field' => 'program', 'type' => 'string'),
        'productLine' => array('name' => 'productLine', 'object' => 'bug', 'field' => 'productLine', 'type' => 'string'),
        'fixedBug'    => array('name' => 'fixedBug', 'object' => 'bug', 'field' => 'fixedBug', 'type' => 'string'),
        'totalBug'    => array('name' => 'totalBug', 'object' => 'bug', 'field' => 'totalBug', 'type' => 'string'),
        'fixedRate'   => array('name' => '修复率', 'object' => 'bug', 'field' => 'fixedRate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'product'     => array('zh-cn' => '所属产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'program'     => array('zh-cn' => '项目集', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'productLine' => array('zh-cn' => '产品线', 'zh-tw' => '', 'en' => 'productLine', 'de' => '', 'fr' => ''),
        'fixedBug'    => array('zh-cn' => '修复bug数', 'zh-tw' => '', 'en' => 'fixedBug', 'de' => '', 'fr' => ''),
        'totalBug'    => array('zh-cn' => 'bug数', 'zh-tw' => '', 'en' => 'totalBug', 'de' => '', 'fr' => ''),
        'fixedRate'   => array('zh-cn' => 'bug修复率', 'zh-tw' => '', 'en' => 'fixedRate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1049,
    'name'      => '宏观数据-部门人员分布图',
    'code'      => 'macro_deptAccountStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select if(t3.id is not null, t3."name", '空') as deptName, count(1) as count,
if(t3.id is not null, t3."order", 9999) as deptOrder
from zt_user as t1
left join zt_dept as t2 on t1.dept = t2.id
left join zt_dept as t3 on (t2.path like '%' || t3.path || '%') and t3.grade = '1'
where t1.deleted = '0'
group by deptName, deptOrder
order by deptOrder asc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'deptName', 'name' => 'deptName', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'deptName'  => array('name' => 'deptName', 'object' => 'false', 'field' => 'deptName', 'type' => 'object'),
        'count'     => array('name' => 'count', 'object' => 'false', 'field' => 'count', 'type' => 'object'),
        'deptOrder' => array('name' => 'deptOrder', 'object' => 'false', 'field' => 'deptOrder', 'type' => 'object')
    ),
    'langs'     => array
    (
        'deptName'  => array('zh-cn' => '部门', 'zh-tw' => '', 'en' => 'deptName', 'de' => '', 'fr' => ''),
        'count'     => array('zh-cn' => '人数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => ''),
        'deptOrder' => array('zh-cn' => '顺序', 'zh-tw' => '', 'en' => 'deptOrder', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1050,
    'name'      => '宏观数据-公司角色分布图',
    'code'      => 'macro_roleStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'pie',
    'group'     => '56',
    'sql'       => <<<EOT
select
	account,
case
		role
		when 'dev' then
		'研发'
		when 'qa' then
		'测试'
		when 'pm' then
		'项目经理'
		when 'others' then
		'其他'
		when 'td' then
		'研发主管'
		when 'pd' then
		'产品主管'
		when 'po' then
		'产品经理'
		when 'qd' then
		'测试主管'
		when 'top' then
		'高层管理' else '未知'
	end "role"
from
	zt_user
where
	deleted = '0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'role', 'name' => '职位', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'account', 'name' => '用户名', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'account' => array('name' => '用户名', 'object' => 'user', 'field' => 'account', 'type' => 'string'),
        'role'    => array('name' => '职位', 'object' => 'user', 'field' => 'role', 'type' => 'string')
    ),
    'langs'     => array
    (
        'account' => array('zh-cn' => '用户名', 'zh-tw' => '', 'en' => 'account', 'de' => '', 'fr' => ''),
        'role'    => array('zh-cn' => '职位', 'zh-tw' => '', 'en' => 'role', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1051,
    'name'      => '宏观数据-人员工龄分布图',
    'code'      => 'macro_workingStatus',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select count(1) as count, '0-1年' as joindate from zt_user where deleted = '0' and "join" > (current_date() - interval '1 year')
union
select count(1) as count, '1-3年' as joindate from zt_user where deleted = '0' and "join" > (current_date() - interval '3 year') and "join" <= (current_date() - interval '1 year')
union
select count(1) as count, '3-5年' as joindate from zt_user where deleted = '0' and "join" > (current_date() - interval '5 year') and "join" <= (current_date() - interval '3 year')
union
select count(1) as count, '5-10年' as joindate from zt_user where deleted = '0' and "join" > (current_date() - interval '10 year') and "join" <= (current_date() - interval '5 year')
union
select count(1) as count, '10年以上' as joindate from zt_user where deleted = '0' and "join" < (current_date() - interval '10 year') and date_part('year', "join") != '0000'
union
select count(1) as count, '未知' as joindate from zt_user where deleted = '0' and date_part('year', "join") = '0000'
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'joinDate', 'name' => 'joinDate', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'count'    => array('name' => 'count', 'object' => 'user', 'field' => 'count', 'type' => 'string'),
        'joinDate' => array('name' => 'joinDate', 'object' => 'user', 'field' => 'joinDate', 'type' => 'string')
    ),
    'langs'     => array
    (
        'count'    => array('zh-cn' => '人数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => ''),
        'joinDate' => array('zh-cn' => '工龄', 'zh-tw' => '', 'en' => 'joinDate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1055,
    'name'      => '年度新增-一级项目集个数',
    'code'      => 'annualCreated_countTopProgram',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '45',
    'sql'       => <<<EOT
SELECT
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
		`type` = 'program'
		AND deleted = '0'
		AND grade = '1'
	) t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1056,
    'name'      => '年度新增-产品个数',
    'code'      => 'annualCreated_countProduct',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
SELECT
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
		deleted = '0'
		AND shadow = '0'
	) t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1057,
    'name'      => '年度新增-需求个数',
    'code'      => 'annualCreated_countStory',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '51',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( openedDate ) AS `year` FROM zt_story WHERE deleted = '0' ) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1058,
    'name'      => '年度新增-Bug个数',
    'code'      => 'annualCreated_countBug',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '53',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( openedDate ) AS `year` FROM zt_bug WHERE deleted = '0' ) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1059,
    'name'      => '年度新增-计划个数',
    'code'      => 'annualCreated_countPlan',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '48',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( createdDate ) AS `year` FROM zt_productplan WHERE deleted = '0') AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1060,
    'name'      => '年度新增-项目个数',
    'code'      => 'annualCreated_countProject',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT
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
		`type` = 'project'
		AND deleted = '0'
	) t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1061,
    'name'      => '年度新增-执行个数',
    'code'      => 'annualCreated_countExecution',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '49',
    'sql'       => <<<EOT
SELECT t1.`year`, t2.id, t2.name
FROM (SELECT DISTINCT YEAR(`date`) AS "year" FROM zt_action) AS t1
LEFT JOIN (SELECT id,name, YEAR(openedDate) AS `year` FROM zt_project WHERE `type` IN ( 'sprint', 'stage', 'kanban' ) AND deleted = '0' AND multiple = '1') AS t2 ON t1.`year` = t2.`year`
WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1062,
    'name'      => '年度新增-任务数',
    'code'      => 'annualCreated_countTask',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '52',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( openedDate ) AS `year` FROM zt_task WHERE deleted = '0') AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1063,
    'name'      => '年度新增-文档个数',
    'code'      => 'annualCreated_countDoc',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '54',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.title
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, title, YEAR ( addedDate ) AS `year` FROM zt_doc WHERE deleted = '0') AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1064,
    'name'      => '年度新增-发布个数',
    'code'      => 'annualCreated_countRelease',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '50',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( `date` ) AS `year` FROM zt_release WHERE deleted = '0') AS t2 ON t1.`year` = t2.`year`
WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1065,
    'name'      => '年度新增-人员个数',
    'code'      => 'annualCreated_countAccount',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '56',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.account,
	t2.realname
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN (
	SELECT
		account, realname,
		YEAR ( t112.`date` ) AS 'year'
	FROM
		zt_user AS t111
		LEFT JOIN zt_action t112 ON t111.id = t112.objectID
		AND t112.objectType = 'user'
	WHERE
		t111.deleted = '0'
		AND t112.action = 'created'
	) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.account IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'account', 'agg' => 'count'),
        'title' => array('type' => 'text', 'realname' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1066,
    'name'      => '年度新增-完成项目数',
    'code'      => 'annualCreated_countPorject_finished',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT
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
`type` = 'project'
AND deleted = '0'
) t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1067,
    'name'      => '年度新增-完成执行数',
    'code'      => 'annualCreated_countExecution_finished',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '49',
    'sql'       => <<<EOT
SELECT t1.`year`, t2.id, t2.name
FROM (SELECT DISTINCT YEAR(date) AS "year" FROM zt_action) AS t1
LEFT JOIN (SELECT id, name, YEAR(closedDate) AS `year` FROM zt_project WHERE `type` IN ( 'sprint', 'stage', 'kanban' ) AND deleted = '0' AND multiple = '1' AND status = 'closed') AS t2 ON t1.`year` = t2.`year`
WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1068,
    'name'      => '年度新增-完成发布数',
    'code'      => 'annualCreated_countRelease_finished',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '50',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	t2.id,
	t2.name
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT id, name, YEAR ( `date` ) AS `year` FROM zt_release WHERE deleted = '0') AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1069,
    'name'      => '年度新增-完成需求数',
    'code'      => 'annualCreated_countStory_finished',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '51',
    'sql'       => <<<EOT
SELECT
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
		deleted = '0'
		AND closedReason = 'done'
		AND STATUS = 'closed'
	) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1070,
    'name'      => '年度新增-解决Bug数',
    'code'      => 'annualCreated_countBug_fixed',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '53',
    'sql'       => <<<EOT
SELECT
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
		deleted = '0'
		AND resolution = 'fixed'
		AND STATUS = 'closed'
	) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1071,
    'name'      => '年度新增-完成任务数',
    'code'      => 'annualCreated_countTask_finished',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '52',
    'sql'       => <<<EOT
SELECT
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
		deleted = '0'
		AND STATUS = 'closed'
		AND closedReason = 'done'
	) AS t2 ON t1.`year` = t2.`year`
 WHERE t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1072,
    'name'      => '年度新增-投入工时数',
    'code'      => 'annualCreated_consumed',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '57',
    'sql'       => <<<EOT
SELECT
	t1.`year`,
	IFNULL( t2.consumed, 0 ) AS consumed
FROM
	( SELECT DISTINCT YEAR ( `date` ) AS "year" FROM zt_action ) AS t1
	LEFT JOIN ( SELECT ROUND( SUM( consumed )) AS consumed, YEAR ( `date` ) AS "year" FROM zt_effort WHERE deleted = '0' GROUP BY `year`) AS t2 ON t1.`year` = t2.`year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'consumed', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1073,
    'name'      => '年度新增-项目集年度新增数据汇总表',
    'code'      => 'annualCreated_programOverview_created',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '64',
    'sql'       => <<<EOT
select tt.topProgram,tt.programID as id,tt.`year`,sum(tt.product) as product,sum(tt.plan) as plan,sum(tt.`release`) as `release`,sum(tt.story) as story,sum(tt.bug) as bug,sum(tt.doc) as doc
from (
select t2.name as topProgram,t2.id as programID,t0.`year`,count(1) as product,0 as plan,0 as story,0 as bug,0 as `release`, 0 as doc
from zt_product t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.createdDate) = t0.`year`
left join zt_project t2 on t1.program = t2.id
where t1.deleted = '0' and t1.shadow = '0'
and t2.type = 'program' and t2.grade = 1 and t2.deleted = '0'
group by t2.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,count(1) as plan,0 as story,0 as bug,0 as `release`, 0 as doc
from zt_productplan t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.createdDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,0 as plan,0 as story,0 as bug,0 as `release`, count(1) as doc
from zt_doc t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.addedDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,0 as plan,0 as story,0 as bug,0 as `release`, count(distinct t1.id) as doc
from zt_doc t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.addedDate) = t0.`year`
left join zt_projectproduct t4 on t1.project = t4.project
left join zt_product t2 on t2.id = t4.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,0 as plan,0 as story,0 as bug,count(1) as `release`, 0 as doc
from zt_release t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.date) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,0 as plan,count(1) as story,0 as bug,0 as `release`, 0 as doc
from zt_story t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.openedDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as product,0 as plan,0 as story,count(1) as bug,0 as `release`, 0 as doc
from zt_bug t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.openedDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
) tt
group by tt.programID, tt.`year`
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'topProgram', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'product', 'valOrAgg' => 'value', 'name' => '产品数'),
            array('field' => 'plan', 'valOrAgg' => 'value', 'name' => '计划数'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数'),
            array('field' => 'doc', 'valOrAgg' => 'value', 'name' => '文档数')
        ),
        'filter' => array()
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1074,
    'name'      => '年度新增-项目集年度完成数据概览',
    'code'      => 'annualCreated_programOverview_finished',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '64',
    'sql'       => <<<EOT
select tt.topProgram,tt.programID as id,tt.`year`,sum(tt.projectA) as projectA,sum(tt.executionA) as executionA,sum(tt.releaseA) as `release`,sum(tt.storyA) as story,sum(tt.bugA) as bug
from (
select t2.name as topProgram,t2.id as programID,t0.`year`,count(1) as projectA,0 as executionA,0 as releaseA,0 as storyA,0 as bugA
from zt_project t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.closedDate) = t0.`year`
left join zt_project t2 on FIND_IN_SET(t2.id, t1.path)
where t1.type = 'project' and t1.deleted = '0'
and t2.type = 'program' and t2.grade = 1 and t2.deleted = '0'
and t1.`status` = 'closed'
group by t2.id, t0.`year`
union all
select t3.name as topProgram, t3.id as programID,t0.`year`,0 as projectA,count(1) as executionA,0 as releaseA,0 as storyA,0 as bugA
from zt_project t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.closedDate) = t0.`year`
left join zt_project t2 on t1.parent = t2.id
left join zt_project t3 on FIND_IN_SET(t3.id, t2.path)
where t1.type in ('sprint', 'stage', 'kanban') and t1.deleted = '0'
and t2.type = 'project' and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
and t1.`status` = 'closed'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as projectA,0 as executionA,count(1) as releaseA,0 as storyA,0 as bugA
from zt_release t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.date) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as projectA,0 as executionA,0 as releaseA,count(1) as storyA,0 as bugA
from zt_story t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.closedDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0' and t1.closedReason = 'done' and t1.status = 'closed'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
union all
select t3.name as topProgram,t3.id as programID,t0.`year`,0 as projectA,0 as executionA,0 as releaseA,0 as storyA,count(1) as bugA
from zt_bug t1
left join (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) t0 on YEAR(t1.resolvedDate) = t0.`year`
left join zt_product t2 on t2.id = t1.product
left join zt_project t3 on t2.program = t3.id
where t1.deleted = '0' and t1.resolution = 'fixed' and t1.status = 'closed'
and t2.deleted = '0'
and t3.type = 'program' and t3.grade = 1 and t3.deleted = '0'
group by t3.id, t0.`year`
) tt
group by tt.programID, tt.`year`
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'topProgram', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'projectA', 'valOrAgg' => 'value', 'name' => '项目数'),
            array('field' => 'executionA', 'valOrAgg' => 'value', 'name' => '执行数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数')
        ),
        'filter' => array()
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1075,
    'name'      => '年度新增-产品年度新增数据汇总表',
    'code'      => 'annualCreated_productOverview_created',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '63',
    'sql'       => <<<EOT
SELECT
    t1.name,t1.id,t2.`year`,IF(YEAR(t1.createdDate) = t2.`year`, 1, 0) as newProduct,
    SUM(IFNULL(t3.story, 0)) AS story,
    SUM(IFNULL(t4.bug, 0)) AS bug,
    SUM(IFNULL(t5.`plan`, 0)) AS 'plan',
    SUM(IFNULL(t6.`release`, 0)) AS 'release'
FROM zt_product AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as 'year' FROM zt_action) as t2 ON 1 = 1
LEFT JOIN (SELECT COUNT(1) as 'story', product, YEAR(openedDate) as `year` FROM zt_story WHERE deleted = '0' GROUP BY product, `year`) AS t3 on t1.id = t3.product AND t3.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as 'bug', product, YEAR(openedDate) as `year` FROM zt_bug WHERE deleted = '0' GROUP BY product, `year`) AS t4 on t1.id = t4.product AND t4.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as 'plan', product, YEAR(createdDate) AS 'year' FROM zt_productplan WHERE deleted = '0' GROUP BY product,`year`) AS t5 on t1.id = t5.product AND t5.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as 'release', product, YEAR(`date`) as `year` FROM zt_release WHERE deleted = '0' GROUP BY product, `year`) AS t6 ON t1.id = t6.product AND t6.`year` = t2.`year`
WHERE t1.deleted = '0' AND t1.status != 'closed' AND t1.shadow = '0'
GROUP BY t1.name,t1.id,t2.`year`,newProduct
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '产品'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数'),
            array('field' => 'plan', 'valOrAgg' => 'value', 'name' => '计划数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数')
        ),
        'filter' => array()
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1076,
    'name'      => '年度新增-产品年度完成数据汇总表',
    'code'      => 'annualCreated_productOverview_finished',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '63',
    'sql'       => <<<EOT
SELECT
    t1.name,t1.id,t2.`year`,IF(YEAR(t1.createdDate) = t2.`year`, 1, 0) as newProduct,
    SUM(IFNULL(t3.story, 0)) AS story,
    SUM(IFNULL(t4.bug, 0)) AS bug,
    SUM(IFNULL(t5.`plan`, 0)) AS 'plan',
    SUM(IFNULL(t6.`release`, 0)) AS 'release'
FROM zt_product AS t1
LEFT JOIN (SELECT DISTINCT YEAR(`date`) as "year" FROM zt_action) as t2 ON 1 = 1
LEFT JOIN (SELECT COUNT(1) as 'story', product, YEAR(closedDate) as `year` FROM zt_story WHERE deleted = '0' AND closedReason = 'done' AND status = 'closed' GROUP BY product, `year`) AS t3 on t1.id = t3.product AND t3.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as 'bug', product, YEAR(resolvedDate) as `year` FROM zt_bug WHERE deleted = '0' AND resolution = 'fixed' AND status = 'closed' GROUP BY product, `year`) AS t4 on t1.id = t4.product AND t4.`year` = t2.`year`
LEFT JOIN (
    SELECT COUNT(DISTINCT t51.id) as 'plan', t51.product, YEAR(t52.`date`) AS "year"
    FROM zt_productplan AS t51
    LEFT JOIN (SELECT objectID,objectType,action,MAX(`date`) as 'date' FROM zt_action GROUP BY objectID,objectType, action) AS t52 ON t51.id = t52.objectID AND t52.objectType = 'productplan'
    WHERE t51.deleted = '0' AND t51.closedReason = 'done' AND t51.status = 'closed'
    AND t52.action = 'closed'
    GROUP BY t51.product,`year`
) AS t5 on t1.id = t5.product AND t5.`year` = t2.`year`
LEFT JOIN (SELECT COUNT(1) as 'release', product, YEAR(`date`) as `year` FROM zt_release WHERE deleted = '0' GROUP BY product, `year`) AS t6 ON t1.id = t6.product AND t6.`year` = t2.`year`
WHERE t1.deleted = '0' AND t1.status != 'closed' AND t1.shadow = '0'
GROUP BY t1.name,t1.id,t2.`year`,newProduct
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '产品'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '需求数'),
            array('field' => 'bug', 'valOrAgg' => 'value', 'name' => 'Bug数'),
            array('field' => 'plan', 'valOrAgg' => 'value', 'name' => '计划数'),
            array('field' => 'release', 'valOrAgg' => 'value', 'name' => '发布数')
        ),
        'filter' => array()
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1077,
    'name'      => '年度新增-需求年度新增和完成趋势图',
    'code'      => 'annualCreated_storyTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '36',
    'sql'       => <<<EOT
select t1.yearmonth, t1.year, t1.month as "month", ifnull(t2.story, 0) as newStory, ifnull(t3.story, 0) as closedStory
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as "year", month(date) as "month" from zt_action) as t1
left join (select year(openedDate) as "year", month(openedDate) as "month", count(1) as story from zt_story where deleted = '0' group by "year", "month") as t2 on t1.year = t2.year and t1.month = t2.month
left join (select year(closedDate) as "year", month(closedDate) as "month", count(1) as story from zt_story where deleted = '0' and closedReason = 'done' group by "year", "month") as t3 on t1.year = t3.year and t1.month = t3.month
order by t1."year", t1."month"
EOT,
    'settings'  => array
    (
        array
        (
            'type'    => 'line',
            'xaxis'   => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis'   => array
            (
                array('field' => 'newStory', 'name' => '继续添加研发需求', 'valOrAgg' => 'sum'),
                array('field' => 'closedStory', 'name' => '需求：%s 已关闭，将不会被关闭。', 'valOrAgg' => 'sum')
            ),
            'rotateX' => 'notuse'
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth'   => array('name' => 'yearmonth', 'object' => 'story', 'field' => 'yearmonth', 'type' => 'string'),
        'year'        => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'month'       => array('name' => 'month', 'object' => 'story', 'field' => 'month', 'type' => 'number'),
        'newStory'    => array('name' => '继续添加研发需求', 'object' => 'story', 'field' => 'newStory', 'type' => 'string'),
        'closedStory' => array('name' => '需求：%s 已关闭，将不会被关闭。', 'object' => 'story', 'field' => 'closedStory', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth'   => array('zh-cn' => 'yearmonth', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'        => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'       => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'newStory'    => array('zh-cn' => '新增需求数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'closedStory' => array('zh-cn' => '完成需求数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1078,
    'name'      => '年度新增-Bug年度新增和解决趋势图',
    'code'      => 'annualCreated_bugTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '44',
    'sql'       => <<<EOT
select yearmonth, t1.year, t1.month as month, ifnull(t2.bug, 0) as newBug, ifnull(t3.bug, 0) as fixedBug
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as year, month(date) as month from zt_action) as t1
left join (select year(openedDate) as year, month(openedDate) as month, count(1) as bug from zt_bug where deleted = '0' group by year, month) as t2
ON t1.year = t2.year and t1.month = t2.month
left join (select year(closedDate) as year, month(closedDate) as month, count(1) as bug from zt_bug where deleted = '0' and resolution = 'fixed' and status = 'closed' group by year, month) as t3
on t1.year = t3.year and t1.month = t3.month
order by t1.year, t1.month
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'newBug', 'name' => 'newBug', 'valOrAgg' => 'sum'),
                array('field' => 'fixedBug', 'name' => 'fixedBug', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth' => array('name' => 'yearmonth', 'object' => 'bug', 'field' => 'yearmonth', 'type' => 'string'),
        'year'      => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'month'     => array('name' => 'month', 'object' => 'bug', 'field' => 'month', 'type' => 'number'),
        'newBug'    => array('name' => 'newBug', 'object' => 'bug', 'field' => 'newBug', 'type' => 'string'),
        'fixedBug'  => array('name' => 'fixedBug', 'object' => 'bug', 'field' => 'fixedBug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth' => array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'      => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'     => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'newBug'    => array('zh-cn' => '新增Bug数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'fixedBug'  => array('zh-cn' => '解决Bug数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1079,
    'name'      => '年度新增-任务年度新增和完成趋势图',
    'code'      => 'annualCreated_taskTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '39',
    'sql'       => <<<EOT
select yearmonth, t1.year, t1.month || '月' as "month", ifnull(t2.task, 0) as newTask, ifnull(t3.task, 0) as closedTask
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as "year", month(date) as "month" from zt_action) as t1
left join (select year(openedDate) as "year", month(openedDate) as "month", count(1) as task from zt_task where deleted = '0' group by "year", "month") as t2 on t1.year = t2.year and t1.month = t2.month
left join (select year(closedDate) as "year", month(closedDate) as "month", count(1) as task from zt_task where deleted = '0' and status = 'closed' group by "year", "month") as t3 on t1.year = t3.year and t1.month = t3.month
order by t1."year", t1."month"
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'newTask', 'name' => 'newTask', 'valOrAgg' => 'sum'),
                array('field' => 'closedTask', 'name' => 'closedTask', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth'  => array('name' => 'yearmonth', 'object' => 'task', 'field' => 'yearmonth', 'type' => 'string'),
        'year'       => array('name' => 'year', 'object' => 'task', 'field' => 'year', 'type' => 'number'),
        'month'      => array('name' => 'month', 'object' => 'task', 'field' => 'month', 'type' => 'string'),
        'newTask'    => array('name' => 'newTask', 'object' => 'task', 'field' => 'newTask', 'type' => 'string'),
        'closedTask' => array('name' => 'closedTask', 'object' => 'task', 'field' => 'closedTask', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth'  => array('zh-cn' => 'yearmonth', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'       => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'      => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'newTask'    => array('zh-cn' => '新增任务数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'closedTask' => array('zh-cn' => '完成任务数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1080,
    'name'      => '年度新增-项目年度新增和完成趋势图',
    'code'      => 'annualCreated_projectTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '38',
    'sql'       => <<<EOT
select yearmonth, t1.year, t1.month || '月' as "month", ifnull(t2.project, 0) as newProject, ifnull(t3.project, 0) as closedProject
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as "year", month(date) as "month" from zt_action) as t1
left join (select year(openedDate) as "year", month(openedDate) as "month", count(1) as project from zt_project where deleted = '0' and type = 'project' group by "year", "month") as t2 on t1.year = t2.year and t1.month = t2.month
left join (select year(closedDate) as "year", month(closedDate) as "month", count(1) as project from zt_project where deleted = '0' and type = 'project' and status = 'closed' group by "year", "month") as t3 on t1.year = t3.year and t1.month = t3.month
order by t1."year", t1."month"
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'newProject', 'name' => 'newProject', 'valOrAgg' => 'sum'),
                array('field' => 'closedProject', 'name' => '已关闭的项目', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth'     => array('name' => 'yearmonth', 'object' => 'project', 'field' => 'yearmonth', 'type' => 'string'),
        'year'          => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number'),
        'month'         => array('name' => 'month', 'object' => 'project', 'field' => 'month', 'type' => 'string'),
        'newProject'    => array('name' => 'newProject', 'object' => 'project', 'field' => 'newProject', 'type' => 'string'),
        'closedProject' => array('name' => '已关闭的项目', 'object' => 'project', 'field' => 'closedProject', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth'     => array('zh-cn' => 'yearmonth', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'          => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'         => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'newProject'    => array('zh-cn' => '新增项目数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'closedProject' => array('zh-cn' => '完成项目数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1081,
    'name'      => '年度新增-执行年度新增和完成趋势图',
    'code'      => 'annualCreated_executionTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '40',
    'sql'       => <<<EOT
select yearmonth, t1.year, t1.month || '月' as "month", ifnull(t2.execution, 0) as newExecution, ifnull(t3.execution, 0) as closedExecution
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as "year", month(date) as "month" from zt_action) as t1
left join (select year(openedDate) as "year", month(openedDate) as "month", count(1) as execution from zt_project where deleted = '0' and type in ('sprint', 'stage', 'kanban') and multiple = '1' group by "year", "month") as t2 on t1.year = t2.year and t1.month = t2.month
left join (select year(closedDate) as "year", month(closedDate) as "month", count(1) as execution from zt_project where deleted = '0' and type in ('sprint', 'stage', 'kanban') and status = 'closed' and multiple = '1' group by "year", "month") as t3 on t1.year = t3.year and t1.month = t3.month
order by t1."year", t1."month"
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'newExecution', 'name' => 'newExecution', 'valOrAgg' => 'sum'),
                array('field' => 'closedExecution', 'name' => 'closedExecution', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth'       => array('name' => 'yearmonth', 'object' => 'project', 'field' => 'yearmonth', 'type' => 'string'),
        'year'            => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number'),
        'month'           => array('name' => 'month', 'object' => 'project', 'field' => 'month', 'type' => 'string'),
        'newExecution'    => array('name' => 'newExecution', 'object' => 'project', 'field' => 'newExecution', 'type' => 'string'),
        'closedExecution' => array('name' => 'closedExecution', 'object' => 'project', 'field' => 'closedExecution', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth'       => array('zh-cn' => 'yearmonth', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'            => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'           => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'newExecution'    => array('zh-cn' => '新增执行数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'closedExecution' => array('zh-cn' => '完成执行数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1082,
    'name'      => '年度新增-产品发布次数年度趋势图',
    'code'      => 'annualCreated_releaseTendency',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '37',
    'sql'       => <<<EOT
select yearmonth, t1.year, t1.month || '月' as "month", ifnull(t2.release, 0) as "release"
from (select distinct printf('%04d-%02d', year(date), month(date)) yearmonth, year(date) as "year", month(date) as "month" from zt_action) as t1
left join (select year(createdDate) as "year", month(createdDate) as "month", count(1) as "release" from zt_release where deleted = '0' group by "year", "month") as t2 on t1.year = t2.year and t1.month = t2.month
order by t1."year", t1."month"
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'yearmonth', 'name' => 'yearmonth', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'release', 'name' => 'release', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年度')
    ),
    'fields'    => array
    (
        'yearmonth' => array('name' => 'yearmonth', 'object' => 'release', 'field' => 'yearmonth', 'type' => 'string'),
        'year'      => array('name' => 'year', 'object' => 'release', 'field' => 'year', 'type' => 'number'),
        'month'     => array('name' => 'month', 'object' => 'release', 'field' => 'month', 'type' => 'string'),
        'release'   => array('name' => 'release', 'object' => 'release', 'field' => 'release', 'type' => 'string')
    ),
    'langs'     => array
    (
        'yearmonth' => array('zh-cn' => 'yearmonth', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'year'      => array('zh-cn' => '年度', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'month'     => array('zh-cn' => '月份', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'release'   => array('zh-cn' => '发布次数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1083,
    'name'      => '年度新增-年度投入产出比',
    'code'      => 'annualCreated_IORatio',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '45',
    'sql'       => <<<EOT
select * from
(select t1.year, t1.month || '月' as month, ifnull(t2.story, 0) as story, ifnull(t3.consumed, 0) as consumed, round(if(ifnull(t3.consumed, 0) = 0, 0, ifnull(t2.story, 0) / ifnull(t3.consumed, 0)), 2) as ratio
from (select datepart('year', "date") as year, datepart('month', "date") as month from zt_action group by year, month) as t1
left join (select round(sum(estimate)) as story, datepart('year', "closedDate") as year, datepart('month', "closedDate") as month from zt_story where deleted = '0' and closedReason = 'done' and status = 'closed' group by year, month) as t2 on t1.year = t2.year and t1.month = t2.month
left join (select round(sum(consumed)) as consumed, datepart('year', "date") as year, datepart('month', "date") as month from zt_effort where deleted = '0' group by year, month) as t3 on t1.year = t3.year and t1.month = t3.month) tt
order by year, month
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'month', 'name' => '月份', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'value', 'field' => 'ratio', 'agg' => 'value', 'name' => '投入产出比', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'story', 'agg' => 'value', 'name' => '需求交付', 'valOrAgg' => 'value'),
            array('type' => 'value', 'field' => 'consumed', 'agg' => 'value', 'name' => '工时消耗', 'valOrAgg' => 'value')
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1085,
    'name'      => '年度排行-项目集-预算投入榜',
    'code'      => 'annualRank_programBudget',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '41',
    'sql'       => <<<EOT
select
  year(t2.openedDate) as "year",
  t1.id,
  t1.name as program,
  round(
    sum(
      ifnull(try_cast(t2.budget as integer), 0)
    ) / 10000,
    2
  ) as budget
from
  zt_project as t1
  left join zt_project as t2 on (',' || t2.path || ',' like '%,' || t1.id || ',%')
  and t2.deleted = '0'
  and t2.type = 'project'
where
  t1.deleted = '0'
  and t1.type = 'program'
  and t1.grade = 1
group by
  "year",
  t1.id,
  program
order by
  "year",
  budget desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'budget', 'name' => '预算', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'budget'  => array('name' => '预算', 'object' => 'project', 'field' => 'budget', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'budget'  => array('zh-cn' => '项目集预算', 'zh-tw' => '', 'en' => 'budget', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1086,
    'name'      => '年度排行-项目集-人员投入榜',
    'code'      => 'annualRank_programPersonnel',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '41',
    'sql'       => <<<EOT
select tt.join as "year", count(1) as "number", tt.setName from (
select
year(t1.join) as "join", t4.name as setName
from zt_team t1
right join zt_project t2 on t2.id = t1.root
left join zt_project t4 on (',' || t2.path || ',' like '%,' || t4.id || ',%') and t4.grade = 1
right join zt_user t3 on t3.account = t1.account
where t1.type = 'project'
and t2.deleted = '0'
and t3.deleted = '0'
) tt
group by tt.setName, tt.join
order by tt.join, "number" desc, tt.setName
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'setName', 'name' => 'setName', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'number', 'name' => 'number', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'user', 'field' => 'year', 'type' => 'number'),
        'number'  => array('name' => 'number', 'object' => 'user', 'field' => 'number', 'type' => 'string'),
        'setName' => array('name' => 'setName', 'object' => 'user', 'field' => 'setName', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'number'  => array('zh-cn' => '人员数量', 'zh-tw' => '', 'en' => 'number', 'de' => '', 'fr' => ''),
        'setName' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'setName', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1087,
    'name'      => '年度排行-项目集-工时消耗榜',
    'code'      => 'annualRank_programConsumed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '41',
    'sql'       => <<<EOT
select
  year(t5.date) as "year",
  t1.id,
  t1.name as program,
  round(
    sum(t5.consumed),
    2
  ) as consumed
from
  zt_project as t1
  left join zt_project as t2 on (',' || t2.path || ',' like '%,' || t1.id || ',%')
  and t2.deleted = '0'
  and t2.type = 'project'
  left join zt_project as t3 on t2.id = t3.parent
  and t3.deleted = '0'
  and t3.type in ('sprint', 'stage', 'kanban')
  left join zt_task as t4 on t3.id = t4.execution
  and t4.deleted = '0'
  and t4.status != 'cancel'
  left join zt_effort as t5 on t4.id = t5.objectID
  and t5.deleted = '0'
  and t5.objectType = 'task'
where
  t1.deleted = '0'
  and t1.type = 'program'
  and t1.grade = 1
  and t5.id is not null
group by
  "year",
  t1.id,
  program
order by
  "year",
  consumed desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'consumed', 'name' => '总计消耗', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'effort', 'field' => 'year', 'type' => 'number'),
        'id'       => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program'  => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'consumed' => array('name' => '总计消耗', 'object' => 'task', 'field' => 'consumed', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'       => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program'  => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'consumed' => array('zh-cn' => '项目集总计消耗', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1088,
    'name'      => '年度排行-项目集-新增需求条目榜',
    'code'      => 'annualRank_programStoryCount_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t3.openedDate) as year, t1.id, t1.name as program, count(1) as story
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
left join zt_story as t3 on t2.id = t3.product and t3.deleted = '0'
where t1.deleted = '0'
and t1.type = 'program'
and t1.grade = 1
and t3.id is not null
group by year, t1.id, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '新增研发需求计数', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1089,
    'name'      => '年度排行-项目集-新增需求规模榜',
    'code'      => 'annualRank_programStoryEstimate_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t3.openedDate) as year, t1.id, t1.name as program, round(sum(t3.estimate), 2) as story
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
left join zt_story as t3 on t2.id = t3.product and t3.deleted = '0'
where t1.deleted = '0'
and t1.type = 'program'
and t1.grade = 1
and t3.id is not null
group by year, t1.id, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '新增研发需求规模', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1090,
    'name'      => '年度排行-项目集-新增Bug条目榜',
    'code'      => 'annualRank_programBug_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '44',
    'sql'       => <<<EOT
select year(t3.openedDate) as `year`, t1.id, t1.name as program, count(1) as bug
from zt_project as t1
    left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
    left join zt_bug as t3 on t2.id = t3.product and t3.deleted = '0'
where t1.deleted = '0' and t1.type = 'program' and t1.grade = 1 and t3.id is not null
group by `year`, t1.id, t1.name
order by `year`, bug desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'bug', 'name' => 'Bug列表', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'bug'     => array('name' => 'Bug列表', 'object' => 'project', 'field' => 'bug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'bug'     => array('zh-cn' => '新增Bug计数', 'zh-tw' => '', 'en' => 'bug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1091,
    'name'      => '年度排行-项目集-完成需求条目榜',
    'code'      => 'annualRank_programStoryCount_finished',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '43',
    'sql'       => <<<EOT
select year(t3.closedDate) as year, t1.id, t1.name as program, count(1) as story
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
left join zt_story as t3 on t2.id = t3.product and t3.deleted = '0' and t3.closedReason = 'done'
where t1.deleted = '0'
and t1.type = 'program'
and t1.grade = 1
and t3.id is not null
group by year, t1.id, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目集编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '完成研发需求求和', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1092,
    'name'      => '年度排行-项目集-完成需求规模榜',
    'code'      => 'annualRank_programStoryEstimate_finished',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t3.closedDate) as year, t1.id, t1.name as program, round(sum(t3.estimate), 2) as story
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
left join zt_story as t3 on t2.id = t3.product and t3.deleted = '0' and t3.closedReason = 'done'
where t1.deleted = '0'
and t1.type = 'program'
and t1.grade = 1
and t3.id is not null
group by year, t1.id, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '研发需求预计工时求和', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1093,
    'name'      => '年度排行-项目集-修复Bug条目榜',
    'code'      => 'annualRank_programBug_fixed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '44',
    'sql'       => <<<EOT
select year(t3.closedDate) as `year`, t1.id, t1.name as program, count(1) as bug
from zt_project as t1
left join zt_product as t2 on t1.id = t2.program and t2.deleted = '0'
left join zt_bug as t3 on t2.id = t3.product and t3.deleted = '0' and t3.resolution = 'fixed' and t3.status = 'closed'
where t1.deleted = '0'
and t1.type = 'program'
and t1.grade = 1
and t3.id is not null
group by year, t1.id, t1.name
order by year, bug desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'program', 'name' => 'program', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'bug', 'name' => 'Bug列表', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'program' => array('name' => 'program', 'object' => 'zt_project', 'field' => 'program', 'type' => 'string'),
        'bug'     => array('name' => 'Bug列表', 'object' => 'project', 'field' => 'bug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'program' => array('zh-cn' => '项目集名称', 'zh-tw' => '', 'en' => 'program', 'de' => '', 'fr' => ''),
        'bug'     => array('zh-cn' => 'Bug计数', 'zh-tw' => '', 'en' => 'bug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1094,
    'name'      => '年度排行-项目-工期榜',
    'code'      => 'annualRank_projectDuration',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '42',
    'sql'       => <<<EOT
select year, id, name, status, realBegan, realEnd,
    if(status = 'closed', datediff('day', realBegan, realEnd), datediff('day', realBegan, current_date())) as duration
from (select distinct year(date) as year from zt_action) as t1
left join zt_project as t2 on 1 = 1
where deleted = '0'
and type = 'project'
and year(realBegan) <= year
and year(realBegan) is not null
and (status ='doing' or (status = 'suspended' and year(suspendedDate) >= year)
or (status = 'closed' and year(realEnd) >= year))
order by year, duration desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => '项目名称', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'duration', 'name' => 'duration', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'      => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number'),
        'id'        => array('name' => '编号', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'name'      => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'    => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option'),
        'realBegan' => array('name' => '实际开始日期', 'object' => 'project', 'field' => 'realBegan', 'type' => 'date'),
        'realEnd'   => array('name' => '实际完成日期', 'object' => 'project', 'field' => 'realEnd', 'type' => 'date'),
        'duration'  => array('name' => 'duration', 'object' => 'project', 'field' => 'duration', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'      => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'        => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'name'      => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'status'    => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => ''),
        'realBegan' => array('zh-cn' => '实际开始日期', 'zh-tw' => '', 'en' => 'realBegan', 'de' => '', 'fr' => ''),
        'realEnd'   => array('zh-cn' => '实际完成日期', 'zh-tw' => '', 'en' => 'realEnd', 'de' => '', 'fr' => ''),
        'duration'  => array('zh-cn' => '工期', 'zh-tw' => '', 'en' => 'duration', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1096,
    'name'      => '年度排行-项目-工期偏差榜',
    'code'      => 'annualRank_projectDurationDeviation',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '42',
    'sql'       => <<<EOT
select `year`, id, name, status, begin, "end", realBegan, realEnd,
    round((if(year(realEnd) is not null,
    datediff('day', realBegan, realEnd),
    datediff('day', realBegan, current_date())) - datediff('day', begin, "end")) / datediff('day', begin, "end") * 100) as duration
from (select distinct year(date) as year from zt_action) as t1
left join zt_project as t2 on 1=1
where deleted = '0' and t2.type = 'project'
and (year(realBegan) <= year and year(realBegan) is not null)
and (year(realEnd) >= year and year(realEnd) is not null)
and year("end") != '2059'
order by duration asc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => '项目名称', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'duration', 'name' => 'duration', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'      => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'number'),
        'id'        => array('name' => '编号', 'object' => 'action', 'field' => 'id', 'type' => 'number'),
        'name'      => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'    => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option'),
        'begin'     => array('name' => '计划开始', 'object' => 'project', 'field' => 'begin', 'type' => 'date'),
        'end'       => array('name' => '计划完成', 'object' => 'project', 'field' => 'end', 'type' => 'date'),
        'realBegan' => array('name' => '实际开始日期', 'object' => 'project', 'field' => 'realBegan', 'type' => 'date'),
        'realEnd'   => array('name' => '实际完成日期', 'object' => 'project', 'field' => 'realEnd', 'type' => 'date'),
        'duration'  => array('name' => 'duration', 'object' => 'project', 'field' => 'duration', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'      => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'        => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'name'      => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'status'    => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => ''),
        'begin'     => array('zh-cn' => '计划开始', 'zh-tw' => '', 'en' => 'begin', 'de' => '', 'fr' => ''),
        'end'       => array('zh-cn' => '计划完成', 'zh-tw' => '', 'en' => 'end', 'de' => '', 'fr' => ''),
        'realBegan' => array('zh-cn' => '实际开始日期', 'zh-tw' => '', 'en' => 'realBegan', 'de' => '', 'fr' => ''),
        'realEnd'   => array('zh-cn' => '实际完成日期', 'zh-tw' => '', 'en' => 'realEnd', 'de' => '', 'fr' => ''),
        'duration'  => array('zh-cn' => '工期偏差', 'zh-tw' => '', 'en' => 'duration', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1097,
    'name'      => '年度排行-项目-人员投入榜',
    'code'      => 'annualRank_projectPersonnel',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '41',
    'sql'       => <<<EOT
SELECT tt.join as `year`, count(1) as number, tt.name from (
select
t2.name, YEAR(t1.join) as `join`
from zt_team t1
RIGHT JOIN zt_project t2 on t2.id = t1.root
RIGHT JOIN zt_user t3 on t3.account = t1.account
WHERE t1.type = 'project'
AND t2.deleted = '0'
) tt
GROUP BY tt.`name`, tt.join
ORDER BY tt.join, number desc, tt.name
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => '项目名称', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'number', 'name' => 'number', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'   => array('name' => 'year', 'object' => 'user', 'field' => 'year', 'type' => 'number'),
        'number' => array('name' => 'number', 'object' => 'user', 'field' => 'number', 'type' => 'string'),
        'name'   => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'   => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'number' => array('zh-cn' => '人员个数', 'zh-tw' => '', 'en' => 'number', 'de' => '', 'fr' => ''),
        'name'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1098,
    'name'      => '年度排行-项目-工时消耗榜',
    'code'      => 'annualRank_projectConsumed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '41',
    'sql'       => <<<EOT
select year(t4.date) as year, t1.id, t1.name as project, round(sum(t4.consumed), 2) as consumed
from zt_project as t1
left join zt_project as t2 on t1.id = t2.parent and t2.deleted = '0' and t2.type in ('sprint', 'stage', 'kanban')
left join zt_task as t3 on t2.id = t3.execution and t3.deleted = '0' and t3.status != 'cancel'
left join zt_effort as t4 on t3.id = t4.objectid and t4.deleted = '0' and t4.objecttype = 'task'
where t1.deleted = '0'
and t1.type = 'project'
and t4.id is not null
group by year, t1.id, t1.project, t1.name
order by year, consumed desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'project', 'name' => 'project', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'consumed', 'name' => '总计消耗', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'effort', 'field' => 'year', 'type' => 'number'),
        'id'       => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'project'  => array('name' => 'project', 'object' => 'zt_project', 'field' => 'project', 'type' => 'string'),
        'consumed' => array('name' => '总计消耗', 'object' => 'task', 'field' => 'consumed', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'       => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'project'  => array('zh-cn' => '项目', 'zh-tw' => '', 'en' => 'project', 'de' => '', 'fr' => ''),
        'consumed' => array('zh-cn' => '任务总计消耗', 'zh-tw' => '', 'en' => 'consumed', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1099,
    'name'      => '年度排行-项目-完成需求条目榜',
    'code'      => 'annualRank_projectStoryCount_finished',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t1.closeddate) as year, t1.id, t1.project, count(1) as story
    from (
        select distinct t1.id, t1.name as project, t4.id as story, t4.closeddate
        from zt_project as t1
        left join zt_project as t2 on t1.id = t2.parent and t2.deleted = '0' and t2.type in ('sprint', 'stage', 'kanban')
        left join zt_projectstory as t3 on t2.id = t3.project
        left join zt_story as t4 on t3.story = t4.id and t4.deleted = '0' and t4.closedreason = 'done'
        where t1.deleted = '0' and t1.type = 'project' and t4.id is not null
    ) as t1
group by year, id, project
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'project', 'name' => '所属项目', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求列表', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'project' => array('name' => '所属项目', 'object' => 'project', 'field' => 'project', 'type' => 'string'),
        'story'   => array('name' => '研发需求列表', 'object' => 'projectstory', 'field' => 'story', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'project' => array('zh-cn' => '所属项目', 'zh-tw' => '', 'en' => 'project', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '研发需求计数', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1100,
    'name'      => '年度排行-项目-完成需求规模榜',
    'code'      => 'annualRank_projectStoryEstimate_finished',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '43',
    'sql'       => <<<EOT
select year(t1.closeddate) as year, t1.id, t1.project, round(sum(t1.estimate), 2) as story
    from (
        select distinct t1.id, t1.name as project, t4.id as story, t4.estimate, t4.closeddate
        from zt_project as t1
        left join zt_project as t2 on t1.id = t2.parent and t2.deleted = '0' and t2.type in ('sprint', 'stage', 'kanban')
        left join zt_projectstory as t3 on t2.id = t3.project
        left join zt_story as t4 on t3.story = t4.id and t4.deleted = '0' and t4.closedreason = 'done'
        where t1.deleted = '0' and t1.type = 'project' and t4.id is not null
    ) as t1
group by year, id, project
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'project', 'name' => '所属项目', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求列表', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'project' => array('name' => '所属项目', 'object' => 'project', 'field' => 'project', 'type' => 'string'),
        'story'   => array('name' => '研发需求列表', 'object' => 'projectstory', 'field' => 'story', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'project' => array('zh-cn' => '所属项目', 'zh-tw' => '', 'en' => 'project', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '需求预计工时', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1101,
    'name'      => '年度排行-产品-新增需求条目榜',
    'code'      => 'annualRank_productStoryCount_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t2.openeddate) as year, t1.id, t1.name as product, count(1) as story
from zt_product as t1
left join zt_story as t2 on t1.id = t2.product and t2.deleted = '0'
where t1.deleted = '0' and t1.shadow = '0' and t1.vision = 'rnd' and t2.id is not null
group by year, t1.id, product, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => 'product', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_product', 'field' => 'id', 'type' => 'number'),
        'product' => array('name' => 'product', 'object' => 'zt_product', 'field' => 'product', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '产品编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'product' => array('zh-cn' => '产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '研发需求计数', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1102,
    'name'      => '年度排行-产品-完成需求规模榜',
    'code'      => 'annualRank_productStoryEstimate_finished',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '36',
    'sql'       => <<<EOT
select year(t2.closeddate) as year, t1.id, t1.name as product, round(sum(t2.estimate), 1) as story
from zt_product as t1
left join zt_story as t2 on t1.id = t2.product and t2.deleted = '0' and t2.closedreason = 'done'
where t1.deleted = '0' and t1.shadow = '0' and t1.vision = 'rnd' and t2.id is not null
group by year, t1.id, product, t1.name
order by year, story desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => 'product', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'story', 'name' => '研发需求', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_product', 'field' => 'id', 'type' => 'number'),
        'product' => array('name' => 'product', 'object' => 'zt_product', 'field' => 'product', 'type' => 'string'),
        'story'   => array('name' => '研发需求', 'object' => 'story', 'field' => 'story', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '产品编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'product' => array('zh-cn' => '产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'story'   => array('zh-cn' => '研发需求预计工时求和', 'zh-tw' => '', 'en' => 'story', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1103,
    'name'      => '年度排行-产品-新增Bug条目榜',
    'code'      => 'annualRank_productBug_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '44',
    'sql'       => <<<EOT
select year(t2.openeddate) as year, t1.id, t1.name as product, count(1) as bug
from zt_product as t1
left join zt_bug as t2 on t1.id = t2.product and t2.deleted = '0'
where t1.deleted = '0' and t1.shadow = '0' and t1.vision = 'rnd' and t2.id is not null
group by year, t1.id, product, t1.name
order by year, bug desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => 'product', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'bug', 'name' => 'bug', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_product', 'field' => 'id', 'type' => 'number'),
        'product' => array('name' => 'product', 'object' => 'zt_product', 'field' => 'product', 'type' => 'string'),
        'bug'     => array('name' => 'bug', 'object' => 'bug', 'field' => 'bug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '产品编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'product' => array('zh-cn' => '产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'bug'     => array('zh-cn' => 'Bug计数', 'zh-tw' => '', 'en' => 'bug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1104,
    'name'      => '年度排行-产品-修复Bug条目榜',
    'code'      => 'annualRank_productBug_fixed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '44',
    'sql'       => <<<EOT
select year(t2.closeddate) as year, t1.id, t1.name as product, count(1) as bug
from zt_product as t1
left join zt_bug as t2 on t1.id = t2.product and t2.deleted = '0' and t2.resolution = 'fixed' and t2.status = 'closed'
where t1.deleted = '0'
and t1.shadow = '0'
and t1.vision = 'rnd'
and t2.id is not null
group by year, t1.id, product, t1.name
order by year, bug desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'product', 'name' => 'product', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'bug', 'name' => 'bug', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'    => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'id'      => array('name' => 'id', 'object' => 'zt_product', 'field' => 'id', 'type' => 'number'),
        'product' => array('name' => 'product', 'object' => 'zt_product', 'field' => 'product', 'type' => 'string'),
        'bug'     => array('name' => 'bug', 'object' => 'bug', 'field' => 'bug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'    => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'id'      => array('zh-cn' => '产品编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'product' => array('zh-cn' => '产品', 'zh-tw' => '', 'en' => 'product', 'de' => '', 'fr' => ''),
        'bug'     => array('zh-cn' => 'Bug计数', 'zh-tw' => '', 'en' => 'bug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1105,
    'name'      => '年度排行-个人-创建需求条目榜',
    'code'      => 'annualRank_personalStoryCount_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t3.openeddate) as year, t2.realname, count(1) as count
from zt_action as t1
right join zt_user as t2 on t1.actor = t2.account
left join zt_story as t3 on t1.objectid = t3.id
where t1.objecttype = 'story' and t1.action = 'opened' and t3.deleted = '0'
group by year, t2.account, t2.realname
order by year, count desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_user', 'field' => 'realname', 'type' => 'string'),
        'count'    => array('name' => 'count', 'object' => 'story', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'count'    => array('zh-cn' => '计数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1106,
    'name'      => '年度排行-个人-创建用例条目榜',
    'code'      => 'annualRank_personalCaseCount_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t3.openeddate) as year, t2.realname, count(1) as count
from zt_action as t1
right join zt_user as t2 on t1.actor = t2.account
left join zt_case as t3 on t1.objectid = t3.id
where t1.objecttype = 'case'
and t1.action = 'opened'
and t3.deleted = '0'
group by year, t2.account, t2.realname
order by year, count desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'testcase', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_user', 'field' => 'realname', 'type' => 'string'),
        'count'    => array('name' => 'count', 'object' => 'testcase', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'count'    => array('zh-cn' => '计数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1107,
    'name'      => '年度排行-个人-创建Bug条目榜',
    'code'      => 'annualRank_personalBug_created',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t3.openeddate) as year, t2.realname, count(1) as count
from zt_action as t1
right join zt_user as t2 on t1.actor = t2.account
left join zt_bug as t3 on t1.objectid = t3.id
where t1.objecttype = 'bug'
and t1.action = 'opened'
and t3.deleted = '0'
group by year, t2.account, t2.realname
order by year, count desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_user', 'field' => 'realname', 'type' => 'string'),
        'count'    => array('name' => 'count', 'object' => 'bug', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'count'    => array('zh-cn' => '计数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1108,
    'name'      => '年度排行-个人-修复Bug条目榜',
    'code'      => 'annualRank_personalBug_Fixed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t3.openeddate) as year, t2.realname, count(distinct t3.id) as count
from zt_action as t1
right join zt_user as t2 on t1.actor = t2.account
left join zt_bug as t3 on t1.objectid = t3.id
where t1.objecttype = 'bug' and t1.action = 'resolved' and t3.deleted = '0'
group by year, t2.account, t2.realname
order by year, count desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_user', 'field' => 'realname', 'type' => 'string'),
        'count'    => array('name' => 'count', 'object' => 'bug', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'count'    => array('zh-cn' => '计数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1109,
    'name'      => '年度排行-个人-工时消耗榜',
    'code'      => 'annualRank_personalConsumed',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t1.date) as year, t2.realname, round(sum(t1.consumed), 1) as consumed
from zt_effort as t1
left join zt_user as t2 on t1.account = t2.account
where t1.deleted = '0'
and t2.deleted = '0'
and year(t1.date) is not null
group by year, realname
order by year, consumed desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'consumed', 'name' => '耗时', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'user', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_user', 'field' => 'realname', 'type' => 'string'),
        'consumed' => array('name' => '耗时', 'object' => 'effort', 'field' => 'consumed', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'consumed' => array('zh-cn' => '耗时', 'zh-tw' => '', 'en' => 'consumed', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 1110,
    'name'      => '年度排行-个人-禅道操作次数榜',
    'code'      => 'annualRank_personalAction',
    'driver'    => 'duckdb',
    'dimension' => '1',
    'type'      => 'cluBarY',
    'group'     => '56',
    'sql'       => <<<EOT
select year(t1.date) as year, ifnull(t2.realname, t1.actor) as realname, count(1) as count
from zt_action t1
left join zt_user as t2 on t1.actor = t2.account
where t1.actor is not null
and year(t1.date) is not null
and t1.actor not in('', 'system')
group by year, t1.actor, t2.realname
order by year, `count` desc
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'realname', 'name' => 'realname', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'     => array('name' => 'year', 'object' => 'user', 'field' => 'year', 'type' => 'number'),
        'realname' => array('name' => 'realname', 'object' => 'zt_action', 'field' => 'realname', 'type' => 'string'),
        'count'    => array('name' => 'count', 'object' => 'user', 'field' => 'count', 'type' => 'string')
    ),
    'langs'     => array
    (
        'year'     => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => ''),
        'realname' => array('zh-cn' => '姓名', 'zh-tw' => '', 'en' => 'realname', 'de' => '', 'fr' => ''),
        'count'    => array('zh-cn' => '计数', 'zh-tw' => '', 'en' => 'count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10000,
    'name'      => '年度完成项目-完成项目数',
    'code'      => 'annualFinishedProject_countProject',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM zt_project WHERE type='project' AND status='closed' AND deleted='0' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10001,
    'name'      => '年度完成项目-按时完成项目数',
    'code'      => 'annualFinishedProject_countProject_finished_ontime',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) as 'year' FROM (SELECT id, begin, end, IF(left(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted='0' AND type='project' AND status='closed') t1 WHERE t1.realEnd<=end GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10002,
    'name'      => '年度完成项目-延期完成项目数',
    'code'      => 'annualFinishedProject_countProject_finished_delay',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM (SELECT id, begin, end, IF(left(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted='0' AND type='project' AND status='closed') t1 WHERE t1.realEnd>end GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10003,
    'name'      => '年度完成项目-完成需求条目数',
    'code'      => 'annualFinishedProject_storyCount_finished',
    'driver'    => 'duckdb',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '75',
    'sql'       => <<<EOT
select year(t2.date) as year, month(t2.date) as month, day(t2.date) as day, count(distinct t1.account) as count
from zt_user as t1
left join zt_action as t2 on t1.account = t2.actor
where t2.objecttype = 'user' and t2.action = 'login'
group by year(t2.date), month(t2.date), day(t2.date)
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10004,
    'name'      => '年度完成项目-完成需求规模数',
    'code'      => 'annualFinishedProject_storyEstimate_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '75',
    'sql'       => <<<EOT
SELECT ROUND(SUM(estimate),2) AS number,YEAR(`closedDate`) AS 'year' FROM zt_story WHERE deleted='0' AND status='closed' AND closedReason='done'  GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10005,
    'name'      => '年度完成项目-完成发布数',
    'code'      => 'annualFinishedProject_release_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '74',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`date`) AS 'year' FROM zt_release WHERE deleted='0' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10006,
    'name'      => '年度完成项目-解决bug数',
    'code'      => 'annualFinishedProject_bug_fixed',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '77',
    'sql'       => <<<EOT
SELECT SUM(CASE WHEN resolution='fixed' THEN 1 ELSE 0 END) AS number,YEAR(`resolvedDate`) AS 'year' FROM zt_bug WHERE deleted='0' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10007,
    'name'      => '年度完成项目-完成执行数',
    'code'      => 'annualFinishedProject_execution_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM zt_project WHERE type='sprint' AND status='closed' AND deleted='0' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10008,
    'name'      => '年度完成项目-按时完成执行数',
    'code'      => 'annualFinishedProject_execution_finished_ontime',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM (SELECT id, begin, end, IF(LEFT(realEnd,4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd,closedDate FROM zt_project WHERE deleted='0' AND type='sprint' AND status='closed') t1 WHERE t1.realEnd<=end GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10009,
    'name'      => '年度完成项目-延期完成执行数',
    'code'      => 'annualFinishedProject_execution_finished_delay',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM (SELECT id, begin, end, IF(LEFT(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' AND type='sprint' AND status='closed') t1 WHERE t1.realEnd>end GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10010,
    'name'      => '年度完成项目-完成任务条目数',
    'code'      => 'annualFinishedProject_taskCount_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '76',
    'sql'       => <<<EOT
SELECT COUNT(1) AS number,YEAR(`closedDate`) AS 'year' FROM zt_task WHERE deleted='0' AND status='closed' AND closedReason='done' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10011,
    'name'      => '年度完成项目-完成任务预计工时数',
    'code'      => 'annualFinishedProject_taskEstimate_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '78',
    'sql'       => <<<EOT
SELECT ROUND(SUM(estimate),2) AS number,YEAR(`closedDate`) AS 'year' FROM zt_task WHERE deleted='0' AND status='closed' AND closedReason='done' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10012,
    'name'      => '年度完成项目-完成任务消耗工时数',
    'code'      => 'annualFinishedProject_taskConsumed_finished',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '78',
    'sql'       => <<<EOT
SELECT ROUND(SUM(consumed),2) AS number,YEAR(`closedDate`) AS 'year' FROM zt_task WHERE deleted='0' AND status='closed' AND closedReason='done' GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10013,
    'name'      => '年度完成项目-投入的总人天',
    'code'      => 'annualFinishedProject_workingDayConsumed',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '78',
    'sql'       => <<<EOT
SELECT SUM(t2.people*DATEDIFF(t1.realEnd,t1.realBegan)) AS number,YEAR(`closedDate`) AS 'year' FROM (SELECT id, realBegan, IF(LEFT(realEnd, 4) = '0000', closedDate, realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' AND status='closed' AND type='project' AND realBegan != '0000-00-00') t1 LEFT JOIN (SELECT root, COUNT(id) people FROM zt_team WHERE type='project' GROUP BY `root`) t2 ON t1.id=t2.root GROUP BY `year`
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10014,
    'name'      => '年度完成项目-项目按期完成率',
    'code'      => 'annualFinishedProject_projectFinishedRatio',
    'dimension' => '2',
    'type'      => 'waterpolo',
    'group'     => '71',
    'sql'       => <<<EOT
SELECT t1.id,IF(t1.realEnd<=t1.end,'done','undone') AS 'projectstatus', YEAR(`closedDate`) AS 'year' FROM(SELECT id, begin, end, IF(LEFT(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' AND type='project' AND status='closed') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'projectstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'            => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectstatus' => array('name' => 'projectstatus', 'object' => 'project', 'field' => 'projectstatus', 'type' => 'string'),
        'year'          => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'projectstatus' => array('zh-cn' => '项目状态', 'zh-tw' => '', 'en' => 'projectstatus', 'de' => '', 'fr' => ''),
        'year'          => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10015,
    'name'      => '年度完成项目-执行按期完成率',
    'code'      => 'annualFinishedProject_executionFinishedRatio',
    'dimension' => '2',
    'type'      => 'waterpolo',
    'group'     => '71',
    'sql'       => <<<EOT
SELECT t1.id,IF(t1.realEnd<=t1.end,'done','undone') AS 'projectstatus', YEAR(`closedDate`) AS 'year' FROM (SELECT id, begin, end, IF(LEFT(realEnd,4)='0000',LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' and type='sprint' and status='closed') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'projectstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'            => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectstatus' => array('name' => 'projectstatus', 'object' => 'project', 'field' => 'projectstatus', 'type' => 'string'),
        'year'          => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'projectstatus' => array('zh-cn' => '执行状态', 'zh-tw' => '', 'en' => 'projectstatus', 'de' => '', 'fr' => ''),
        'year'          => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10016,
    'name'      => '年度完成项目-项目延期率',
    'code'      => 'annualFinishedProject_projectDelayRatio',
    'dimension' => '2',
    'type'      => 'waterpolo',
    'group'     => '71',
    'sql'       => <<<EOT
SELECT t1.id,IF(t1.realEnd>t1.end ,'done','undone') AS 'projectstatus', YEAR(`closedDate`) AS 'year' FROM (SELECT id, begin, end, IF(LEFT(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' AND type='project' AND status='closed') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'projectstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'            => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectstatus' => array('name' => 'projectstatus', 'object' => 'project', 'field' => 'projectstatus', 'type' => 'string'),
        'year'          => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'projectstatus' => array('zh-cn' => '项目状态', 'zh-tw' => '', 'en' => 'projectstatus', 'de' => '', 'fr' => ''),
        'year'          => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10017,
    'name'      => '年度完成项目-执行延期率',
    'code'      => 'annualFinishedProject_executionDelayRatio',
    'dimension' => '2',
    'type'      => 'waterpolo',
    'group'     => '71',
    'sql'       => <<<EOT
SELECT t1.id,IF(t1.realEnd>t1.end,'done','undone') AS 'projectstatus', YEAR(`closedDate`) AS 'year' FROM(SELECT id, begin, end, IF(LEFT(realEnd, 4) = '0000', LEFT(closedDate,10), realEnd) AS realEnd, closedDate FROM zt_project WHERE deleted='0' and type='sprint' and status='closed') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'count',
            'goal'       => 'id',
            'conditions' => array
            (
                array('field' => 'projectstatus', 'condition' => 'eq', 'value' => 'done')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'            => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectstatus' => array('name' => 'projectstatus', 'object' => 'project', 'field' => 'projectstatus', 'type' => 'string'),
        'year'          => array('name' => 'year', 'object' => 'project', 'field' => 'year', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'projectstatus' => array('zh-cn' => '执行状态', 'zh-tw' => '', 'en' => 'projectstatus', 'de' => '', 'fr' => ''),
        'year'          => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'year', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10018,
    'name'      => '年度完成项目-完成项目工期偏差条形图',
    'code'      => 'annualFinishedProject_projectFinishedDurationDeviation',
    'dimension' => '2',
    'type'      => 'cluBarY',
    'group'     => '71',
    'sql'       => <<<EOT
select
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
ifnull(if(left(realEnd,4) != '0000',datediff(`realEnd`,`realBegan`),datediff(`closedDate`,`realBegan`)),0) realduration
from
zt_project
where deleted='0'
and status='closed'
and type='project'
) t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => '项目名称', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'daterate', 'name' => 'daterate', 'valOrAgg' => 'max')
            )
        )
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
        'name'       => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'closedDate' => array('name' => '关闭日期', 'object' => 'project', 'field' => 'closedDate', 'type' => 'date'),
        'daterate'   => array('name' => 'daterate', 'object' => 'project', 'field' => 'daterate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'name'       => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'closedDate' => array('zh-cn' => '关闭日期', 'zh-tw' => '', 'en' => 'closedDate', 'de' => '', 'fr' => ''),
        'daterate'   => array('zh-cn' => '工期偏差率', 'zh-tw' => '', 'en' => 'daterate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10019,
    'name'      => '年度完成项目-单位工时交付需求规模数对比图',
    'code'      => 'annualFinishedProject_storyEstimatePerHour',
    'dimension' => '2',
    'type'      => 'pie',
    'group'     => '71',
    'sql'       => <<<EOT
select tt.*,
tt.`故事点` / tt.`工时` as "单位时间交付需求规模数"
from (
select
t1.name as project,
(
	select round(sum(t3.estimate), 1) from zt_projectstory t2
	left join zt_story t3 on t3.id= t2.story and t3.status='closed' and t3.closedReason = 'done'
	where t2.project = t1.id
) as "故事点",
(
	select round(sum(t5.consumed), 1) from zt_project t4
	left join zt_task t5 on t5.execution = t4.id and t5.deleted = '0' and t5.parent in (0, -1)
  where t4.project = t1.id and t4.type = 'sprint'
) as "工时"
from zt_project t1
where t1.status = 'closed'
and t1.deleted = '0'
and t1.type = 'project'
group by t1.id) tt
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarX',
            'xaxis' => array
            (
                array('field' => 'project', 'name' => '所属项目', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => '单位时间交付需求规模数', 'name' => '单位时间交付需求规模数', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'project', 'type' => 'input', 'name' => '所属项目', 'default' => '')
    ),
    'fields'    => array
    (
        'project'                           => array('name' => '所属项目', 'object' => 'project', 'field' => 'project', 'type' => 'string'),
        '故事点'                         => array('name' => '故事点', 'object' => 'project', 'field' => '故事点', 'type' => 'number'),
        '工时'                            => array('name' => '工时', 'object' => 'project', 'field' => '工时', 'type' => 'number'),
        '单位时间交付需求规模数' => array('name' => '单位时间交付需求规模数', 'object' => 'project', 'field' => '单位时间交付需求规模数', 'type' => 'number')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10020,
    'name'      => '年度完成项目-项目完成分布图',
    'code'      => 'annualFinishedProject_projectStatus_finished',
    'dimension' => '2',
    'type'      => 'pie',
    'group'     => '71',
    'sql'       => <<<EOT
select
t1.id,
(case when t1.realEnd<t1.end then "提前完成项目" when t1.realEnd=t1.end then "正常完成项目" else "延期完成项目" end) "completeStatus",
t1.closedDate
from(
select
id,
closedDate,
end,
if(left(realEnd, 4) = '0000', closedDate, realEnd) as realEnd
from
zt_project
where deleted='0'
and status='closed'
and type='project') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'completeStatus', 'name' => 'completeStatus', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => '项目ID', 'valOrAgg' => 'count')
            )
        )
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
        'id'             => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'completeStatus' => array('name' => 'completeStatus', 'object' => 'project', 'field' => 'completeStatus', 'type' => 'string'),
        'closedDate'     => array('name' => '关闭日期', 'object' => 'project', 'field' => 'closedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'             => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'completeStatus' => array('zh-cn' => '项目完成情况', 'zh-tw' => '', 'en' => 'completeStatus', 'de' => '', 'fr' => ''),
        'closedDate'     => array('zh-cn' => '关闭日期', 'zh-tw' => '', 'en' => 'closedDate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10021,
    'name'      => '年度完成项目-执行完成分布图',
    'code'      => 'annualFinishedProject_executionStatus_finished',
    'dimension' => '2',
    'type'      => 'pie',
    'group'     => '71',
    'sql'       => <<<EOT
select
t1.id,
(case when t1.realEnd<t1.end then "提前完成执行" when t1.realEnd=t1.end then "正常完成执行" else "延期完成执行" end) "completeStatus",
t1.closedDate
from(
select
id,
closedDate,
end,
if(left(realEnd, 4) = '0000', closedDate, realEnd) as realEnd
from
zt_project
where deleted='0'
and status='closed'
and type='sprint') t1
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'completeStatus', 'name' => 'completeStatus', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => '项目ID', 'valOrAgg' => 'count')
            )
        )
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
        'id'             => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'completeStatus' => array('name' => 'completeStatus', 'object' => 'project', 'field' => 'completeStatus', 'type' => 'string'),
        'closedDate'     => array('name' => '关闭日期', 'object' => 'project', 'field' => 'closedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'             => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'completeStatus' => array('zh-cn' => '完成情况', 'zh-tw' => '', 'en' => 'completeStatus', 'de' => '', 'fr' => ''),
        'closedDate'     => array('zh-cn' => '关闭日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10022,
    'name'      => '年度完成项目-完成项目工时偏差条形图',
    'code'      => 'annualFinishedProject_projectHourDeviation_finished',
    'dimension' => '2',
    'type'      => 'cluBarY',
    'group'     => '70',
    'sql'       => <<<EOT
select
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
where t1.deleted='0'
and t1.status='closed'
and t1.type='project') tt
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => '任务名称', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'rate', 'name' => 'rate', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'closedDate',
            'type'    => 'date',
            'name'    => '关闭时间',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'name'       => array('name' => '任务名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'id'         => array('name' => '编号', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'closedDate' => array('name' => '关闭时间', 'object' => 'task', 'field' => 'closedDate', 'type' => 'date'),
        'estimate'   => array('name' => '最初预计', 'object' => 'task', 'field' => 'estimate', 'type' => 'string'),
        'consumed'   => array('name' => '总计消耗', 'object' => 'task', 'field' => 'consumed', 'type' => 'string'),
        'left'       => array('name' => '预计剩余', 'object' => 'task', 'field' => 'left', 'type' => 'string'),
        'deviation'  => array('name' => 'deviation', 'object' => 'task', 'field' => 'deviation', 'type' => 'number'),
        'rate'       => array('name' => 'rate', 'object' => 'task', 'field' => 'rate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'name'       => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'id'         => array('zh-cn' => '项目编号', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'closedDate' => array('zh-cn' => '关闭时间', 'zh-tw' => '', 'en' => 'closedDate', 'de' => '', 'fr' => ''),
        'estimate'   => array('zh-cn' => '最初预计', 'zh-tw' => '', 'en' => 'estimate', 'de' => '', 'fr' => ''),
        'consumed'   => array('zh-cn' => '总计消耗', 'zh-tw' => '', 'en' => 'consumed', 'de' => '', 'fr' => ''),
        'left'       => array('zh-cn' => '预计剩余', 'zh-tw' => '', 'en' => 'left', 'de' => '', 'fr' => ''),
        'deviation'  => array('zh-cn' => '偏差', 'zh-tw' => '', 'en' => 'deviation', 'de' => '', 'fr' => ''),
        'rate'       => array('zh-cn' => '偏差比率', 'zh-tw' => '', 'en' => 'rate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10101,
    'name'      => '年度进行中项目-进行中的项目数',
    'code'      => 'annualDoingProject_countProject',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT id FROM zt_project WHERE deleted = '0' AND status = 'doing' AND type = 'project'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10102,
    'name'      => '年度进行中项目-进行中的迭代数',
    'code'      => 'annualDoingProject_countExecution',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT id,type FROM zt_project WHERE deleted = '0' AND status = 'doing' AND type IN ('sprint', 'stage', 'kanban') AND multiple = '1'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10103,
    'name'      => '年度进行中项目-进展顺利项目数',
    'code'      => 'annualDoingProject_countProject_good',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT t1.id, t1.name, IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss,LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = '0' AND t21.type IN ('sprint', 'kanban')
    AND t22.deleted = '0' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = '0' AND t21.type = 'stage'
        AND t22.deleted = '0' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project
WHERE t1.deleted = '0'
AND t1.status = 'doing'
AND t1.type = 'project'
AND ((IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != '2059' AND DATEDIFF(`end`, NOW()) >= 0) OR LEFT(t1.`end`, 4) = '2059' )
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10104,
    'name'      => '年度进行中项目-进展顺利迭代数',
    'code'      => 'annualDoingProject_countExecution_good',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT id, prograss, planPrograss, `end`
FROM (
SELECT t1.id,ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,t1.`end`,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
AND t2.deleted = '0' AND t2.parent < 1
GROUP BY t1.id
) AS t
WHERE prograss >= planPrograss AND DATEDIFF(`end`, NOW()) >= 0
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10105,
    'name'      => '年度进行中项目-进度滞后项目数',
    'code'      => 'annualDoingProject_countProject_bad',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT t1.id, t1.name, IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss
, LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = '0' AND t21.type IN ('sprint', 'kanban')
    AND t22.deleted = '0' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = '0' AND t21.type = 'stage'
        AND t22.deleted = '0' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project
WHERE t1.deleted = '0'
AND t1.status = 'doing'
AND t1.type = 'project'
AND LEFT(t1.`end`, 4) != '2059'
AND IFNULL(prograss, 0) < (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100)  AND DATEDIFF(`end`, NOW()) >= 0
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10106,
    'name'      => '年度进行中项目-进度滞后迭代数',
    'code'      => 'annualDoingProject_countExecution_bad',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT id, prograss, planPrograss
FROM (
SELECT t1.id,ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1' AND DATEDIFF(t1.`end`, NOW()) >= 0
AND t2.deleted = '0' AND t2.parent < 1
GROUP BY t1.id
) AS t
WHERE prograss < planPrograss
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10107,
    'name'      => '年度进行中项目-已延期项目数',
    'code'      => 'annualDoingProject_countPorject_delay',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '72',
    'sql'       => <<<EOT
SELECT id, name FROM zt_project WHERE deleted = '0' AND status = 'doing' AND type = 'project' AND LEFT(`end`, 4) != '2059' AND DATEDIFF(`end`, NOW()) < 0
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10108,
    'name'      => '年度进行中项目-已延期迭代数',
    'code'      => 'annualDoingProject_countExecution_delay',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '73',
    'sql'       => <<<EOT
SELECT id, name FROM zt_project WHERE deleted = '0' AND status = 'doing' AND type IN ('sprint', 'stage', 'kanban') AND DATEDIFF(`end`, NOW()) < 0 AND multiple = '1'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10109,
    'name'      => '年度进行中项目-未完成需求条目数',
    'code'      => 'annualDoingProject_storyCount_undone',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '75',
    'sql'       => <<<EOT
SELECT DISTINCT t3.id, t3.estimate
FROM zt_project AS t1
LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
LEFT JOIN zt_story AS t3 ON t2.story = t3.id
WHERE t1.deleted = '0' AND t1.status = 'doing' AND t1.type = 'project'
AND t3.deleted = '0' AND t3.stage NOT IN ('verified', 'released', 'closed')
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10110,
    'name'      => '年度进行中项目-未完成任务数',
    'code'      => 'annualDoingProject_countTask_undone',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '76',
    'sql'       => <<<EOT
SELECT DISTINCT t2.id
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = '0' AND t1.status = 'doing' AND t1.type IN ('sprint', 'stage', 'kanban')
AND t2.deleted = '0' AND t2.status IN ('wait', 'doing', 'pause') AND t2.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10111,
    'name'      => '年度进行中项目-未完成需求规模数',
    'code'      => 'annualDoingProject_storyEstimate_undone',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '75',
    'sql'       => <<<EOT
SELECT DISTINCT t3.id, t3.estimate
FROM zt_project AS t1
LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
LEFT JOIN zt_story AS t3 ON t2.story = t3.id
WHERE t1.deleted = '0' AND t1.status = 'doing' AND t1.type = 'project'
AND t3.deleted = '0' AND t3.stage NOT IN ('verified', 'released', 'closed')
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'estimate', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10112,
    'name'      => '年度进行中项目-剩余工时数',
    'code'      => 'annualDoingProject_leftEffort',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '78',
    'sql'       => <<<EOT
SELECT t1.id, t1.name, `taskleft`
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)), 2) AS `taskleft`
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = '0' AND t21.type IN ('sprint', 'stage', 'kanban')
    AND t22.deleted = '0' AND t22.parent < 1
    GROUP BY t22.project
) AS t2 ON t1.id = t2.project
WHERE t1.deleted = '0'
AND t1.status = 'doing'
AND t1.type = 'project'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'taskleft', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10113,
    'name'      => '年度进行中项目-投入总人次',
    'code'      => 'annualDoingProject_investedPeople',
    'dimension' => '2',
    'type'      => 'card',
    'group'     => '78',
    'sql'       => <<<EOT
SELECT t1.id,t1.type,t1.account
FROM zt_team AS t1
LEFT JOIN zt_user AS t2 on t1.account = t2.account
WHERE t1.type = 'project' AND t2.deleted = '0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'id', 'agg' => 'count'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10114,
    'name'      => '年度进行中项目-项目进度分布图',
    'code'      => 'annualDoingProject_projectProgressChart',
    'dimension' => '2',
    'type'      => 'pie',
    'group'     => '69',
    'sql'       => <<<EOT
SELECT t1.id, t1.name,
IF(
    DATEDIFF(t1.`end`, NOW()) < 0,
    "延期",
    (IF(
        (IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != '2059')
        OR LEFT(t1.`end`, 4) = '2059' ,
        "顺利",
        "滞后"
    ))) AS 'status',
IFNULL(prograss, 0) AS prograss, ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2)  AS planPrograss,LEFT(t1.`end`, 4) AS endYear
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = '0' AND t21.type IN ('sprint', 'kanban')
    AND t22.deleted = '0' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = '0' AND t21.type = 'stage'
        AND t22.deleted = '0' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project
WHERE t1.deleted = '0'
AND t1.status = 'doing'
AND t1.type = 'project'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'status', 'name' => '状态', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => 'id', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'           => array('name' => 'id', 'object' => 'zt_project', 'field' => 'id', 'type' => 'number'),
        'name'         => array('name' => 'name', 'object' => 'zt_project', 'field' => 'name', 'type' => 'string'),
        'status'       => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option'),
        'prograss'     => array('name' => 'prograss', 'object' => 'task', 'field' => 'prograss', 'type' => 'number'),
        'planPrograss' => array('name' => 'planPrograss', 'object' => 'task', 'field' => 'planPrograss', 'type' => 'number'),
        'endYear'      => array('name' => 'endYear', 'object' => 'task', 'field' => 'endYear', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'           => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'name'         => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'status'       => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => ''),
        'prograss'     => array('zh-cn' => '项目进度', 'zh-tw' => '', 'en' => 'prograss', 'de' => '', 'fr' => ''),
        'planPrograss' => array('zh-cn' => '计划进度', 'zh-tw' => '', 'en' => 'planPrograss', 'de' => '', 'fr' => ''),
        'endYear'      => array('zh-cn' => '结束年份', 'zh-tw' => '', 'en' => 'endYear', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10115,
    'name'      => '年度进行中项目-迭代进度分布图',
    'code'      => 'annualDoingProject_executionProgressChart',
    'dimension' => '2',
    'type'      => 'pie',
    'group'     => '69',
    'sql'       => <<<EOT
SELECT id, name,IF(
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
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
AND ((t2.deleted = '0' AND t2.parent < 1) OR t2.id IS NULL)
GROUP BY t1.id
) AS t
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'status', 'name' => '状态', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => '项目ID', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'id'           => array('name' => '项目ID', 'object' => 'project', 'field' => 'id', 'type' => 'number'),
        'name'         => array('name' => '项目名称', 'object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'       => array('name' => '状态', 'object' => 'project', 'field' => 'status', 'type' => 'option'),
        'prograss'     => array('name' => 'prograss', 'object' => 'task', 'field' => 'prograss', 'type' => 'number'),
        'planPrograss' => array('name' => 'planPrograss', 'object' => 'task', 'field' => 'planPrograss', 'type' => 'number'),
        'end'          => array('name' => '计划完成', 'object' => 'project', 'field' => 'end', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'           => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => 'id', 'de' => '', 'fr' => ''),
        'name'         => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => 'name', 'de' => '', 'fr' => ''),
        'status'       => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => 'status', 'de' => '', 'fr' => ''),
        'prograss'     => array('zh-cn' => '项目进度', 'zh-tw' => '', 'en' => 'prograss', 'de' => '', 'fr' => ''),
        'planPrograss' => array('zh-cn' => '计划进度', 'zh-tw' => '', 'en' => 'planPrograss', 'de' => '', 'fr' => ''),
        'end'          => array('zh-cn' => '计划完成', 'zh-tw' => '', 'en' => 'end', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10116,
    'name'      => '年度进行中项目-项目进度透视表',
    'code'      => 'annualDoingProject_projectProgressPivot',
    'dimension' => '2',
    'type'      => 'table',
    'group'     => '84',
    'sql'       => <<<EOT
SELECT t1.id, t1.name, IFNULL(t3.name, '/') AS program,t1.`begin`, IF(YEAR(t1.`end`) = '2059', "长期", t1.`end`) AS `end`, IF(YEAR(t1.`end`) = '2059', "长期", DATEDIFF(t1.`end`, t1.`begin`) + 1) AS planDuration,
IF(LEFT(t1.realBegan, 4) = '0000', '/', t1.realBegan) AS realBegan, IF(YEAR(t1.`end`) = '2059', "长期", IF(DATEDIFF(t1.`end`, NOW()) >= 0, DATEDIFF(t1.`end`, NOW()) + 1, 0)) AS realDuration,
IF(
    DATEDIFF(t1.`end`, NOW()) < 0,
    "延期",
    (IF(
        (IFNULL(prograss, 0) >= (DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100) AND LEFT(t1.`end`, 4) != '2059')
        OR LEFT(t1.`end`, 4) = '2059' ,
        "顺利",
        "滞后"
    ))) AS 'status',
CONCAT(IFNULL(prograss, 0), '%') AS prograss
FROM zt_project AS t1
LEFT JOIN (
    SELECT t22.project,
    ROUND(IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))), 0) * 100, 2) AS prograss
    FROM zt_project AS t21
    LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
    WHERE t21.deleted = '0' AND t21.type IN ('sprint', 'kanban')
    AND t22.deleted = '0' AND t22.parent < 1
    GROUP BY t22.project
    UNION
    SELECT  t.project, ROUND(SUM(t.prograss * (t.percent / 100)), 2) as prograss
    FROM (
        SELECT t21.id,t21.percent, t22.project,
        IF(SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0)) > 0, ROUND(SUM(t22.consumed) / (SUM(t22.consumed) + SUM(IF(t22.status != 'closed' && t22.status != 'cancel', t22.`left`, 0))) * 1000 / 1000 * 100, 2), 0)  AS prograss
        FROM zt_project AS t21
        LEFT JOIN zt_task AS t22 ON t21.id = t22.execution
        WHERE t21.deleted = '0' AND t21.type = 'stage'
        AND t22.deleted = '0' AND t22.parent < 1
        AND t22.id IS NOT NULL
        GROUP BY t21.id, t21.percent, t22.project
    ) t
    GROUP BY t.project
) AS t2 ON t1.id = t2.project
LEFT JOIN zt_project AS t3 ON SUBSTR(t1.path, 2, POSITION(',' IN SUBSTR(t1.path, 2)) -1) = t3.id AND t3.type = 'program' AND t3.deleted = '0'
WHERE t1.deleted = '0'
AND t1.status = 'doing'
AND t1.type = 'project'
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'program', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'begin', 'valOrAgg' => 'value', 'name' => '计划开始日期'),
            array('field' => 'end', 'valOrAgg' => 'value', 'name' => '计划完成日期'),
            array('field' => 'planDuration', 'valOrAgg' => 'value', 'name' => '计划工期'),
            array('field' => 'realBegan', 'valOrAgg' => 'value', 'name' => '实际开始日期'),
            array('field' => 'realDuration', 'valOrAgg' => 'value', 'name' => '剩余工期天数'),
            array('field' => 'prograss', 'valOrAgg' => 'value', 'name' => '工期进度'),
            array('field' => 'status', 'valOrAgg' => 'value', 'name' => '进度状态')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10117,
    'name'      => '年度进行中项目-迭代进度透视表',
    'code'      => 'annualDoingProject_executionProgressPivot',
    'dimension' => '2',
    'type'      => 'table',
    'group'     => '84',
    'sql'       => <<<EOT
SELECT id, name,project,`begin`, `end`, planDuration, IF(LEFT(realBegan, 4) = '0000', '/', realBegan) as realBegan, realDuration, CONCAT(prograss, '%') as prograss,
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
SELECT t1.id,t1.name,t1.`begin`,t1.`end`,t1.`realBegan`,IFNULL(t3.name, '/') AS project,t3.id AS projectID,
DATEDIFF(t1.`end`, t1.`begin`) + 1 AS planDuration, IF(DATEDIFF(t1.`end`, NOW()) >= 0, DATEDIFF(t1.`end`, NOW()) + 1, 0) AS realDuration,
ROUND(DATEDIFF(NOW(), t1.`begin`) / DATEDIFF(t1.`end`, t1.`begin`) * 100, 2) AS planPrograss,
ROUND(IF(SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0)) > 0, SUM(t2.consumed) / (SUM(t2.consumed) + SUM(IF(t2.status != 'closed' && t2.status != 'cancel', t2.`left`, 0))), 0) * 100, 2) AS prograss
FROM zt_project AS t1
LEFT JOIN zt_task AS t2 ON t1.id = t2.execution
LEFT JOIN zt_project AS t3 on t1.project = t3.id AND t3.type = 'project' AND t3.deleted = '0'
WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
AND t2.deleted = '0' AND t2.parent < 1
GROUP BY t1.id
) AS t
ORDER BY projectID ASC, id ASC
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'project', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '迭代'),
            array('field' => 'begin', 'valOrAgg' => 'value', 'name' => '计划开始日期'),
            array('field' => 'end', 'valOrAgg' => 'value', 'name' => '计划完成日期'),
            array('field' => 'planDuration', 'valOrAgg' => 'value', 'name' => '计划工期'),
            array('field' => 'realBegan', 'valOrAgg' => 'value', 'name' => '实际开始日期'),
            array('field' => 'realDuration', 'valOrAgg' => 'value', 'name' => '剩余工期天数'),
            array('field' => 'prograss', 'valOrAgg' => 'value', 'name' => '工期进度'),
            array('field' => 'status', 'valOrAgg' => 'value', 'name' => '进度状态')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10118,
    'name'      => '年度进行中项目-项目剩余工作量透视表',
    'code'      => 'annualDoingProject_porjectLeftPivot',
    'dimension' => '2',
    'type'      => 'table',
    'group'     => '83',
    'sql'       => <<<EOT
SELECT t1.id, t1.NAME AS project, IFNULL( t2.NAME, '/') AS program, IFNULL( t3.story, 0 ) AS story, IFNULL( t3.estimate, 0 ) AS estimate, IFNULL( t4.execution, 0 ) AS execution, IFNULL( t5.workhour, 0 ) AS workhour FROM zt_project AS t1 LEFT JOIN zt_project AS t2 ON FIND_IN_SET( t2.id, t1.path ) AND t2.deleted = '0' AND t2.type = 'program' AND t2.grade = 1 LEFT JOIN( SELECT t1.parent AS project, COUNT( 1 ) AS story, ROUND( SUM( t1.estimate ), 1 ) AS estimate FROM ( SELECT DISTINCT t1.parent, t3.id, t3.estimate FROM zt_project AS t1 LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project LEFT JOIN zt_story AS t3 ON t2.story = t3.id AND t3.deleted = '0' AND t3.stage NOT IN ( 'verified', 'released', 'closed' ) WHERE t1.deleted = '0' AND t1.type IN ( 'sprint', 'stage', 'kanban' ) AND t3.id IS NOT NULL ) AS t1 GROUP BY project ) AS t3 ON t1.id = t3.project LEFT JOIN ( SELECT parent AS project, COUNT( 1 ) AS execution FROM zt_project WHERE deleted = '0' AND type IN ( 'sprint', 'stage', 'kanban' ) AND multiple = '1' AND STATUS NOT IN ( 'done', 'closed' ) GROUP BY project ) AS t4 ON t1.id = t4.project LEFT JOIN ( SELECT t1.parent AS project, ROUND( SUM( t2.LEFT ), 1 ) AS workhour FROM zt_project AS t1 LEFT JOIN zt_task AS t2 ON t1.id = t2.execution AND t2.deleted = '0' AND t2.parent < 1 WHERE t1.deleted = '0' AND t1.type IN ( 'sprint', 'stage', 'kanban' ) AND t1.STATUS NOT IN ( 'done', 'closed' ) AND t2.id IS NOT NULL GROUP BY project ) AS t5 ON t1.id = t5.project WHERE t1.deleted = '0' AND t1.type = 'project' AND t1.STATUS = 'doing'
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'program', 'valOrAgg' => 'value', 'name' => '一级项目集'),
            array('field' => 'project', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '剩余需求数'),
            array('field' => 'estimate', 'valOrAgg' => 'value', 'name' => '剩余需求规模数'),
            array('field' => 'execution', 'valOrAgg' => 'value', 'name' => '剩余执行数'),
            array('field' => 'workhour', 'valOrAgg' => 'value', 'name' => '剩余工时')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10119,
    'name'      => '年度进行中项目-迭代剩余工作量透视表',
    'code'      => 'annualDoingProject_executionLeftPivot',
    'dimension' => '2',
    'type'      => 'table',
    'group'     => '83',
    'sql'       => <<<EOT
SELECT
  t1.id,
  t1.name AS execution,
  IFNULL(t2.name, '/') AS project,
  IFNULL(t3.story, 0) AS story,
  IFNULL(t3.estimate, 0) AS estimate,
  IFNULL(t4.task, 0) AS task,
  IFNULL(t4.workhour, 0) AS workhour
FROM zt_project AS t1
LEFT JOIN zt_project AS t2 ON t1.project = t2.id AND t2.type = 'project'
LEFT JOIN (
  SELECT t1.id AS execution, COUNT(1) AS story, ROUND(SUM(t3.estimate), 1) AS estimate
  FROM zt_project AS t1
  LEFT JOIN zt_projectstory AS t2 ON t1.id = t2.project
  LEFT JOIN zt_story AS t3 ON t2.story = t3.id AND t3.deleted = '0' AND t3.stage NOT IN ('verified', 'released', 'closed')
  WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
  GROUP BY execution
) AS t3 ON t1.id = t3.execution
LEFT JOIN (
  SELECT t1.id AS execution, SUM(IF(t2.status IN ('wait', 'doing'), 1, 0)) AS task, ROUND(SUM(IF(t2.status IN ('wait', 'doing', 'pause'), t2.left, 0)), 1) AS workhour
  FROM zt_project AS t1
  LEFT JOIN zt_task AS t2 ON t1.id = t2.execution AND t2.deleted = '0' AND t2.parent < 1
  WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
  GROUP BY execution
) AS t4 ON t1.id = t4.execution
WHERE t1.deleted = '0' AND t1.type IN ('sprint', 'stage', 'kanban') AND t1.status = 'doing' AND t1.multiple = '1'
ORDER BY t2.id ASC, t1.id ASC
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'project', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'execution', 'valOrAgg' => 'value', 'name' => '迭代'),
            array('field' => 'story', 'valOrAgg' => 'value', 'name' => '剩余需求数'),
            array('field' => 'estimate', 'valOrAgg' => 'value', 'name' => '剩余需求规模数'),
            array('field' => 'task', 'valOrAgg' => 'value', 'name' => '剩余任务数'),
            array('field' => 'workhour', 'valOrAgg' => 'value', 'name' => '剩余工时')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10201,
    'name'      => '质量数据-研发完成需求数',
    'code'      => 'quality_storyCount_finished',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '93',
    'sql'       => <<<EOT
SELECT COUNT(id) AS number FROM zt_story WHERE deleted='0' AND (stage IN ('developed','testing','verfied','released') OR (status='closed' AND closedReason='done'))
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10202,
    'name'      => '质量数据-研发完成需求规模数',
    'code'      => 'quality_storyEstimate_finished',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '93',
    'sql'       => <<<EOT
SELECT ROUND(SUM(estimate),2) AS number FROM zt_story WHERE deleted='0' AND (stage IN ('developed','testing','verfied','released') OR (status='closed' AND closedReason='done'))
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10203,
    'name'      => '质量数据-研发完成需求用例数',
    'code'      => 'quality_storyCaseCount_finished',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '95',
    'sql'       => <<<EOT
SELECT SUM(t2.cases) AS number FROM (SELECT t1.story story, COUNT(t1.id) cases FROM (SELECT story,id FROM zt_case WHERE deleted='0') t1 GROUP BY t1.story) t2 LEFT JOIN (SELECT id,stage,status,closedReason,deleted FROM zt_story) t3 ON t2.story=t3.id WHERE t3.deleted='0' AND (t3.stage IN ('developed','testing','verfied','released') OR (t3.status='closed' AND t3.closedReason='done'))
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10204,
    'name'      => '质量数据-Bug总数',
    'code'      => 'quality_bugCount',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '94',
    'sql'       => <<<EOT
SELECT COUNT(id) AS number FROM zt_bug WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10205,
    'name'      => '质量数据-有效Bug数',
    'code'      => 'quality_bugCount_valid',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '94',
    'sql'       => <<<EOT
SELECT SUM(CASE WHEN resolution IN ('fixed','postponed') OR status='active' THEN 1 ELSE 0 END) AS number FROM zt_bug WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10206,
    'name'      => '质量数据-修复Bug数',
    'code'      => 'quality_bugCount_fixed',
    'dimension' => '3',
    'type'      => 'card',
    'group'     => '94',
    'sql'       => <<<EOT
SELECT SUM(CASE WHEN resolution='fixed' THEN 1 ELSE 0 END) AS number FROM zt_bug WHERE deleted='0'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'agg', 'field' => 'number', 'agg' => 'sum'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10207,
    'name'      => '质量数据-研发完成需求用例覆盖率',
    'code'      => 'quality_storyCaseCoverage_finished',
    'dimension' => '3',
    'type'      => 'waterpolo',
    'group'     => '92',
    'sql'       => <<<EOT
SELECT ROUND(SUM(t3.havecasefixstory)/COUNT(t3.fixstory),4) AS fixpercent, 'havecase' as havecase FROM (SELECT t2.storyid 'fixstory', (CASE WHEN t2.cases=0 THEN 0 ELSE 1 END) havecasefixstory FROM (SELECT t1.storyid, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted='0' AND (zt_story.stage IN ('developed','testing','verfied','released') OR (zt_story.status='closed' AND zt_story.closedReason='done'))) t1 GROUP BY t1.storyid ORDER BY cases DESC) t2) t3
union
SELECT ROUND(1-SUM(t3.havecasefixstory)/COUNT(t3.fixstory),4) AS fixpercent, 'nocase' as havecase FROM (SELECT t2.storyid 'fixstory', (CASE WHEN t2.cases=0 THEN 0 ELSE 1 END) havecasefixstory FROM (SELECT t1.storyid, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted='0' AND (zt_story.stage IN ('developed','testing','verfied','released') OR (zt_story.status='closed' AND zt_story.closedReason='done'))) t1 GROUP BY t1.storyid ORDER BY cases DESC) t2) t3
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'sum',
            'goal'       => 'fixpercent',
            'conditions' => array
            (
                array('field' => 'havecase', 'condition' => 'eq', 'value' => 'havecase')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'fixpercent' => array('name' => 'fixpercent', 'object' => 'testcase', 'field' => 'fixpercent', 'type' => 'number'),
        'havecase'   => array('name' => 'havecase', 'object' => 'testcase', 'field' => 'havecase', 'type' => 'string')
    ),
    'langs'     => array
    (
        'fixpercent' => array('zh-cn' => '用例覆盖率', 'zh-tw' => '', 'en' => 'fixpercent', 'de' => '', 'fr' => ''),
        'havecase'   => array('zh-cn' => '是否有用例', 'zh-tw' => '', 'en' => 'havecase', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10208,
    'name'      => '质量数据-研发完成需求用例密度',
    'code'      => 'quality_storyCaseDensity_finished',
    'dimension' => '3',
    'type'      => 'waterpolo',
    'group'     => '92',
    'sql'       => <<<EOT
SELECT ROUND(SUM(t2.cases)/SUM(t2.estimate),4) AS casedensity, 'havecase' as havecase FROM (SELECT t1.storyid, t1.estimate, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, zt_story.estimate, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted='0' AND (zt_story.stage IN ('developed','testing','verfied','released') OR (zt_story.status='closed' AND zt_story.closedReason='done'))) t1 GROUP BY t1.storyid, t1.estimate ORDER BY cases DESC) t2
union
SELECT ROUND(1-SUM(t2.cases)/SUM(t2.estimate),4) AS casedensity, 'nocase' as havecase FROM (SELECT t1.storyid, t1.estimate, SUM(t1.iscase) cases FROM (SELECT zt_story.id storyid, zt_story.estimate, (CASE WHEN zt_case.id is null THEN 0 ELSE 1 END) iscase FROM zt_story LEFT JOIN zt_case ON zt_story.id=zt_case.story WHERE zt_story.deleted='0' AND (zt_story.stage IN ('developed','testing','verfied','released') OR (zt_story.status='closed' AND zt_story.closedReason='done'))) t1 GROUP BY t1.storyid, t1.estimate ORDER BY cases DESC) t2
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'sum',
            'goal'       => 'casedensity',
            'conditions' => array
            (
                array('field' => 'havecase', 'condition' => 'eq', 'value' => 'havecase')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'casedensity' => array('name' => 'casedensity', 'object' => 'testcase', 'field' => 'casedensity', 'type' => 'number'),
        'havecase'    => array('name' => 'havecase', 'object' => 'testcase', 'field' => 'havecase', 'type' => 'string')
    ),
    'langs'     => array
    (
        'casedensity' => array('zh-cn' => '用例密度', 'zh-tw' => '', 'en' => 'casedensity', 'de' => '', 'fr' => ''),
        'havecase'    => array('zh-cn' => '是否有用例', 'zh-tw' => '', 'en' => 'havecase', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10209,
    'name'      => '质量数据-Bug密度',
    'code'      => 'quality_bugDensity',
    'dimension' => '3',
    'type'      => 'waterpolo',
    'group'     => '91',
    'sql'       => <<<EOT
SELECT ROUND(SUM(t3.bug)/SUM(t3.estimate), 4) AS bugdensity, 'havebug' as havebug FROM (SELECT t1.product product, IFNULL(t1.estimate,0) estimate, IFNULL(t2.bug,0) bug FROM (SELECT product, ROUND(SUM(estimate),2) estimate FROM zt_story WHERE deleted='0' AND (stage IN ('developed','testing','verfied','released') OR (status='closed' AND closedReason='done')) GROUP BY product) t1 LEFT JOIN (SELECT product, COUNT(id) bug FROM zt_bug WHERE deleted='0' GROUP BY product) t2 ON t1.product=t2.product) t3
union
SELECT ROUND(1-SUM(t3.bug)/SUM(t3.estimate), 4) AS bugdensity, 'nobug' as havebug FROM (SELECT t1.product product, IFNULL(t1.estimate,0) estimate, IFNULL(t2.bug,0) bug FROM (SELECT product, ROUND(SUM(estimate),2) estimate FROM zt_story WHERE deleted='0' AND (stage IN ('developed','testing','verfied','released') OR (status='closed' AND closedReason='done')) GROUP BY product) t1 LEFT JOIN (SELECT product, COUNT(id) bug FROM zt_bug WHERE deleted='0' GROUP BY product) t2 ON t1.product=t2.product) t3
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'sum',
            'goal'       => 'bugdensity',
            'conditions' => array
            (
                array('field' => 'havebug', 'condition' => 'eq', 'value' => 'havebug')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'bugdensity' => array('name' => 'bugdensity', 'object' => 'bug', 'field' => 'bugdensity', 'type' => 'number'),
        'havebug'    => array('name' => 'havebug', 'object' => 'bug', 'field' => 'havebug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'bugdensity' => array('zh-cn' => 'Bug密度', 'zh-tw' => '', 'en' => 'bugdensity', 'de' => '', 'fr' => ''),
        'havebug'    => array('zh-cn' => '是否有Bug', 'zh-tw' => '', 'en' => 'havebug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10210,
    'name'      => '质量数据-Bug修复率',
    'code'      => 'quality_bugFixedRatio',
    'dimension' => '3',
    'type'      => 'waterpolo',
    'group'     => '91',
    'sql'       => <<<EOT
SELECT ROUND(SUM(CASE WHEN resolution='fixed' THEN 1 ELSE 0 END)/COUNT(id),4) AS fixpercent, 'havebug' as havebug FROM zt_bug WHERE deleted = '0'
union
SELECT ROUND(1-SUM(CASE WHEN resolution='fixed' THEN 1 ELSE 0 END)/COUNT(id),4) AS fixpercent, 'nobug' as havebug FROM zt_bug WHERE deleted = '0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'       => 'waterpolo',
            'calc'       => 'sum',
            'goal'       => 'fixpercent',
            'conditions' => array
            (
                array('field' => 'havebug', 'condition' => 'eq', 'value' => 'havebug')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'fixpercent' => array('name' => 'fixpercent', 'object' => 'bug', 'field' => 'fixpercent', 'type' => 'number'),
        'havebug'    => array('name' => 'havebug', 'object' => 'bug', 'field' => 'havebug', 'type' => 'string')
    ),
    'langs'     => array
    (
        'fixpercent' => array('zh-cn' => 'Bug修复率', 'zh-tw' => '', 'en' => 'fixpercent', 'de' => '', 'fr' => ''),
        'havebug'    => array('zh-cn' => '是否有Bug', 'zh-tw' => '', 'en' => 'havebug', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10211,
    'name'      => '质量数据-Bug总数、有效Bug与解决Bug数近30天统计柱形图',
    'code'      => 'quality_bugDailyChart',
    'dimension' => '3',
    'type'      => 'cluBarX',
    'group'     => '91',
    'sql'       => <<<EOT
select
id "Bug总数",
(case when  resolution in ('fixed','postponed') or status='active' then 1 else 0 end) "有效Bug",
(case when  resolution='fixed' then 1 else 0 end) "已解决Bug",
openedDate "日期"
from zt_bug
where left(openedDate,10) > (select DATE_sub(MAX(NOW()), INTERVAL '30' DAY))
and left(openedDate,10) < NOW()
and deleted='0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarX',
            'xaxis' => array
            (
                array('field' => '日期', 'name' => '日期', 'group' => 'day')
            ),
            'yaxis' => array
            (
                array('field' => 'Bug总数', 'name' => 'Bug总数', 'valOrAgg' => 'count'),
                array('field' => '有效Bug', 'name' => '有效Bug', 'valOrAgg' => 'sum'),
                array('field' => '已解决Bug', 'name' => '已解决Bug', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array(),
    'fields'    => array
    (
        'Bug总数'    => array('name' => 'Bug总数', 'object' => 'bug', 'field' => 'Bug总数', 'type' => 'number'),
        '有效Bug'    => array('name' => '有效Bug', 'object' => 'bug', 'field' => '有效Bug', 'type' => 'string'),
        '已解决Bug' => array('name' => '已解决Bug', 'object' => 'bug', 'field' => '已解决Bug', 'type' => 'string'),
        '日期'       => array('name' => '日期', 'object' => 'bug', 'field' => '日期', 'type' => 'date')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10212,
    'name'      => '质量数据-有效Bug率年度趋势图',
    'code'      => 'quality_validBugTendency',
    'dimension' => '3',
    'type'      => 'line',
    'group'     => '91',
    'sql'       => <<<EOT
select
year,
count(a.id) as totalBugCount,
sum(a.effectivebug) as effectiveBugCount,
sum(a.effectivebug)/count(a.id) effectiveBugRate
from(
select
left(openedDate,4) year,
id,
(case when  resolution in ('fixed','postponed') or status='active' then 1 else 0 end) effectivebug,
(case when  resolution='fixed' then 1 else 0 end) fixedBug
from zt_bug
where zt_bug.deleted='0'
) a
group by a.year
order by  a.year
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'year', 'name' => 'year', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'effectiveBugRate', 'name' => 'effectiveBugRate', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'              => array('name' => 'year', 'object' => 'bug', 'field' => 'year', 'type' => 'string'),
        'totalBugCount'     => array('name' => 'totalBugCount', 'object' => 'bug', 'field' => 'totalBugCount', 'type' => 'string'),
        'effectiveBugCount' => array('name' => 'effectiveBugCount', 'object' => 'bug', 'field' => 'effectiveBugCount', 'type' => 'number'),
        'effectiveBugRate'  => array('name' => 'effectiveBugRate', 'object' => 'bug', 'field' => 'effectiveBugRate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'              => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'Year', 'de' => '', 'fr' => ''),
        'totalBugCount'     => array('zh-cn' => 'Bug总数', 'zh-tw' => '', 'en' => 'Total Bug Count', 'de' => '', 'fr' => ''),
        'effectiveBugCount' => array('zh-cn' => '有效Bug数', 'zh-tw' => '', 'en' => 'Effective Bug Count', 'de' => '', 'fr' => ''),
        'effectiveBugRate'  => array('zh-cn' => '有效Bug率', 'zh-tw' => '', 'en' => 'Effective Bug Rate', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10213,
    'name'      => '质量数据-Bug密度年度趋势图',
    'code'      => 'quality_bugDensityTendency',
    'dimension' => '3',
    'type'      => 'line',
    'group'     => '91',
    'sql'       => <<<EOT
select
bug.year as year,
createdBugs,
exfixedstoryestimate,
round(createdBugs/exfixedstoryestimate,2) as bugCount
from
(select
left(openedDate,4) year,
count(id) createdBugs
from zt_bug
where zt_bug.deleted='0'
group by year
) bug
left join
(select
sum(estimate) exfixedstoryestimate,
left(closedDate,4) year
from
zt_story
where zt_story.deleted='0' and zt_story.status='closed' and zt_story.closedReason='done'
group by year
) story
on story.year=bug.year
order by bug.year
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'line',
            'xaxis' => array
            (
                array('field' => 'year', 'name' => 'year', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'bugCount', 'name' => 'Bug数', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'year', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        'year'                 => array('name' => 'year', 'object' => 'story', 'field' => 'year', 'type' => 'string'),
        'createdBugs'          => array('name' => 'createdBugs', 'object' => 'story', 'field' => 'createdBugs', 'type' => 'string'),
        'exfixedstoryestimate' => array('name' => 'exfixedstoryestimate', 'object' => 'story', 'field' => 'exfixedstoryestimate', 'type' => 'number'),
        'bugCount'             => array('name' => 'Bug数', 'object' => 'story', 'field' => 'bugCount', 'type' => 'number')
    ),
    'langs'     => array
    (
        'year'                 => array('zh-cn' => '年份', 'zh-tw' => '', 'en' => 'Year', 'de' => '', 'fr' => ''),
        'createdBugs'          => array('zh-cn' => '产生Bug', 'zh-tw' => '', 'en' => 'Created Bug', 'de' => '', 'fr' => ''),
        'exfixedstoryestimate' => array('zh-cn' => '完成需求数', 'zh-tw' => '', 'en' => 'Finished Story', 'de' => '', 'fr' => ''),
        'bugCount'             => array('zh-cn' => '单位完成需求规模产生的Bug数', 'zh-tw' => '', 'en' => 'Bug Density', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10214,
    'name'      => '质量数据-Bug严重程度年度堆积柱状图',
    'code'      => 'quality_bugAnnualSeverityChart',
    'dimension' => '3',
    'type'      => 'stackedBar',
    'group'     => '91',
    'sql'       => <<<EOT
select
count(id) "所有Bug数",
sum(case when severity=1 then 1 else 0 end) "严重程度为1级的Bug",
sum(case when severity=2 then 1 else 0 end) "严重程度为2级的Bug",
sum(case when severity not in (1,2) then 1 else 0 end) "严重程度低于2级的Bug",
left(openedDate,4) "年份"
from
zt_bug
where deleted='0'
group by left(openedDate,4)
order by left(openedDate,4)
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'stackedBar',
            'xaxis' => array
            (
                array('field' => '年份', 'name' => '年份', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => '严重程度为1级的Bug', 'name' => '严重程度为1级的Bug', 'valOrAgg' => 'sum'),
                array('field' => '严重程度为2级的Bug', 'name' => '严重程度为2级的Bug', 'valOrAgg' => 'sum'),
                array('field' => '严重程度低于2级的Bug', 'name' => '严重程度低于2级的Bug', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => '年份', 'type' => 'select', 'name' => '年份')
    ),
    'fields'    => array
    (
        '所有Bug数'                 => array('name' => '所有Bug数', 'object' => 'bug', 'field' => '所有Bug数', 'type' => 'string'),
        '严重程度为1级的Bug'    => array('name' => '严重程度为1级的Bug', 'object' => 'bug', 'field' => '严重程度为1级的Bug', 'type' => 'number'),
        '严重程度为2级的Bug'    => array('name' => '严重程度为2级的Bug', 'object' => 'bug', 'field' => '严重程度为2级的Bug', 'type' => 'number'),
        '严重程度低于2级的Bug' => array('name' => '严重程度低于2级的Bug', 'object' => 'bug', 'field' => '严重程度低于2级的Bug', 'type' => 'number'),
        '年份'                       => array('name' => '年份', 'object' => 'bug', 'field' => '年份', 'type' => 'string')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10215,
    'name'      => '质量数据-产品用例数量统计条形图',
    'code'      => 'quality_productCaseCountChart',
    'dimension' => '3',
    'type'      => 'cluBarY',
    'group'     => '91',
    'sql'       => <<<EOT
select
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
where deleted='0'
group by product )
t2 on t1.id=t2.product
EOT,
    'settings'  => array
    (
        array
        (
            'type'  => 'cluBarY',
            'xaxis' => array
            (
                array('field' => 'name', 'name' => 'name', 'group' => '')
            ),
            'yaxis' => array
            (
                array('field' => 'count', 'name' => 'count', 'valOrAgg' => 'sum')
            )
        )
    ),
    'filters'   => array
    (
        array('field' => 'name', 'type' => 'select', 'name' => '产品')
    ),
    'fields'    => array
    (
        'name'  => array('name' => 'name', 'object' => 'product', 'field' => 'name', 'type' => 'string'),
        'count' => array('name' => 'count', 'object' => 'testcase', 'field' => 'id', 'type' => 'string')
    ),
    'langs'     => array
    (
        'name'  => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => 'Product', 'de' => '', 'fr' => ''),
        'count' => array('zh-cn' => '用例计数', 'zh-tw' => '', 'en' => 'Case Count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10216,
    'name'      => '质量数据-产品Bug数量统计条形图',
    'code'      => 'quality_productBugCountChart',
    'dimension' => '3',
    'type'      => 'cluBarY',
    'group'     => '91',
    'sql'       => <<<EOT
select
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
where zt_bug.deleted='0'
group by product )
t2 on t1.id=t2.product
EOT,
    'settings'  => array
    (
        array
        (
            'type'    => 'cluBarY',
            'xaxis'   => array
            (
                array('field' => 'name', 'name' => 'name', 'group' => '')
            ),
            'yaxis'   => array
            (
                array('field' => 'bug', 'name' => 'bug', 'valOrAgg' => 'sum')
            ),
            'rotateX' => 'notuse'
        )
    ),
    'filters'   => array
    (
        array('field' => 'name', 'type' => 'select', 'name' => '产品')
    ),
    'fields'    => array
    (
        'name' => array('name' => 'name', 'object' => 'product', 'field' => 'name', 'type' => 'string'),
        'bug'  => array('name' => 'bug', 'object' => 'bug', 'field' => 'id', 'type' => 'string')
    ),
    'langs'     => array
    (
        'name' => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => 'Product', 'de' => '', 'fr' => ''),
        'bug'  => array('zh-cn' => 'Bug计数', 'zh-tw' => '', 'en' => 'Bug Count', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10217,
    'name'      => '质量数据-Bug状态分布图',
    'code'      => 'quality_bugStatus',
    'dimension' => '3',
    'type'      => 'pie',
    'group'     => '91',
    'sql'       => <<<EOT
select
id,status,openedDate
from zt_bug
where deleted='0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'status', 'name' => 'Bug状态', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => 'Bug编号', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'openedDate',
            'type'    => 'date',
            'name'    => '创建日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'id'         => array('name' => 'Bug编号', 'object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'status'     => array('name' => 'Bug状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option'),
        'openedDate' => array('name' => '创建日期', 'object' => 'bug', 'field' => 'openedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'         => array('zh-cn' => 'Bug编号', 'zh-tw' => '', 'en' => 'Bug ID', 'de' => '', 'fr' => ''),
        'status'     => array('zh-cn' => 'Bug状态', 'zh-tw' => '', 'en' => 'Status', 'de' => '', 'fr' => ''),
        'openedDate' => array('zh-cn' => '创建日期', 'zh-tw' => '', 'en' => 'Opened Date', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10218,
    'name'      => '质量数据-Bug类型分布',
    'code'      => 'quality_bugType',
    'dimension' => '3',
    'type'      => 'pie',
    'group'     => '91',
    'sql'       => <<<EOT
select
id,type,openedDate
from
zt_bug
where deleted='0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'type', 'name' => 'Bug类型', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => 'Bug编号', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'openedDate',
            'type'    => 'date',
            'name'    => '创建日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'id'         => array('name' => 'Bug编号', 'object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'type'       => array('name' => 'Bug类型', 'object' => 'bug', 'field' => 'type', 'type' => 'option'),
        'openedDate' => array('name' => '创建日期', 'object' => 'bug', 'field' => 'openedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'         => array('zh-cn' => 'Bug编号', 'zh-tw' => '', 'en' => 'Bug ID', 'de' => '', 'fr' => ''),
        'type'       => array('zh-cn' => 'Bug类型', 'zh-tw' => '', 'en' => 'Type', 'de' => '', 'fr' => ''),
        'openedDate' => array('zh-cn' => '创建日期', 'zh-tw' => '', 'en' => 'Opened Date', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10219,
    'name'      => '质量数据-Bug严重程度分布',
    'code'      => 'quality_bugSeverity',
    'dimension' => '3',
    'type'      => 'pie',
    'group'     => '91',
    'sql'       => <<<EOT
select
id,severity,openedDate
from
zt_bug
where deleted='0'
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'severity', 'name' => '严重程度', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => 'Bug编号', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'openedDate',
            'type'    => 'date',
            'name'    => '创建日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'id'         => array('name' => 'Bug编号', 'object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'severity'   => array('name' => '严重程度', 'object' => 'bug', 'field' => 'severity', 'type' => 'option'),
        'openedDate' => array('name' => '创建日期', 'object' => 'bug', 'field' => 'openedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'         => array('zh-cn' => 'Bug编号', 'zh-tw' => '', 'en' => 'Bug ID', 'de' => '', 'fr' => ''),
        'severity'   => array('zh-cn' => '严重程度', 'zh-tw' => '', 'en' => 'Severity', 'de' => '', 'fr' => ''),
        'openedDate' => array('zh-cn' => '创建日期', 'zh-tw' => '', 'en' => 'Opened Date', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 10220,
    'name'      => '质量数据-Bug解决方案分布',
    'code'      => 'quality_bugResolution',
    'dimension' => '3',
    'type'      => 'pie',
    'group'     => '91',
    'sql'       => <<<EOT
select id,resolution,resolvedDate from zt_bug
where deleted='0' and resolution!=' '
EOT,
    'settings'  => array
    (
        array
        (
            'type'   => 'pie',
            'group'  => array
            (
                array('field' => 'resolution', 'name' => '解决方案', 'group' => '')
            ),
            'metric' => array
            (
                array('field' => 'id', 'name' => 'Bug编号', 'valOrAgg' => 'count')
            )
        )
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'resolvedDate',
            'type'    => 'date',
            'name'    => '解决日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'id'           => array('name' => 'Bug编号', 'object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'resolution'   => array('name' => '解决方案', 'object' => 'bug', 'field' => 'resolution', 'type' => 'option'),
        'resolvedDate' => array('name' => '解决日期', 'object' => 'bug', 'field' => 'resolvedDate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'id'           => array('zh-cn' => 'Bug编号', 'zh-tw' => '', 'en' => 'Bug ID', 'de' => '', 'fr' => ''),
        'resolution'   => array('zh-cn' => '解决方案', 'zh-tw' => '', 'en' => 'Resolution', 'de' => '', 'fr' => ''),
        'resolvedDate' => array('zh-cn' => '解决日期', 'zh-tw' => '', 'en' => 'Resolved Date', 'de' => '', 'fr' => '')
    ),
    'stage'     => 'published',
    'builtin'   => '0'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20002,
    'name'      => '活跃账号情况-活跃账号数项目间对比',
    'code'      => 'activeAccount_projectCompare',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '62',
    'sql'       => <<<EOT
SELECT t1.id, t1.name, t1.year, t1.month, t1.totalAccount, ifnull(t2.activeAccount,0) as activeAccount, ifnull(concat(truncate(t2.activeAccount/t1.totalAccount*100,2),'%'), 0) as ratio
FROM (
select t1.id, t1.name, t3.year, t3.month, count(distinct t2.`account`) as totalAccount
from zt_project as t1
left join zt_team as t2 on t1.id = t2.root
left join (
    SELECT DISTINCT YEAR(`date`) AS `year`, MONTH(`date`) AS `month`, cast(`date` as DATE) as date
    FROM zt_action
) as t3 on t2.`join` <= t3.date
left join zt_user as t4 on t2.account = t4.account
where t1.type = 'project'
and t4.deleted = '0'
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
    WHERE objectType = 'user' AND action = 'login'
  ) AS t4 ON t3.id = t4.objectID and t2.`join` <= t4.date
WHERE
  t3.deleted = '0' AND t1.type = 'project'
GROUP BY t1.id, t4.year, t4.month
) AS t2 ON t1.year = t2.year AND t1.month = t2.month AND t1.id = t2.id
ORDER BY t2.activeAccount DESC
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'activeAccount', 'valOrAgg' => 'value', 'name' => '活跃账号数'),
            array('field' => 'totalAccount', 'valOrAgg' => 'value', 'name' => '团队账号数'),
            array('field' => 'ratio', 'valOrAgg' => 'value', 'name' => '活跃账号比')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20003,
    'name'      => '活跃账号情况-公司账号日活跃度趋势',
    'code'      => 'activeAccount_activeTendency',
    'dimension' => '1',
    'type'      => 'line',
    'group'     => '56',
    'sql'       => <<<EOT
SELECT YEAR(t2.date) AS year, MONTH(t2.date) AS month, DAY(t2.date) AS day, COUNT(DISTINCT t1.account) AS count FROM zt_user AS t1
LEFT JOIN zt_action AS t2 ON t1.account = t2.actor
WHERE t2.objectType = 'user' AND t2.action = 'login'
GROUP BY YEAR(t2.date), MONTH(t2.date), DAY(t2.date)
EOT,
    'settings'  => array
    (
        'xaxis' => array
        (
            array('field' => 'day', 'name' => '日期', 'group' => 'value')
        ),
        'yaxis' => array
        (
            array('type' => 'value', 'field' => 'count', 'agg' => 'value', 'name' => '数量', 'valOrAgg' => 'value')
        )
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20004,
    'name'      => '应用数据-活跃产品数',
    'code'      => 'appData_activeProduct',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
select count(distinct REPLACE(product, ',', '')) as count, year(date) as year, month(date) as month
from zt_action
where objectType not in ('project','execution','task')
and product != ',0,'
and product != ','
and product != ''
group by year(date), month(date)
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'count', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20005,
    'name'      => '应用数据-本月新增产品数',
    'code'      => 'appData_createdProductCount',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
SELECT DISTINCT YEAR(createdDate) AS year, MONTH(createdDate) AS month, count(id) as count FROM zt_product
WHERE deleted = '0' AND shadow = '0'
GROUP BY YEAR(createdDate), MONTH(createdDate)
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'count', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20006,
    'name'      => '应用数据-本月新增产品名',
    'code'      => 'appData_createdProductName',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '47',
    'sql'       => <<<EOT
SELECT DISTINCT GROUP_CONCAT(name) AS name, YEAR(createdDate) AS year, MONTH(createdDate) AS month FROM zt_product
WHERE deleted = '0' AND shadow = '0'
GROUP BY YEAR(createdDate), MONTH(createdDate)

EOT,
    'settings'  => array
    (
        'value' => array('type' => 'text', 'field' => 'name', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20007,
    'name'      => '应用数据-活跃项目数',
    'code'      => 'appData_activeProject',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
select year(date) as year, month(date) as month, count(distinct project) as count
from zt_action
where project != 0
group by year(date), month(date)
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'count', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20008,
    'name'      => '应用数据-本月新增项目数',
    'code'      => 'appData_createdProjectCount',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT COUNT(id) as count, YEAR(openedDate) AS year, MONTH(openedDate) AS month FROM zt_project
WHERE deleted = '0' AND type = 'project'
GROUP BY YEAR(openedDate), MONTH(openedDate)
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'count', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20009,
    'name'      => '应用数据-本月新增项目名 ',
    'code'      => 'appData_createdProjectName',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '46',
    'sql'       => <<<EOT
SELECT DISTINCT GROUP_CONCAT(name) AS name, YEAR(openedDate) AS year, MONTH(openedDate) AS month FROM zt_project
WHERE deleted = '0' AND type = 'project'
GROUP BY YEAR(openedDate), MONTH(openedDate)

EOT,
    'settings'  => array
    (
        'value' => array('type' => 'text', 'field' => 'name', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20010,
    'name'      => '应用数据-项目任务概况表',
    'code'      => 'appData_projectTaskOverview',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '60',
    'sql'       => <<<EOT
SELECT
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
	t1.type = 'project'
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
	t1.type = 'project'
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
	t1.type = 'project'
	AND t3.objectType = 'task'
	AND t3.action IN ( 'opened', 'closed', 'finished', 'canceled', 'assigned' )
GROUP BY
	t1.id,
	YEAR ( t3.date ),
	MONTH ( t3.date )
	) AS t3 ON t1.id = t3.id
	AND t4.YEAR = t3.YEAR
	AND t4.MONTH = t3.MONTH
	order by t1.id,t4.year
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'createdTasks', 'valOrAgg' => 'value', 'name' => '新增任务数'),
            array('field' => 'contributors', 'valOrAgg' => 'value', 'name' => '新增任务人数'),
            array('field' => 'finishedTasks', 'valOrAgg' => 'value', 'name' => '完成任务数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20011,
    'name'      => '应用数据-产品测试表',
    'code'      => 'appData_productTest',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '63',
    'sql'       => <<<EOT
SELECT * FROM
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
	t1.deleted = '0'
	AND t2.deleted = '0'
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
	t1.deleted = '0'
	AND t2.deleted = '0'
	AND t2.`status` = 'closed'
	AND t2.resolution = 'fixed'
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
	t1.deleted = '0'
	AND t2.deleted = '0'
	AND t2.`status` = 'closed'
	AND t2.resolution = 'fixed'
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
  AND t1.deleted = '0'
	AND t2.deleted = '0'
	AND t3.deleted = '0'
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
	t1.deleted = '0'
	AND t2.deleted = '0'
GROUP BY
	t1.id,
	YEAR ( t2.openedDate ),
	MONTH ( t2.openedDate )
	) AS t5 ON t1.id = t5.id
	AND t6.YEAR = t5.YEAR
	AND t6.MONTH = t5.MONTH
) AS t WHERE t.name IS NOT NULL
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '产品'),
            array('field' => 'createdCases', 'valOrAgg' => 'value', 'name' => '新增用例数'),
            array('field' => 'avgBugsOfCase', 'valOrAgg' => 'value', 'name' => '用例平均Bug数'),
            array('field' => 'createdBugs', 'valOrAgg' => 'value', 'name' => '新增Bug数'),
            array('field' => 'fixedBugs', 'valOrAgg' => 'value', 'name' => '修复Bug数'),
            array('field' => 'avgFixedCycle', 'valOrAgg' => 'value', 'name' => 'Bug平均修复周期')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20012,
    'name'      => '应用数据-产品需求概况表',
    'code'      => 'appData_productStoryOverview',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '63',
    'sql'       => <<<EOT
SELECT * FROM
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
	t2.deleted = '0'
	AND t1.deleted = '0'
	AND t1.type = 'story'
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
	LEFT JOIN ( SELECT objectID, MAX( date ) AS date FROM zt_action WHERE objectType = 'story' AND action = 'linked2release' GROUP BY objectID ) AS t3 ON t1.id = t3.objectID
WHERE
	t1.deleted = '0'
	AND t2.deleted = '0'
	AND EXISTS ( SELECT 1 FROM zt_action WHERE objectID = t1.id AND objectType = 'story' AND action = 'linked2release' )
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
	t1.deleted = '0'
	AND t2.deleted = '0'
	AND t1.status = 'closed'
	AND t1.closedReason = 'done'
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
WHERE t.name IS NOT NULL
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '产品'),
            array('field' => 'createdStories', 'valOrAgg' => 'value', 'name' => '新增研发需求数'),
            array('field' => 'deliveredStories', 'valOrAgg' => 'value', 'name' => '交付需求数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20013,
    'name'      => '应用数据-项目需求概况表',
    'code'      => 'appData_projectStoryOverview',
    'dimension' => '1',
    'type'      => 'table',
    'group'     => '60',
    'sql'       => <<<EOT
SELECT * FROM (
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
	t3.type = 'project'
	AND t1.deleted = '0'
	AND t3.deleted = '0'
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
	LEFT JOIN ( SELECT objectID, MAX( date ) AS date FROM zt_action WHERE objectType = 'story' AND action = 'linked2release' GROUP BY objectID ) AS t4 ON t1.id = t4.objectID
WHERE
	t3.type = 'project'
	AND t1.deleted = '0'
	AND t3.deleted = '0'
	AND EXISTS ( SELECT 1 FROM zt_action WHERE objectID = t1.id AND objectType = 'story' AND action = 'linked2release' ) UNION
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
	t3.type = 'project'
	AND t1.STATUS = 'closed'
	AND t1.closedReason = 'done'
	AND t1.deleted = '0'
	AND t3.deleted = '0'
	) AS t1
GROUP BY
	t1.id,
	t1.name,
	t1.YEAR,
	t1.MONTH
	) AS t2 ON t1.id = t2.id
	AND t3.YEAR = t2.YEAR
	AND t3.MONTH = t2.MONTH
) AS t WHERE t.id IS NOT NULL
EOT,
    'settings'  => array
    (
        'group'  => array(),
        'column' => array
        (
            array('field' => 'name', 'valOrAgg' => 'value', 'name' => '项目'),
            array('field' => 'createdStories', 'valOrAgg' => 'value', 'name' => '新增研发需求数'),
            array('field' => 'deliveredStories', 'valOrAgg' => 'value', 'name' => '交付需求数')
        ),
        'filter' => array()
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20014,
    'name'      => '使用数据分析-当前版本',
    'code'      => 'appData_currentVersion',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '58',
    'sql'       => <<<EOT
SELECT REPLACE(REPLACE(REPLACE(value, 'max', '旗舰版'), 'biz', '企业版'), 'pro', '专业版') as version FROM zt_config WHERE owner = 'system' AND module = 'common' AND section = 'global' AND `key` = 'version'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'version', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->charts[] = array
(
    'id'        => 20015,
    'name'      => '使用数据分析-上线时间',
    'code'      => 'appData_onlineDate',
    'dimension' => '1',
    'type'      => 'card',
    'group'     => '58',
    'sql'       => <<<EOT
select `value` as date from zt_config where `owner` = 'system' and `key` = 'installedDate'
EOT,
    'settings'  => array
    (
        'value' => array('type' => 'value', 'field' => 'date', 'agg' => 'value'),
        'title' => array('type' => 'text', 'name' => ''),
        'type'  => 'value'
    ),
    'filters'   => array(),
    'stage'     => 'published',
    'builtin'   => '1'
);
