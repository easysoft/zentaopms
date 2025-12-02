<?php
$config->bi->builtin->pivots = array();

$config->bi->builtin->pivots[] = array
(
    'id'          => 1000,
    'version'     => '1',
    'name'        => array('zh-cn' => '完成项目工期透视表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'        => 'finishedProjectDuration',
    'dimension'   => '2',
    'driver'      => 'mysql',
    'group'       => '86',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.name,
    t2.program1,
    t1.begin,
    t1.`end`,
    t1.realBegan,
    t1.realEnd,
    t1.closedDate,
    t1.realduration,
    t1.realduration - t1.planduration as duration_deviation,
    round((t1.realduration - t1.planduration) / t1.planduration, 3) as rate
from
    (select
        name,
        CAST(substr(path,2,4) AS DECIMAL) as program1,
        begin,
        `end`,
        realBegan,
        realEnd,
        left(closedDate, 10) as closedDate,
        datediff(`end`, `begin`) as planduration,
        ifnull(if(left(`realEnd`,4) != '0000',datediff(`realEnd`,`realBegan`),datediff(`closedDate`,`realBegan`)),0) realduration
    from zt_project
    where type='project' and status='closed' and deleted='0') t1
left join
    (select
        id as programid,
        name as program1
    from zt_project
    where type='program'
    and grade=1) t2
on t1.program1=t2.programid;
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
        'group1'   => 'program1',
        'group2'   => 'name',
        'lastStep' => '4'
    ),
    'fields'    => array
    (
        'name'               => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'program1'           => array('object' => 'project', 'field' => 'program1', 'type' => 'string'),
        'begin'              => array('object' => 'project', 'field' => 'begin', 'type' => 'date'),
        'end'                => array('object' => 'project', 'field' => 'end', 'type' => 'date'),
        'realBegan'          => array('object' => 'project', 'field' => 'realBegan', 'type' => 'date'),
        'realEnd'            => array('object' => 'project', 'field' => 'realEnd', 'type' => 'date'),
        'closedDate'         => array('object' => 'project', 'field' => 'closedDate', 'type' => 'date'),
        'realduration'       => array('object' => 'project', 'field' => 'realduration', 'type' => 'number'),
        'duration_deviation' => array('object' => 'project', 'field' => 'duration_deviation', 'type' => 'number'),
        'rate'               => array('object' => 'project', 'field' => 'rate', 'type' => 'number')
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
    'id'          => 1001,
    'version'     => '1',
    'name'        => array('zh-cn' => '完成项目工时透视表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'        => 'finishedProjectHour',
    'dimension'   => '2',
    'driver'      => 'mysql',
    'group'       => '85',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.name as "projectname",
    t4.program1 as "topprogram",
    round(t2.estimate, 2) as "estimate",
    round(t2.consumed, 2) as "consumed",
    round(t2.consumed - t2.estimate, 2) as "deviation",
    round((t2.consumed - t2.estimate) / t2.estimate, 2) as "deviationrate",
    coalesce(t3.storys, 0) as "finishedstorys",
    coalesce(t3.storyestimate, 0) as "finishedstorysmate",
    round(coalesce(t3.storyestimate, 0) / coalesce(t2.consumed, 0), 2) as "demandsizesperunittime",
    t1.closedDate as "closeddate"
from
    (
        select
            id,
            name,
            CAST(substr(`path`, 2, 4) AS DECIMAL) as program1,
            closedDate
        from zt_project
        where deleted = '0'
        and type = 'project'
        and status = 'closed'
    ) as t1
    left join (
        select
            project,
            sum(estimate) as estimate,
            sum(consumed) as consumed
        from zt_task
        where deleted = '0'
        and project != 0
        group by project
    ) as t2 on t1.id = t2.project
    left join (
        select
            tt3.project,
            count(tt3.id) as storys,
            sum(estimate) as storyestimate
        from
            (
                select
                    tt1.id,
                    tt1.estimate,
                    tt2.project
                from zt_story tt1
                    left join zt_projectstory tt2 on tt1.id = tt2.story
                where tt1.deleted = '0'
                and tt1.status = 'closed'
                and tt1.closedReason = 'done'
            ) tt3
        group by
            tt3.project
    ) t3 on t1.id = t3.project
    left join (
        select
            id as programid,
            name as program1
        from zt_project
        where type = 'program'
        and grade = 1
    ) t4 on t1.program1 = t4.programid;
EOT,
    'settings'  => array
    (
        'columns'     => array
        (
            array('field' => 'estimate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'consumed', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'deviation', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'deviationrate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'finishedstorys', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'finishedstorysmate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'demandsizesperunittime', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow')
        ),
        'columnTotal' => 'noShow',
        'group1'      => 'topprogram',
        'group2'      => 'projectname',
        'lastStep'    => '4'
    ),
    'filters'   => array
    (
        array
        (
            'field'   => 'closeddate',
            'type'    => 'date',
            'name'    => '关闭日期',
            'default' => array('begin' => '', 'end' => '')
        )
    ),
    'fields'    => array
    (
        'projectname'            => array('object' => 'project', 'field' => 'projectname', 'type' => 'string'),
        'topprogram'             => array('object' => 'project', 'field' => 'topprogram', 'type' => 'string'),
        'estimate'               => array('object' => 'project', 'field' => 'estimate', 'type' => 'number'),
        'consumed'               => array('object' => 'project', 'field' => 'consumed', 'type' => 'number'),
        'deviation'              => array('object' => 'project', 'field' => 'deviation', 'type' => 'number'),
        'deviationrate'          => array('object' => 'project', 'field' => 'deviationrate', 'type' => 'number'),
        'finishedstorys'         => array('object' => 'project', 'field' => 'deviationrate', 'type' => 'string'),
        'finishedstorysmate'     => array('object' => 'project', 'field' => 'finishedstorysmate', 'type' => 'number'),
        'demandsizesperunittime' => array('object' => 'project', 'field' => 'demandsizesperunittime', 'type' => 'number'),
        'closeddate'             => array('object' => 'project', 'field' => 'closeddate', 'type' => 'date')
    ),
    'langs'     => array
    (
        'projectname'            => array('zh-cn' => '项目名称', 'zh-tw' => '项目名称', 'en' => 'projectname'),
        'topprogram'             => array('zh-cn' => '一级项目集', 'zh-tw' => '一级项目集', 'en' => 'topprogram'),
        'estimate'               => array('zh-cn' => '预计工时', 'zh-tw' => '预计工时', 'en' => 'estimate'),
        'consumed'               => array('zh-cn' => '消耗工时', 'zh-tw' => '消耗工时', 'en' => 'consumed'),
        'deviation'              => array('zh-cn' => '工时偏差', 'zh-tw' => '工时偏差', 'en' => 'deviation'),
        'deviationrate'          => array('zh-cn' => '工时偏差率', 'zh-tw' => '工时偏差率', 'en' => 'deviationrate'),
        'finishedstorys'         => array('zh-cn' => '完成需求数', 'zh-tw' => '完成需求数', 'en' => 'finishedstorys'),
        'finishedstorysmate'     => array('zh-cn' => '完成需求规模数', 'zh-tw' => '完成需求规模数', 'en' => 'finishedstorysmate'),
        'demandsizesperunittime' => array('zh-cn' => '单位时间交付需求规模数', 'zh-tw' => '单位时间交付需求规模数', 'en' => 'demandsizesperunittime'),
        'closeddate'             => array('zh-cn' => '关闭日期', 'zh-tw' => '关闭日期', 'en' => 'closeddate')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1002,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品缺陷数据汇总表', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
    'code'        => 'productBugSummary',
    'dimension'   => '3',
    'driver'      => 'mysql',
    'group'       => '100',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.name as product,
    coalesce(t2.name, '/') as topprogram,
    coalesce(t3.name, '/') as productline,
    coalesce(t6.exfixedstorys, 0) as exfixedstorys,
    round(coalesce(t6.exfixedstorysmate, 0), 3) as exfixedstorysmate,
    coalesce(t8.storycases, 0) as storycases,
    round(coalesce(t8.storycases / t6.exfixedstorysmate, 0), 3) as casedensity,
    round(coalesce(t10.casestorys / t6.exfixedstorys, 0), 3) as casecoveragerate,
    coalesce(t7.bug, 0) as bugs,
    coalesce(t7.effbugs, 0) as effectivebugs,
    coalesce(t7.pri12bugs, 0) as pri12bugs,
    round(coalesce(t7.bug / t6.exfixedstorysmate, 0), 3) as bugdensity,
    coalesce(t7.fixedbugs, 0) as fixedbugs,
    round(coalesce(t7.fixedbugs / t7.bug, 0), 3) as fixedbugsrate
from zt_product as t1
    left join zt_project as t2 on t1.program = t2.id
        and t2.type = 'program'
        and t2.grade = 1
    left join zt_module as t3 on t1.line = t3.id and t3.type = 'line'
    left join (
        select
            product,
            count(id) as exfixedstorys,
            sum(estimate) as exfixedstorysmate
        from zt_story
        where deleted = '0'
        and (stage in ('developed', 'testing', 'verified', 'released') or (status = 'closed' and closedReason = 'done'))
        group by product
    ) as t6 on t1.id = t6.product
    left join (
        select
            product,
            count(id) as bug,
            sum(case when resolution in ('fixed', 'postponed') or status = 'active' then 1=1 else 0 end) as effbugs,
            sum(case when resolution = 'fixed' then 1=1 else 0 end) as fixedbugs,
            sum(case when severity IN (1, 2) then 1=1 else 0 end) as pri12bugs
        from zt_bug
        where deleted = '0'
        group by product
    ) as t7 on t1.id = t7.product
    left join (
        select
            product,
            COUNT(id) as storycases
        from zt_case
        where deleted = '0'
        group by product
    ) as t8 on t1.id = t8.product
    left join (
        select
            tcase.product,
            COUNT(tcase.story) as casestorys
        from zt_case as tcase
        left join zt_story as tstory on tcase.story = tstory.id
        where tcase.deleted = '0'
        and tcase.story != '0'
        and tstory.deleted = '0'
        and (tstory.stage IN ('developed', 'testing', 'verified', 'released') OR (tstory.status = 'closed' and tstory.closedReason = 'done'))
        group by tcase.product
    ) as t10 on t1.id = t10.product
where t1.deleted = '0'
and t1.status != 'closed'
and t1.vision = 'rnd'
ORDER BY t1.order;
EOT,
    'settings'  => array
    (
        'columns'     => array
        (
            array('field' => 'exfixedstorys', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'exfixedstorysmate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'storycases', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'casedensity', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'casecoveragerate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'bugs', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'effectivebugs', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'pri12bugs', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'bugdensity', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'fixedbugs', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow'),
            array('field' => 'fixedbugsrate', 'stat' => 'sum', 'slice' => 'noSlice', 'showMode' => 'default', 'monopolize' => '0', 'showTotal' => 'noShow')
        ),
        'columnTotal' => 'noShow',
        'group1'      => 'topprogram',
        'group2'      => 'productline',
        'group3'      => 'product',
        'lastStep'    => '4'
    ),
    'fields'    => array
    (
        'product'           => array('object' => 'story', 'field' => 'product', 'type' => 'string'),
        'topprogram'        => array('object' => 'story', 'field' => 'topprogram', 'type' => 'string'),
        'productline'       => array('object' => 'story', 'field' => 'productline', 'type' => 'string'),
        'exfixedstorys'     => array('object' => 'story', 'field' => 'exfixedstorys', 'type' => 'string'),
        'exfixedstorysmate' => array('object' => 'story', 'field' => 'exfixedstorysmate', 'type' => 'number'),
        'storycases'        => array('object' => 'story', 'field' => 'storycases', 'type' => 'string'),
        'casedensity'       => array('object' => 'story', 'field' => 'casedensity', 'type' => 'number'),
        'casecoveragerate'  => array('object' => 'story', 'field' => 'casecoveragerate', 'type' => 'number'),
        'bugs'              => array('object' => 'story', 'field' => 'bugs', 'type' => 'string'),
        'effectivebugs'     => array('object' => 'story', 'field' => 'effectviebugs', 'type' => 'number'),
        'pri12bugs'         => array('object' => 'story', 'field' => 'pri12bugs', 'type' => 'number'),
        'bugdensity'        => array('object' => 'story', 'field' => 'bugdensity', 'type' => 'number'),
        'fixedbugs'         => array('object' => 'story', 'field' => 'fixedbugs', 'type' => 'number'),
        'fixedbugsrate'     => array('object' => 'story', 'field' => 'fixedbugsrate', 'type' => 'number')
    ),
    'langs'     => array
    (
        'product'           => array('zh-cn' => '产品', 'zh-tw' => '产品', 'en' => 'product'),
        'topprogram'        => array('zh-cn' => '一级项目集', 'zh-tw' => '一级项目集', 'en' => 'topprogram'),
        'productline'       => array('zh-cn' => '产品线', 'zh-tw' => '产品线', 'en' => 'productline'),
        'exfixedstorys'     => array('zh-cn' => '研发完成需求数', 'zh-tw' => '研发完成需求数', 'en' => 'exfixedstorys'),
        'exfixedstorysmate' => array('zh-cn' => '研发完成需求规模数', 'zh-tw' => '研发完成需求规模数', 'en' => 'exfixedstorysmate'),
        'storycases'        => array('zh-cn' => '需求用例数', 'zh-tw' => '需求用例数', 'en' => 'storycases'),
        'casedensity'       => array('zh-cn' => '用例密度', 'zh-tw' => '用例密度', 'en' => 'casedensity'),
        'casecoveragerate'  => array('zh-cn' => '用例覆盖率', 'zh-tw' => '用例覆盖率', 'en' => 'casecoveragerate'),
        'bugs'              => array('zh-cn' => 'Bug数', 'zh-tw' => 'Bug数', 'en' => 'bugs'),
        'effectivebugs'     => array('zh-cn' => '有效Bug数', 'zh-tw' => '有效Bug数', 'en' => 'effectviebugs'),
        'pri12bugs'         => array('zh-cn' => '优先级为1，2的Bug数', 'zh-tw' => '优先级为1，2的Bug数', 'en' => 'pri12bugs'),
        'bugdensity'        => array('zh-cn' => 'Bug密度', 'zh-tw' => 'Bug密度', 'en' => 'bugdensity'),
        'fixedbugs'         => array('zh-cn' => '修复Bug数', 'zh-tw' => '修复Bug数', 'en' => 'fixedbugs'),
        'fixedbugsrate'     => array('zh-cn' => 'Bug修复率', 'zh-tw' => 'Bug修复率', 'en' => 'fixedbugsrate')
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1003,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品需求交付统计表', 'zh-tw' => '產品完成度統計表', 'en' => 'Product Progress', 'de' => 'Product Progress', 'fr' => 'Product Progress'),
    'code'        => 'productProgress',
    'desc'        => array('zh-cn' => '按照产品列出需求总数，交付的需求总数(状态是关闭且关闭原因为已完成，或者研发阶段是已发布的需求)。', 'zh-tw' => '按照產品列出需求總數，完成的總數(狀態是關閉，或者研發階段是發布)，完成的百分比。', 'en' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.', 'de' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.', 'fr' => 'Number of total stories,done stories(state is closed, or stage is released), percent of completion.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.product,
    t2.name,
    (case when t1.closedReason = 'done' or t1.stage = 'released' then 1=1 else 1=0 end) as done,
    1 as count from zt_story as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0'
and t2.deleted='0'
and t2.shadow='0'
and (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t2.type=\$productType end)
and (case when \$product='' then 1=1 else t2.id=\$product end)
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'group1'      => 'product',
        'columns'     => array
        (
            array('field' => 'done', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'count', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product' => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'name'    => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'done'    => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'count'   => array('object' => 'project', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'product' => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'    => array('zh-cn' => 'name', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'done'    => array('zh-cn' => '交付需求数', 'zh-tw' => '交付需求数', 'en' => 'delivery', 'de' => '', 'fr' => ''),
        'count'   => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'done',
            'object'    => 'story',
            'whereSql'  => "WHERE t1.deleted='0'  and t1.closedReason = 'done' or t1.stage = 'released'",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product')
            )
        ),
        array
        (
            'field'     => 'count',
            'object'    => 'story',
            'whereSql'  => "WHERE t1.deleted='0' ",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1004,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品需求状态分布表', 'zh-tw' => '產品需求狀態分布表', 'en' => 'Story Status', 'de' => 'Story Status', 'fr' => 'Story Status'),
    'code'        => 'productStoryStatus',
    'desc'        => array('zh-cn' => '按照产品列出需求总数，状态的分布情况。', 'zh-tw' => '按照產品列出需求總數，狀態的分布情況。', 'en' => 'Total number and status distribution of stories.', 'de' => 'Total number and status distribution of stories.', 'fr' => 'Total number and status distribution of stories.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.product, t1.status,
    t2.name
from zt_story as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0'
and t2.deleted='0'
and t2.shadow='0'
and (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t2.type=\$productType end)
and (case when \$product='' then 1=1 else t2.id=\$product end)
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'product',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0, 'summary' => 'use')
        )
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product' => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'status'  => array('object' => 'story', 'field' => 'status', 'type' => 'option'),
        'name'    => array('object' => 'product', 'field' => 'name', 'type' => 'string')
    ),
    'langs'     => array
    (
        'product'          => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'status'           => array('zh-cn' => '不同状态需求', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'             => array('zh-cn' => 'name', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'status',
            'object'    => 'story',
            'whereSql'  => "WHERE t1.deleted='0' ",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product'),
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'status', 'queryField' => 'status')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1005,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品需求阶段分布表', 'zh-tw' => '產品需求階段分布表', 'en' => 'Story Stage', 'de' => 'Story Stage', 'fr' => 'Story Stage'),
    'code'        => 'productStoryStage',
    'desc'        => array('zh-cn' => '按照产品列出需求总数，研发阶段的分布情况。', 'zh-tw' => '按照產品列出需求總數，研發階段的分布情況。', 'en' => 'Total number and stage distribution of stories ', 'de' => 'Total number and stage distribution of stories ', 'fr' => 'Total number and stage distribution of stories '),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.product, t1.stage,
    t2.name
from zt_story as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0'
and t2.deleted='0'
and t2.shadow='0'
and (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t2.type=\$productType end)
and (case when \$product='' then 1=1 else t2.id=\$product end)
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'product',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stage', 'slice' => 'stage', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0)
        ),
        'summary'     => 'use'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product'          => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'stage'            => array('object' => 'story', 'field' => 'stage', 'type' => 'option'),
        'name'             => array('object' => 'product', 'field' => 'name', 'type' => 'string')
    ),
    'langs'     => array
    (
        'product'          => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stage'            => array('zh-cn' => '不同阶段需求', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'             => array('zh-cn' => 'name', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'stage',
            'object'    => 'story',
            'whereSql'  => "WHERE t1.deleted='0' ",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product'),
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'stage', 'queryField' => 'stage')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1006,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品发布数量统计表', 'zh-tw' => '產品發布數量統計表', 'en' => 'Product Release', 'de' => 'Product Release', 'fr' => 'Product Release'),
    'code'        => 'productRelease',
    'desc'        => array('zh-cn' => '按照产品列出发布的数量。', 'zh-tw' => '按照產品列出發布的數量。', 'en' => 'Product Release.', 'de' => 'Product Release.', 'fr' => 'Product Release.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.product,
    t2.name,
    1 as releases
from zt_release as t1
left join zt_product as t2 on t1.product=t2.id
left join zt_project as t3 on t2.program=t3.id
where t1.deleted='0'
and t2.deleted='0'
and t2.shadow='0'
and (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t2.type=\$productType end)
and (case when \$product='' then 1=1 else t2.id=\$product end)
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'group1'      => 'product',
        'columns'     => array
        (
            array('field' => 'releases', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product'  => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'name'     => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'releases' => array('object' => 'product', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'product'  => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'     => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'releases' => array('zh-cn' => '发布', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'releases',
            'object'    => 'release',
            'whereSql'  => "WHERE t1.deleted='0' ",
            'condition' => array
            (
                array('drillObject' => 'zt_release', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1007,
    'version'     => '1',
    'name'        => array('zh-cn' => '任务状态统计表', 'zh-tw' => '任務狀態統計表', 'en' => 'Task Status Report', 'de' => 'Task Status Report', 'fr' => 'Task Status Report', 'vi' => 'Task Status Report', 'ja' => 'Task Status Report'),
    'code'        => 'taskStatus',
    'desc'        => array('zh-cn' => '按照执行统计任务的状态分布情况。', 'zh-tw' => '按照執行統計任務的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.project,
    t3.name as projectname,
    t2.status,
    t1.name as executionname,
    t1.status as executionstatus,
    t2.execution as execution,
    t2.id as taskID,
    (case when cast(t2.deadline as date) < current_date()
         and t2.deadline is not null
         and t2.status != 'closed'
         and t2.status != 'done'
         and t2.status != 'cancel' then 1=1 else 0 end
     ) as timeout
from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t1.type in ('sprint','stage')
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and (case when \$beginDate='' then 1=1 else t1.begin>=cast(\$beginDate as date) end)
and (case when \$endDate='' then 1=1 else t1.end<=cast(\$endDate as date) end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$beginDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'taskID', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0'),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'project'         => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'          => array('object' => 'task', 'field' => 'status', 'type' => 'option'),
        'executionname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'       => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'taskID'          => array('object' => 'task', 'field' => '', 'type' => 'object'),
        'executionstatus' => array('object' => 'task', 'field' => '', 'type' => 'object'),
        'timeout'         => array('object' => 'task', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'project'         => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'status'          => array('zh-cn' => '任务状态', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'       => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'taskID'          => array('zh-cn' => '不同状态任务', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionstatus' => array('zh-cn' => 'executionstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'timeout'         => array('zh-cn' => 'timeout', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution', 'beginDate', 'endDate'),
        'showName'    => array('项目列表', '执行列表', '项目状态', '执行状态', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select','select', 'select', 'date', 'date'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution', '', ''),
        'default'     => array('doing', 'doing', '', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'taskID',
            'object'    => 'task',
            'whereSql'  => "left join zt_project t2 on t1.execution=t2.id left join zt_project as t3 on t3.id=t2.project WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_task', 'drillAlias' => 't1', 'drillField' => 'status', 'queryField' => 'status')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1008,
    'version'     => '1',
    'name'        => array('zh-cn' => '任务类型统计表', 'zh-tw' => '任務類型統計表', 'en' => 'Task Type Report', 'de' => 'Task Type Report', 'fr' => 'Task Type Report', 'vi' => 'Task Type Report', 'ja' => 'Task Type Report'),
    'code'        => 'taskType',
    'desc'        => array('zh-cn' => '按照项目统计任务的类型分布情况。', 'zh-tw' => '按照項目統計任務的類型分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as projectname,
    t3.id as project,
    t1.name as executionname,
    t1.status as executionstatus,
    t1.id as execution,
    t2.type,
    t2.id as taskID
from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t1.type in ('sprint','stage')
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and (case when \$beginDate='' then 1=1 else t1.begin>=cast(\$beginDate as date) end)
and (case when \$endDate='' then 1=1 else t1.end<=cast(\$endDate as date) end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$beginDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'taskID', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0'),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'id'              => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'         => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'       => array('object' => 'project', 'field' => 'name', 'type' => 'object'),
        'type'            => array('object' => 'task', 'field' => 'type', 'type' => 'option'),
        'taskID'          => array('object' => 'task', 'field' => '', 'type' => 'object'),
        'executionstatus' => array('object' => 'task', 'field' => '', 'type' => 'object')
    ),
    'langs'     => array
    (
        'id'              => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'         => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'       => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'            => array('zh-cn' => '任务类型', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'taskID'          => array('zh-cn' => '不同类型任务', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionstatus' => array('zh-cn' => 'executionstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution', 'beginDate', 'endDate'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'select', 'select', 'date', 'date'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution', 'user', 'user'),
        'default'     => array('doing', 'doing', '', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'taskID',
            'object'    => 'task',
            'whereSql'  => "left join zt_project t2 on t1.execution=t2.id left join zt_project as t3 on t3.id=t2.project WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_task', 'drillAlias' => 't1', 'drillField' => 'type', 'queryField' => 'type')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1009,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目任务指派统计表', 'zh-tw' => '項目任務指派統計表', 'en' => 'Task Assign Report', 'de' => 'Task Assign Report', 'fr' => 'Task Assign Report', 'vi' => 'Task Assign Report', 'ja' => 'Task Assign Report'),
    'code'        => 'projectTaskAssign',
    'desc'        => array('zh-cn' => '按照项目统计任务的指派给分布情况。', 'zh-tw' => '按照項目統計任務的指派給分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t4.name as projectname,
    t4.id as project,
    t1.name as executionname,
    t2.execution as execution,
    (case when t3.account is not null then t3.account else t2.assignedTo end) as assignedTo,
    t2.id as taskID,
    t1.status as executionstatus
from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_team as t3 on t3.type='task' and t3.root=t2.id
left join zt_project as t4 on t1.project=t4.id
where t1.deleted='0'
and t1.type in ('sprint','stage')
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t4.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t4.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and (case when \$beginDate='' then 1=1 else t1.begin>=cast(\$beginDate as date) end)
and (case when \$endDate='' then 1=1 else t1.end<=cast(\$endDate as date) end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$beginDate='' and \$endDate = '')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'taskID', 'slice' => 'assignedTo', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0'),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'id'              => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'         => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'       => array('object' => 'project', 'field' => 'name', 'type' => 'object'),
        'assignedTo'      => array('object' => 'task', 'field' => 'assignedTo', 'type' => 'user'),
        'taskID'          => array('object' => 'team', 'field' => '', 'type' => 'number'),
        'executionstatus' => array('object' => 'project', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'              => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'         => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'       => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'assignedTo'      => array('zh-cn' => '指派给', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'taskID'          => array('zh-cn' => '人员被指派任务', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionstatus' => array('zh-cn' => 'executionstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution', 'beginDate', 'endDate'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'select', 'select', 'date', 'date'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution', 'user', 'user'),
        'default'     => array('doing', 'doing', '', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'taskID',
            'object'    => 'task',
            'whereSql'  => "left join zt_project t2 on t1.execution=t2.id left join zt_project as t3 on t2.project=t3.id WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_task', 'drillAlias' => 't1', 'drillField' => 'assignedTo', 'queryField' => 'assignedTo')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1010,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目任务完成者统计表', 'zh-tw' => '項目任務完成者統計表', 'en' => 'Task Finish Report', 'de' => 'Task Finish Report', 'fr' => 'Task Finish Report', 'vi' => 'Task Finish Report', 'ja' => 'Task Finish Report'),
    'code'        => 'projectTaskFinished',
    'desc'        => array('zh-cn' => '按照项目统计任务的完成者分布情况。', 'zh-tw' => '按照項目統計任務的完成者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
 t1.id,
 t3.name as projectname,
 t3.id as project,
 t1.name as executionname,
 t2.execution as execution,
 t2.finishedBy,
 t2.id as taskID,
 t1.status as executionstatus
from zt_project as t1
left join zt_task as t2 on t1.id=t2.execution
left join zt_project as t3 on t1.project=t3.id
left join zt_user as t4 on t2.finishedBy=t4.account
where t1.deleted='0'
and t1.type in ('sprint','stage')
and t2.deleted='0'
and t2.finishedBy!=''
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$dept='' then 1=1 else t4.dept=\$dept end)
and (case when \$user='' then 1=1 else t2.finishedBy=\$user end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='' and \$dept='' and \$user='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'taskID', 'slice' => 'finishedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0'),
        array('from' => 'query', 'field' => 'dept', 'name' => '完成者所在部门', 'type' => 'select', 'typeOption' => 'dept', 'default' => '0'),
        array('from' => 'query', 'field' => 'user', 'name' => '完成者', 'type' => 'select', 'typeOption' => 'user', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'              => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'         => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'       => array('object' => 'project', 'field' => 'name', 'type' => 'object'),
        'finishedBy'      => array('object' => 'task', 'field' => 'finishedBy', 'type' => 'user'),
        'taskID'          => array('object' => 'task', 'field' => '', 'type' => 'number'),
        'executionstatus' => array('object' => 'task', 'field' => '', 'type' => 'string')
    ),
    'langs'     => array
    (
        'id'              => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'         => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'       => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'finishedBy'      => array('zh-cn' => '由谁完成', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'taskID'          => array('zh-cn' => '不同完成者完成的任务', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionstatus' => array('zh-cn' => 'executionstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution', 'beginDate', 'endDate'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'select', 'select', 'date', 'date'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution', 'user', 'user'),
        'default'     => array('doing', 'doing', '', '', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'taskID',
            'object'    => 'task',
            'whereSql'  => "left join zt_project t2 on t1.execution=t2.id left join zt_user t3 on t1.finishedBy=t3.account WHERE t1.deleted='0' AND t1.finishedBy!=''",
            'condition' => array
            (
                array('drillObject' => 'zt_task', 'drillAlias' => 't1', 'drillField' => 'finishedBy', 'queryField' => 'finishedBy'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1011,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目投入统计表', 'zh-tw' => '項目投入統計表', 'en' => 'Project Invest Report', 'de' => 'Project Invest Report', 'fr' => 'Project Invest Report', 'vi' => 'Project Invest Report', 'ja' => 'Project Invest Report'),
    'code'        => 'projectInvested',
    'desc'        => array('zh-cn' => '按照项目列出：任务数，需求数，人数，总消耗工时。', 'zh-tw' => '按照項目列出：任務數，需求數，人數，總消耗工時。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t5.name as projectname,
    t5.id as project,
    t1.name as executionname,
    t2.execution as execution,
    concat(t1.begin,' ~ ',t1.end) as timeLimit,
    t2.teams,
    t3.stories,
    round(t4.consumed,1) as consumed,
    t4.number,
    t1.status as executionstatus
from zt_project as t1
left join ztv_projectteams as t2 on t1.id=t2.execution
left join ztv_projectstories as t3 on t1.id=t3.execution
left join ztv_executionsummary as t4 on t1.id=t4.execution
left join zt_project as t5 on t1.project=t5.id
where t1.deleted='0'
and t1.type in ('sprint','stage')
and (case when \$projectStatus='' then 1=1 else t5.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t5.id=\$project end)
and (case when \$beginDate='' then 1=1 else t1.begin>=cast(\$beginDate as date) end)
and (case when \$endDate='' then 1=1 else t1.end<=cast(\$endDate as date) end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$beginDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'number', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'teams', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'beginDate', 'name' => '执行起始日期', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '执行结束日期', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'id'              => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'         => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'       => array('object' => 'project', 'field' => 'name', 'type' => 'object'),
        'timeLimit'       => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'teams'           => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'stories'         => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'consumed'        => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'number'          => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'executionstatus' => array('object' => 'project', 'field' => '', 'type' => 'object')
    ),
    'langs'     => array
    (
        'id'              => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'         => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'       => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'timeLimit'       => array('zh-cn' => '工期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'teams'           => array('zh-cn' => '人数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stories'         => array('zh-cn' => '需求数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'consumed'        => array('zh-cn' => '总消耗', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'number'          => array('zh-cn' => '任务数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionstatus' => array('zh-cn' => 'executionstatus', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'beginDate', 'endDate'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行起始日期', '执行结束日期'),
        'requestType' => array('select', 'select', 'select', 'date', 'date'),
        'selectList'  => array('project.status', 'execution.status', 'project', '', ''),
        'default'     => array('doing', 'doing', '', '$WEEKBEGIN', '$WEEKEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'number',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution=t2.id left join zt_project as t3 on t2.project=t3.id  where t1.deleted='0' and t2.deleted='0' and t2.type in ('sprint','stage')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'stories',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in('sprint', 'stage')  and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'teams',
            'object'    => 'user',
            'whereSql'  => "left join zt_team t2 on t1.account=t2.account left join zt_project t3 on t2.root=t3.id left join zt_project t4 on t3.project=t4.id where t2.type ='execution' and t3.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'consumed',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution=t2.id left join zt_project as t3 on t2.project=t3.id  where t1.deleted='0' and t2.deleted='0' and t2.type in ('sprint','stage') and t1.parent>='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1012,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目需求状态分布表', 'zh-tw' => '項目需求狀態分布表', 'en' => 'Project Story Status', 'de' => 'Project Story Status', 'fr' => 'Project Story Status', 'vi' => 'Project Story Status', 'ja' => 'Project Story Status'),
    'code'        => 'projectStoryStatus',
    'desc'        => array('zh-cn' => '按照项目统计需求的状态分布情况。', 'zh-tw' => '按照項目統計需求的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t2.id,
    t4.name as projectname,
    t4.id as project,
    t2.name as executionname,
    t2.id as execution,
    t3.status
from zt_projectstory as t1
left join zt_project as t2 on t1.project=t2.id
left join zt_story as t3 on t1.story=t3.id
left join zt_project as t4 on t4.id=t2.project
where t2.deleted='0' and t3.deleted='0'
and t2.type in('sprint', 'stage')
and (case when \$projectStatus='' then 1=1 else t4.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t2.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t4.id=\$project end)
and (case when \$execution='' then 1=1 else t2.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'status'        => array('object' => 'story', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'status'        => array('zh-cn' => '不同状态需求', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'status',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in('sprint', 'stage')  and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'status', 'queryField' => 'status')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1013,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目需求阶段分布表', 'zh-tw' => '項目需求階段分布表', 'en' => 'Project Stage Report', 'de' => 'Project Stage Report', 'fr' => 'Project Stage Report', 'vi' => 'Project Stage Report', 'ja' => 'Project Stage Report'),
    'code'        => 'projectStoryStage',
    'desc'        => array('zh-cn' => '按照项目统计需求阶段分布情况。', 'zh-tw' => '按照項目統計需求階段分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t2.id,
    t4.name as projectname,
    t4.id as project,
    t2.name as executionname,
    t2.id as execution,
    t3.stage
from zt_projectstory as t1
left join zt_project as t2 on t1.project=t2.id
left join zt_story as t3 on t1.story=t3.id
left join zt_project as t4 on t4.id=t2.project
where t2.deleted='0' and t3.deleted='0'
and t2.type in('sprint', 'stage')
and (case when \$projectStatus='' then 1=1 else t4.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t2.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t4.id=\$project end)
and (case when \$execution='' then 1=1 else t2.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'stage', 'slice' => 'stage', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'stage'         => array('object' => 'story', 'field' => 'stage', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stage'         => array('zh-cn' => '不同阶段需求', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'stage',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in('sprint', 'stage')  and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_story', 'drillAlias' => 't1', 'drillField' => 'stage', 'queryField' => 'stage')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1014,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目Bug解决方案分布表', 'zh-tw' => '項目Bug解決方案分布表', 'en' => 'Project Bug Resolution', 'de' => 'Project Bug Resolution', 'fr' => 'Project Bug Resolution', 'vi' => 'Project Bug Resolution', 'ja' => 'Project Bug Resolution'),
    'code'        => 'projectBugResolution',
    'desc'        => array('zh-cn' => '按照项目统计Bug的解决方案分布情况。', 'zh-tw' => '按照項目統計Bug的解決方案分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as project,
    t3.id as projectID,
    t1.id as execution,
    t1.name as executionname,
    t2.id as bugID,
    t2.resolution
from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and t2.resolution!=''
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'project',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'project'       => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'projectID'     => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'executionname' => array('object' => 'bug', 'field' => '', 'type' => 'string'),
        'bugID'         => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'resolution'    => array('object' => 'bug', 'field' => 'resolution', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '项目', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectID'     => array('zh-cn' => '执行', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'         => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolution'    => array('zh-cn' => '解决方案', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'resolution',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t1.execution=t2.id left join zt_project as t3 on t3.id=t2.project  where t1.deleted='0' and t2.deleted='0' and t1.resolution!=''",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'project'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolution', 'queryField' => 'resolution')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1015,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目Bug状态分布表', 'zh-tw' => '項目Bug狀態分布表', 'en' => 'Project Bug Status', 'de' => 'Project Bug Status', 'fr' => 'Project Bug Status', 'vi' => 'Project Bug Status', 'ja' => 'Project Bug Status'),
    'code'        => 'projectBugStatus',
    'desc'        => array('zh-cn' => '按照项目统计Bug的状态分布情况。', 'zh-tw' => '按照項目統計Bug的狀態分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as project,
    t3.id as projectID,
    t1.name as execution,
    t1.id as executionID,
    t2.id as bugID,
    t2.status
from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'status', 'slice' => 'status', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'noShow',
        'group1'      => 'project',
        'group2'      => 'execution'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'          => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'project'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'projectID'   => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'execution'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'executionID' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'bugID'       => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'status'      => array('object' => 'bug', 'field' => 'status', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'          => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectID'   => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionID' => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'       => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'status'      => array('zh-cn' => 'Bug状态', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('project.status', 'execution.status', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'status',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t2.id=t1.execution  left join zt_project as t3 on t3.id=t1.project WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'execution'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'project'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'status', 'queryField' => 'status')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1016,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目Bug创建者分布表', 'zh-tw' => '項目Bug創建者分布表', 'en' => 'Project Bug Opened', 'de' => 'Project Bug Opened', 'fr' => 'Project Bug Opened', 'vi' => 'Project Bug Opened', 'ja' => 'Project Bug Opened'),
    'code'        => 'projectBugOpenedBy',
    'desc'        => array('zh-cn' => '按照项目统计Bug的创建者分布情况。', 'zh-tw' => '按照項目統計Bug的創建者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as projectname,
    t3.id as projectID,
    t1.name as executionname,
    t1.id as execution,
    t2.id as bugID,
    t2.openedBy
from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'openedBy', 'slice' => 'openedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'projectID'     => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'bugID'         => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'openedBy'      => array('object' => 'project', 'field' => 'openedBy', 'type' => 'user')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectID'     => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'         => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'openedBy'      => array('zh-cn' => '创建者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'openedBy',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t2.id=t1.execution  left join zt_project as t3 on t3.id=t2.project  where t1.deleted='0' and t2.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'openedBy', 'queryField' => 'openedBy')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1017,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目Bug解决者分布表', 'zh-tw' => '項目Bug解決者分布表', 'en' => 'Project Bug Resolve', 'de' => 'Project Bug Resolve', 'fr' => 'Project Bug Resolve', 'vi' => 'Project Bug Resolve', 'ja' => 'Project Bug Resolve'),
    'code'        => 'projectBugResolvedBy',
    'desc'        => array('zh-cn' => '按照项目统计Bug的解决者分布情况。', 'zh-tw' => '按照項目統計Bug的解決者分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as projectname,
    t3.id as projectID,
    t1.name as executionname,
    t1.id as execution,
    t2.id as bugID,
    t2.resolvedBy
from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and t2.status!='active'
and t2.resolvedBy!=''
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
and not (\$projectStatus='' and \$executionStatus='' and \$project='' and \$execution='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'resolvedBy', 'slice' => 'resolvedBy', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus', 'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'projectID'     => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'bug', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'bugID'         => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'resolvedBy'    => array('object' => 'bug', 'field' => 'resolvedBy', 'type' => 'user')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectID'     => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'         => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolvedBy'    => array('zh-cn' => '解决者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'showName'    => array('项目状态', '执行状态', '项目列表', '执行列表'),
        'requestType' => array('select', 'select', 'select', 'select'),
        'selectList'  => array('projectStatus', 'executionStatus', 'project', 'execution'),
        'default'     => array('doing', 'doing', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'resolvedBy',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t2.id=t1.execution left join zt_project as t3 on t3.id=t2.project where t1.deleted='0' and t2.deleted='0' and t1.status!='active' and t1.resolvedBy!=''",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolvedBy', 'queryField' => 'resolvedBy')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1018,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目Bug指派给分布表', 'zh-tw' => '項目Bug指派給分布表', 'en' => 'Project Bug Assign', 'de' => 'Project Bug Assign', 'fr' => 'Project Bug Assign', 'vi' => 'Project Bug Assign', 'ja' => 'Project Bug Assign'),
    'code'        => 'projectBugAssignedBy',
    'desc'        => array('zh-cn' => '按照项目统计Bug的指派给分布情况。', 'zh-tw' => '按照項目統計Bug的指派給分布情況。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t3.name as project,
    t3.id as projectID,
    t1.name as execution,
    t1.id as executionID,
    t2.id as bugID,
    t2.assignedTo
from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'assignedTo', 'slice' => 'assignedTo', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'noShow',
        'group1'      => 'project',
        'group2'      => 'execution'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus',   'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'          => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'project'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'projectID'   => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'execution'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'executionID' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'bugID'       => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'assignedTo'  => array('object' => 'bug', 'field' => 'assignedTo', 'type' => 'user')
    ),
    'langs'     => array
    (
        'id'          => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'     => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectID'   => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'   => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionID' => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'       => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'assignedTo'  => array('zh-cn' => '指派给', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'assignedTo',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t1.project=t2.id left join zt_project as t3 on t1.execution=t3.id WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'project'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'assignedTo', 'queryField' => 'assignedTo'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'execution')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1019,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目质量表', 'zh-tw' => '項目質量表', 'en' => 'Project Quality Report', 'de' => 'Project Quality Report', 'fr' => 'Project Quality Report', 'vi' => 'Project Quality Report', 'ja' => 'Project Quality Report'),
    'code'        => 'projectQuality',
    'desc'        => array('zh-cn' => '列出项目的需求总数，完成需求数，任务总数，完成的任务数，Bug数，解决的Bug数，Bug/需求，Bug/任务，重要Bug数量(严重程度不大于3）。', 'zh-tw' => '列出項目的需求總數，完成需求數，任務總數，完成的任務數，Bug數，解決的Bug數，Bug/需求，Bug/任務，重要Bug數量(嚴重程度不大於3）。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t5.name as projectname,
    t5.id as project,
    t1.name as executionname,
    t1.id as execution,
    ifnull(t2.stories, 0) as stories,
    ifnull((t2.stories-t2.undone), 0) as doneStory,
    ifnull(t3.number, 0) as number,
    ifnull((t3.number-t3.undone), 0) as doneTask,
    ifnull(t4.bugs, 0) as bugs,
    ifnull(t4.resolutions, 0) as resolutions,
    ifnull(round(t4.bugs/(t2.stories-t2.undone),2), 0) as bugthanstory,
    ifnull(round(t4.bugs/(t3.number-t3.undone),2), 0) as bugthantask,
    ifnull(t4.seriousBugs, 0) as seriousBugs
from zt_project as t1
left join ztv_projectstories as t2 on t1.id=t2.execution
left join ztv_executionsummary as t3 on t1.id=t3.execution
left join ztv_projectbugs as t4 on t1.id=t4.execution
left join zt_project as t5 on t5.id=t1.project
where t1.deleted='0'
and t1.type in ('sprint','stage')
and t1.grade='1'
and (case when \$projectStatus='' then 1=1 else t5.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t5.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'doneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'number', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'doneTask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'bugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'resolutions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'bugthanstory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'bugthantask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'seriousBugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus',   'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'stories'       => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'doneStory'     => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'number'        => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'doneTask'      => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'bugs'          => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'resolutions'   => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'bugthanstory'  => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'bugthantask'   => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'seriousBugs'   => array('object' => 'project', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stories'       => array('zh-cn' => '需求总数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'doneStory'     => array('zh-cn' => '关闭需求数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'number'        => array('zh-cn' => '任务总数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'doneTask'      => array('zh-cn' => '完成任务数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugs'          => array('zh-cn' => 'Bug数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolutions'   => array('zh-cn' => '解决Bug数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugthanstory'  => array('zh-cn' => 'Bug/完成需求', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugthantask'   => array('zh-cn' => 'Bug/完成任务', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'seriousBugs'   => array('zh-cn' => '重要Bug数', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'stories',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in ('sprint','stage') and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'doneStory',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in ('sprint','stage') and t1.deleted='0' and t1.status='closed'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'number',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t2.type in ('sprint','stage')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'doneTask',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t1.status in ('closed','done') and t2.type in ('sprint','stage')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'bugs',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'resolutions',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t1.resolution !=' '",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'seriousBugs',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t1.severity<='2'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1020,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品Bug类型统计表', 'zh-tw' => '產品Bug類型統計表', 'en' => 'Bug Type of Product', 'de' => 'Bug Type of Product', 'fr' => 'Bug Type of Product'),
    'code'        => 'productBugType',
    'desc'        => array('zh-cn' => '按照产品统计Bug的类型分布情况。', 'zh-tw' => '按照產品統計Bug的類型分布情況。', 'en' => 'Type distribution of Bugs.', 'de' => 'Type distribution of Bugs.', 'fr' => 'Type distribution of Bugs.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t2.product,
    t1.name,
    t2.id as bugID,
    t2.type
from zt_product as t1
left join zt_bug as t2 on t1.id=t2.product
left join zt_project as t3 on t1.program=t3.id
where t1.deleted='0'
and t1.shadow='0'
and t2.deleted='0'
and (case when \$productStatus='' then 1=1 else t1.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t1.type=\$productType end)
and (case when \$product='' then 1=1 else t1.id=\$product end)
order by t3.`order` asc, t1.line desc, t1.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'product',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'type', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0)
        ),
        'summary'     => 'use'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product' => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'name'    => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'bugID'   => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'type'    => array('object' => 'bug', 'field' => 'type', 'type' => 'option')
    ),
    'langs'     => array
    (
        'product' => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'    => array('zh-cn' => '产品', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'   => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'    => array('zh-cn' => '不同类型Bug', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'type',
            'object'    => 'bug',
            'whereSql'  => "WHERE t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'type', 'queryField' => 'type')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1021,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品质量表', 'zh-tw' => '產品質量表', 'en' => 'Product Quality', 'de' => 'Product Quality', 'fr' => 'Product Quality'),
    'code'        => 'productQuality',
    'desc'        => array('zh-cn' => '列出产品的需求数，完成的需求总数，Bug数，解决的Bug总数，Bug/需求，重要Bug数量(严重程度小于3)。', 'zh-tw' => '列出產品的需求數，完成的需求總數，Bug數，解決的Bug總數，Bug/需求，重要Bug數量(嚴重程度小於3)。', 'en' => 'Serious Bug (severity is less than 3).', 'de' => 'Serious Bug (severity is less than 3).', 'fr' => 'Serious Bug (severity is less than 3).'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t1.name,
    ifnull(t2.stories, 0) as stories,
    ifnull((t2.stories-t2.undone), 0) as doneStory,
    ifnull(t3.bugs, 0) as bugs,
    ifnull(t3.resolutions, 0) as resolutions,
    ifnull(round(t3.bugs/(t2.stories-t2.undone),2), 0) as bugthanstory,
    ifnull(t3.seriousBugs, 0) as seriousBugs
from zt_product as t1
left join ztv_productstories as t2 on t1.id=t2.product
left join ztv_productbugs as t3 on t1.id=t3.product
left join zt_project as t4 on t1.program=t4.id
where t1.deleted='0'
and t1.shadow='0'
and (case when \$productStatus='' then 1=1 else t1.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t1.type=\$productType end)
and (case when \$product='' then 1=1 else t1.id=\$product end)
order by t4.`order` asc, t1.line desc, t1.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'name',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0),
            array('field' => 'doneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0),
            array('field' => 'bugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0),
            array('field' => 'resolutions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0),
            array('field' => 'bugthanstory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0),
            array('field' => 'seriousBugs', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '1', 'showOrigin' => 0)
        ),
        'summary'     => 'use'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'           => array('object' => 'product', 'field' => 'id', 'type' => 'number'),
        'name'         => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'stories'      => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'doneStory'    => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'bugs'         => array('object' => 'product', 'field' => '', 'type' => 'string'),
        'resolutions'  => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'bugthanstory' => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'seriousBugs'  => array('object' => 'project', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'id'           => array('zh-cn' => '产品ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'         => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'stories'      => array('zh-cn' => '需求总数', 'zh-tw' => '需求总数', 'en' => 'Stories', 'de' => '', 'fr' => ''),
        'doneStory'    => array('zh-cn' => '关闭需求数', 'zh-tw' => '关闭需求数', 'en' => 'Closed Stories', 'de' => '', 'fr' => ''),
        'bugs'         => array('zh-cn' => 'Bug数', 'zh-tw' => 'Bug数', 'en' => 'Bugs', 'de' => '', 'fr' => ''),
        'resolutions'  => array('zh-cn' => '解决Bug数', 'zh-tw' => '解决Bug数', 'en' => 'Solved Bugs', 'de' => '', 'fr' => ''),
        'bugthanstory' => array('zh-cn' => 'Bug/完成需求', 'zh-tw' => 'Bug/完成需求', 'en' => 'Bug/Finished Story', 'de' => '', 'fr' => ''),
        'seriousBugs'  => array('zh-cn' => '重要Bug数', 'zh-tw' => '重要Bug数', 'en' => 'Serious Bugs', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'stories',
            'object'    => 'story',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id where t1.deleted='0' ",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'name')
            )
        ),
        array
        (
            'field'     => 'doneStory',
            'object'    => 'story',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id where t1.deleted='0' and t1.status='closed'",
            'condition' => array
            (
                array('drillObject' => 'zt_story', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'name')
            )
        ),
        array
        (
            'field'     => 'bugs',
            'object'    => 'bug',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id where t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'name')
            )
        ),
        array
        (
            'field'     => 'resolutions',
            'object'    => 'bug',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id where t1.deleted='0' and t1.resolution !=' '",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'name')
            )
        ),
        array
        (
            'field'     => 'seriousBugs',
            'object'    => 'bug',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id  where t1.deleted and t1.severity<='2'",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'name')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1022,
    'version'     => '1',
    'name'        => array('zh-cn' => '员工登录次数统计表', 'zh-tw' => '員工登錄次數統計表', 'en' => 'Login Times', 'de' => 'Login Times', 'fr' => 'Login Times'),
    'code'        => 'loginTimes',
    'desc'        => array('zh-cn' => '实现员工登录次数统计报表，按照天统计每天每个人的登录次数，以及总数。', 'zh-tw' => '實現員工登錄次數統計報表，按照天統計每天每個人的登錄次數，以及總數。', 'en' => 'The summary of user login times.', 'de' => 'The summary of user login times.', 'fr' => 'The summary of user login times.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '62',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select t1.actor,LEFT(t1.`date`,10) as `day` from zt_action t1
left join zt_user as t2 on t1.actor = t2.account
where t1.`action`='login'
and if(\$startDate='',1=1,LEFT(t1.`date`, 10)>=\$startDate)
and if(\$endDate='',1=1,LEFT(t1.`date`, 10)<=\$endDate)
and if(\$dept='',1=1,t2.`dept`=\$dept)
and not (\$startDate='' and \$endDate='' and \$dept='')
order by t1.`date` asc, t1.actor asc
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'group1'      => 'actor',
        'columns'     => array
        (
            array('field' => 'day', 'slice' => 'day', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY'),
        array('from' => 'query', 'field' => 'dept', 'name' => '部门', 'type' => 'select', 'typeOption' => 'dept', 'default' => '0')
    ),
    'fields'    => array
    (
        'actor' => array('object' => 'action', 'field' => 'actor', 'type' => 'user'),
        'day'   => array('object' => 'action', 'field' => 'day', 'type' => 'string')
    ),
    'langs'     => array
    (
        'actor' => array('name' => '操作者', 'zh-cn' => '操作者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'day'   => array('name' => 'day', 'zh-cn' => '日期', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('startDate', 'endDate'),
        'showName'    => array('起始时间', '结束时间'),
        'requestType' => array('date', 'date'),
        'selectList'  => array('user', 'user'),
        'default'     => array('$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'day',
            'object'    => 'action',
            'whereSql'  => "left join (select date(date) `day`,id from zt_action) t2 on t2.id=t1.id WHERE t1.`action`='login' AND if(\$startDate='',1,`date`>=\$startDate) AND if(\$endDate='',1,`date`<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day'),
                array('drillObject' => 'zt_action', 'drillAlias' => 't1', 'drillField' => 'actor', 'queryField' => 'actor')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1023,
    'version'     => '1',
    'name'        => array('zh-cn' => '日志汇总表', 'zh-tw' => '日誌匯總表', 'en' => 'Effort Summary', 'de' => 'Effort Summary', 'fr' => 'Effort Summary'),
    'code'        => 'effortSummary',
    'desc'        => array('zh-cn' => '查看某个时间段内的日志情况，可以按照部门选择。', 'zh-tw' => '查看某個時間段內的日誌情況，可以按照部門選擇。', 'en' => 'Effort summary of users within a certain period of time, you can select by department.', 'de' => 'Effort summary of users within a certain period of time, you can select by department.', 'fr' => 'Effort summary of users within a certain period of time, you can select by department.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '62',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.account,
    t1.consumed,
    t1.`date`,
    t2.dept as dept
from zt_effort as t1
left join zt_user as t2 on t1.account = t2.account
left join zt_dept as t3 on t2.dept = t3.id
where t1.`deleted` = '0'
and (case when \$startDate='' then 1=1 else cast(t1.`date` as date) >= cast(\$startDate as date) end)
and (case when \$endDate='' then 1=1 else cast(t1.`date` as date) <= cast(\$endDate as date) end)
and (t3.path like concat((select path from zt_dept where id=\$dept), '%') or \$dept=0)
and not (\$startDate='' and \$endDate='' and \$dept='')
order by t1.`date` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'account',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'consumed', 'slice' => 'date', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0)
        ),
        'lastStep'    => '4',
        'summary'     => 'use'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'dept', 'name' => '部门', 'type' => 'select', 'typeOption' => 'dept', 'default' => '0'),
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'account'  => array('object' => 'effort', 'field' => 'account', 'type' => 'user'),
        'consumed' => array('object' => 'effort', 'field' => 'consumed', 'type' => 'number'),
        'date'     => array('object' => 'effort', 'field' => 'date', 'type' => 'date'),
        'dept'     => array('object' => 'effort', 'field' => 'dept', 'type' => 'number')
    ),
    'langs'     => array
    (
        'account'  => array('name' => 'account', 'zh-cn' => '名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'consumed' => array('name' => 'consumed', 'zh-cn' => '消耗工时', 'zh-tw' => '消耗工时', 'en' => 'Cost', 'de' => '', 'fr' => ''),
        'date'     => array('name' => 'date', 'zh-cn' => '日期', 'zh-tw' => '日期', 'en' => 'Date', 'de' => '', 'fr' => ''),
        'dept'     => array('name' => 'dept', 'zh-cn' => '部门', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('dept', 'startDate', 'endDate'),
        'showName'    => array('部门', '起始时间', '结束时间'),
        'requestType' => array('select', 'date', 'date'),
        'selectList'  => array('dept', 'user', 'user'),
        'default'     => array('', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'consumed',
            'object'    => 'effort',
            'whereSql'  => "left join zt_user as t2 on t1.account = t2.account left join zt_dept as t3 on t2.dept = t3.id where t1.`deleted` = '0' and (case when \$startDate='' then 1=1 else cast(t1.`date` as date) >= cast(\$startDate as date) end) and (case when \$endDate='' then 1=1 else cast(t1.`date` as date) <= cast(\$endDate as date) end)  and (t3.path like concat((select path from zt_dept where id=\$dept), '%') or \$dept=0) order by t1.`date` asc",
            'condition' => array
            (
                array('drillObject' => 'zt_effort', 'drillAlias' => 't1', 'drillField' => 'account', 'queryField' => 'account'),
                array('drillObject' => 'zt_effort', 'drillAlias' => 't1', 'drillField' => 'date', 'queryField' => 'date')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1024,
    'version'     => '1',
    'name'        => array('zh-cn' => '公司动态汇总表', 'zh-tw' => '公司動態匯總表', 'en' => 'Company Dynamics', 'de' => 'Company Dynamics', 'fr' => 'Company Dynamics'),
    'code'        => 'companyDynamics',
    'desc'        => array('zh-cn' => '可以指定一个时期，列出相应的数据：1. 每天的登录次数。2. 每天的日志工时量。3. 每天新增的需求数。4. 每天关闭的需求数。5. 每天新增的任务数。6. 每天完成的任务数。7. 每天新增的Bug数。8. 每天解决的Bug数。9. 每天的动态数。', 'zh-tw' => '可以指定一個時期，列出相應的數據：1. 每天的登錄次數。2. 每天的日誌工時量。3. 每天新增的需求數。4. 每天關閉的需求數。5. 每天新增的任務數。6. 每天完成的任務數。7. 每天新增的Bug數。8. 每天解決的Bug數。9. 每天的動態數。', 'en' => 'The summary of company dynamics', 'de' => 'The summary of company dynamics', 'fr' => 'The summary of company dynamics'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '62',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select t1.day,t2.userlogin,t3.consumed,t4.storyopen,t5.storyclose,t6.taskopen,t7.taskfinish,t8.bugopen,t9.bugresolve,t1.actions from ztv_dayactions as t1
left join ztv_dayuserlogin as t2 on t1.day=t2.day
left join ztv_dayeffort as t3 on t1.day=t3.date
left join ztv_daystoryopen as t4 on t1.day=t4.day
left join ztv_daystoryclose as t5 on t1.day=t5.day
left join ztv_daytaskopen as t6 on t1.day=t6.day
left join ztv_daytaskfinish as t7 on t1.day=t7.day
left join ztv_daybugopen as t8 on t1.day=t8.day
left join ztv_daybugresolve as t9 on t1.day=t9.day
where if(\$startDate='',1=1,t1.day>=\$startDate)
and if(\$endDate='',1=1,t1.day<=\$endDate)
and not (\$startDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'group1'      => 'day',
        'columns'     => array
        (
            array('field' => 'userlogin', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'storyopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'storyclose', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'taskopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'taskfinish', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'bugopen', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'bugresolve', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'actions', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'startDate', 'name' => '起始时间', 'type' => 'date', 'typeOption' => '', 'default' => '$MONDAY'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '结束时间', 'type' => 'date', 'typeOption' => '', 'default' => '$SUNDAY')
    ),
    'fields'    => array
    (
        'day'        => array('object' => '', 'field' => 'day', 'type' => 'string'),
        'userlogin'  => array('object' => '', 'field' => 'userlogin', 'type' => 'string'),
        'consumed'   => array('object' => '', 'field' => 'consumed', 'type' => 'number'),
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
    'drills'    => array
    (
        array
        (
            'field'     => 'userlogin',
            'object'    => 'action',
            'whereSql'  => "left join ztv_dayuserlogin  t2 on date(t1.date)=t2.day where ((t1.objectType = 'user') and (t1.action = 'login')) and if(\$startDate='',1,t2.day>=\$startDate)  and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillObject' => 'ztv_dayuserlogin', 'drillAlias' => 't2', 'drillField' => 'day', 'queryField' => 'day')
            )
        ),
        array
        (
            'field'     => 'consumed',
            'object'    => 'effort',
            'whereSql'  => "where if(\$startDate='',1,t1.date>=\$startDate) and if(\$endDate='',1,t1.date<=\$endDate) ",
            'condition' => array
            (
                array('drillObject' => 'zt_effort', 'drillAlias' => 't1', 'drillField' => 'date', 'queryField' => 'day')
            )
        ),
        array
        (
            'field'     => 'storyopen',
            'object'    => 'story',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'story' and  action = 'opened') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day')
            )
        ),
        array
        (
            'field'     => 'storyclose',
            'object'    => 'story',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'story' and  action = 'closed') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day')
            )
        ),
        array
        (
            'field'     => 'taskopen',
            'object'    => 'task',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'task' and  action = 'opened') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day')
            )
        ),
        array
        (
            'field'     => 'taskfinish',
            'object'    => 'task',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'task' and  action = 'finished') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day')
            )
        ),
        array
        (
            'field'     => 'bugopen',
            'object'    => 'bug',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'bug' and  action = 'opened') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillAlias' => 't2', 'queryField' => 'day', 'drillObject' => '', 'drillField' => 'day')
            )
        ),
        array
        (
            'field'     => 'bugresolve',
            'object'    => 'bug',
            'whereSql'  => "right join (select objectID,date(`date`) day from zt_action where objectType = 'bug' and  action = 'resolved') t2 on t2.objectID=t1.id where if(\$startDate='',1,t2.day>=\$startDate) and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillObject' => '', 'drillAlias' => 't2', 'drillField' => 'day', 'queryField' => 'day')
            )
        ),
        array
        (
            'field'     => 'actions',
            'object'    => 'action',
            'whereSql'  => "left join (select id,date(`date`) day from zt_action)  t2 on t1.id=t2.id where if(\$startDate='',1,t2.day>=\$startDate)  and if(\$endDate='',1,t2.day<=\$endDate)",
            'condition' => array
            (
                array('drillObject' => '', 'drillAlias' => 't2', 'drillField' => 'day', 'queryField' => 'day')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1025,
    'version'     => '1',
    'name'        => array('zh-cn' => 'Bug解决表', 'zh-tw' => 'Bug解決表', 'en' => 'Solved Bugs', 'de' => 'Solved Bugs', 'fr' => 'Solved Bugs'),
    'code'        => 'slovedBugs',
    'desc'        => array('zh-cn' => '列出解决的Bug总数，解决方案的分布，占的比例（该用户解决的Bug的数量占所有的解决的Bug的数量)。', 'zh-tw' => '列出解決的Bug總數，解決方案的分布，占的比例（該用戶解決的Bug的數量占所有的解決的Bug的數量)。', 'en' => 'percentage:self resolved / all resolved', 'de' => 'percentage:self resolved / all resolved', 'fr' => 'percentage:self resolved / all resolved'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.resolvedBy,t1.resolution
from zt_bug as t1
left join zt_product as t2 on t1.product = t2.id
where t1.deleted='0'
and t2.deleted='0'
and t1.resolution!=''
and (case when \$startDate='' then 1=1 else cast(t1.resolvedDate as date)>=cast(\$startDate as date) end)
and (case when \$endDate='' then 1=1 else cast(t1.resolvedDate as date)<=cast(\$endDate as date) end)
and (case when \$product = '' then 1=1 else t1.product=\$product end)
and not (\$product='' and \$startDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'row', 'monopolize' => 1, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'resolvedBy'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'product', 'name' => '产品', 'type' => 'select', 'typeOption' => 'product', 'default' => '0'),
        array('from' => 'query', 'field' => 'startDate', 'name' => '解决日期开始', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '解决日期结束', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'resolvedBy'     => array('object' => 'bug', 'field' => 'resolvedBy', 'type' => 'user'),
        'resolution'     => array('object' => 'bug', 'field' => 'resolution', 'type' => 'option')
    ),
    'langs'     => array
    (
        'resolvedBy'     => array('zh-cn' => '解决者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolution'     => array('zh-cn' => '不同解决方案的Bug', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('product', 'startDate', 'endDate'),
        'showName'    => array('产品', '解决日期开始', '解决日期结束'),
        'requestType' => array('select', 'date', 'date'),
        'selectList'  => array('product', 'user', 'user'),
        'default'     => array('', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'resolution',
            'object'    => 'bug',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id WHERE t1.deleted='0' AND t1.resolution!=''  and (case when \$startDate='' then 1=1 else cast(t1.resolvedDate as date)>=cast(\$startDate as date) end)  and (case when \$endDate='' then 1=1 else cast(t1.resolvedDate as date)<=cast(\$endDate as date) end)  and (case when \$product = '' then 1=1 else t1.product=\$product end)",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolvedBy', 'queryField' => 'resolvedBy'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolution', 'queryField' => 'resolution')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1026,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目进展表', 'zh-tw' => '項目進展表', 'en' => 'Project Progress Report', 'de' => 'Project Progress Report', 'fr' => 'Project Progress Report', 'vi' => 'Project Progress Report', 'ja' => 'Project Progress Report'),
    'code'        => 'projectProgress',
    'desc'        => array('zh-cn' => '项目的：需求数，剩余需求数(过滤状态为已关闭的需求)，任务数，剩余任务数(过滤状态为已完成和已关闭的任务)，剩余工时（剩余任务的剩余工时），已消耗工时。', 'zh-tw' => '項目的需求數，任務數，已消耗工時，剩餘工時，剩餘需求數，剩餘任務數，進度。', 'en' => '', 'de' => '', 'fr' => '', 'vi' => '', 'ja' => ''),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t4.name as projectname,
    t4.id as project,
    t1.name as executionname,
    t1.id as execution,
    t1.status,
    t2.number as tasks,
    round(t2.consumed,2) as consumed,
    round(t2.`left`,2) as `left`,
    t3.stories,
    t2.undone as undoneTask,
    t3.undone as undoneStory,
    t2.totalReal from zt_project as t1
left join ztv_executionsummary as t2 on t1.id=t2.execution
left join ztv_projectstories as t3 on t1.id=t3.execution
left join zt_project as t4 on t4.id=t1.project
where t1.deleted='0'
and t1.type in ('sprint','stage')
and (case when \$projectStatus='' then 1=1 else t4.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t4.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'stories', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'undoneStory', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'tasks', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'undoneTask', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'left', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0),
            array('field' => 'consumed', 'slice' => 'noSlice', 'stat' => 'sum', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus',   'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'project'       => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'status'        => array('object' => 'project', 'field' => 'status', 'type' => 'option'),
        'tasks'         => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'consumed'      => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'left'          => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'stories'       => array('object' => 'project', 'field' => '', 'type' => 'string'),
        'undoneTask'    => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'undoneStory'   => array('object' => 'project', 'field' => '', 'type' => 'number'),
        'totalReal'     => array('object' => 'project', 'field' => '', 'type' => 'number')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => 'id', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'status'        => array('zh-cn' => '状态', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'tasks'         => array('zh-cn' => '任务数', 'zh-tw' => '任务数', 'en' => 'Tasks', 'de' => '', 'fr' => ''),
        'consumed'      => array('zh-cn' => '已消耗工时', 'zh-tw' => '已消耗工时', 'en' => 'Cost(h)', 'de' => '', 'fr' => ''),
        'left'          => array('zh-cn' => '剩余工时', 'zh-tw' => '剩余工时', 'en' => 'Left(h)', 'de' => '', 'fr' => ''),
        'stories'       => array('zh-cn' => '需求数', 'zh-tw' => '需求数', 'en' => 'Stories', 'de' => '', 'fr' => ''),
        'undoneTask'    => array('zh-cn' => '剩余任务数', 'zh-tw' => '剩余任务数', 'en' => 'Undone Task', 'de' => '', 'fr' => ''),
        'undoneStory'   => array('zh-cn' => '剩余需求数', 'zh-tw' => '剩余需求数', 'en' => 'Undone Story', 'de' => '', 'fr' => ''),
        'totalReal'     => array('zh-cn' => 'totalReal', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution', 'status'),
        'showName'    => array('项目列表', '执行列表', '执行状态'),
        'requestType' => array('select', 'select', 'select'),
        'selectList'  => array('project', 'execution', 'project.status'),
        'default'     => array('', '', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'stories',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in ('sprint','stage') and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'undoneStory',
            'object'    => 'story',
            'whereSql'  => "right join zt_projectstory as t2 on t2.story=t1.id left join zt_project as t3 on t2.project=t3.id  left join zt_project as t4 on t4.id=t3.project  where t3.deleted='0' and t3.type in ('sprint','stage')  and t1.status !='closed' and t1.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't4', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'tasks',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t2.type in ('sprint','stage')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'undoneTask',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t2.type in ('sprint','stage') and t1.status not in ('closed','done')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'left',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t2.type in ('sprint','stage') and t1.status not in ('closed','done')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        ),
        array
        (
            'field'     => 'consumed',
            'object'    => 'task',
            'whereSql'  => "left join zt_project as t2 on t1.execution = t2.id left join zt_project as t3 on t3.id=t2.project  where t2.deleted='0' and t1.deleted='0' and t2.type in ('sprint','stage')",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1027,
    'version'     => '1',
    'name'        => array('zh-cn' => '项目执行Bug类型统计表', 'zh-tw' => '項目Bug類型統計表', 'en' => 'Project Bug Type', 'de' => 'Project Bug Type', 'fr' => 'Project Bug Type'),
    'code'        => 'projectBugType',
    'desc'        => array('zh-cn' => '按照项目下不同执行统计Bug的类型分布情况。', 'zh-tw' => '按照項目統計Bug的類型分布情況。'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '60,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.id,
    t2.project as project,
    t3.name as projectname,
    t1.id as execution,
    t1.name as executionname,
    t2.id as bugID,
    t2.type from zt_project as t1
left join zt_bug as t2 on t1.id=t2.execution
left join zt_project as t3 on t3.id=t1.project
where t1.deleted='0'
and t2.deleted='0'
and (case when \$projectStatus='' then 1=1 else t3.status=\$projectStatus end)
and (case when \$executionStatus='' then 1=1 else t1.status=\$executionStatus end)
and (case when \$project='' then 1=1 else t3.id=\$project end)
and (case when \$execution='' then 1=1 else t1.id=\$execution end)
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'type', 'slice' => 'type', 'stat' => 'count', 'showTotal' => 'noShow', 'showMode' => 'default', 'monopolize' => 0, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'projectname',
        'group2'      => 'executionname'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'projectStatus',   'name' => '项目状态', 'type' => 'select', 'typeOption' => 'project.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'executionStatus', 'name' => '执行状态', 'type' => 'select', 'typeOption' => 'execution.status', 'default' => 'doing'),
        array('from' => 'query', 'field' => 'project', 'name' => '项目列表', 'type' => 'select', 'typeOption' => 'project', 'default' => '0'),
        array('from' => 'query', 'field' => 'execution', 'name' => '执行列表', 'type' => 'select', 'typeOption' => 'execution', 'default' => '0')
    ),
    'fields'    => array
    (
        'id'            => array('object' => 'project', 'field' => 'id', 'type' => 'number'),
        'project'       => array('object' => 'project', 'field' => 'name', 'type' => 'object'),
        'projectname'   => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'execution'     => array('object' => 'project', 'field' => 'id', 'type' => 'string'),
        'executionname' => array('object' => 'project', 'field' => 'name', 'type' => 'string'),
        'bugID'         => array('object' => 'bug', 'field' => '', 'type' => 'number'),
        'type'          => array('object' => 'bug', 'field' => 'type', 'type' => 'option')
    ),
    'langs'     => array
    (
        'id'            => array('zh-cn' => '项目ID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'project'       => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'projectname'   => array('zh-cn' => '项目名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'execution'     => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'executionname' => array('zh-cn' => '执行名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'         => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'type'          => array('zh-cn' => '不同类型的Bug', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('project', 'execution'),
        'showName'    => array('项目列表', '执行列表'),
        'requestType' => array('select', 'select'),
        'selectList'  => array('project', 'execution'),
        'default'     => array('', '')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'type',
            'object'    => 'bug',
            'whereSql'  => "left join zt_project as t2 on t2.id=t1.execution left join zt_project as t3 on t3.id=t2.project where t1.deleted='0' and t2.deleted='0'",
            'condition' => array
            (
                array('drillObject' => 'zt_project', 'drillAlias' => 't2', 'drillField' => 'name', 'queryField' => 'executionname'),
                array('drillObject' => 'zt_project', 'drillAlias' => 't3', 'drillField' => 'name', 'queryField' => 'projectname'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'type', 'queryField' => 'type')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1028,
    'version'     => '1',
    'name'        => array('zh-cn' => '产品Bug解决方案统计表', 'zh-tw' => '産品Bug解決方案統計表', 'en' => 'Bug Solution of Product'),
    'code'        => 'productBugSolution',
    'desc'        => array('zh-cn' => '按照产品统计Bug的解决方案分布情况。', 'zh-tw' => '按照産品統計Bug的解決方案分布情況。', 'en' => 'Solution distribution of bugs.'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '59,61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select t1.product,t2.name,t1.id as bugID,t1.resolution from zt_bug as t1
left join zt_product as t2 on t2.id=t1.product
left join zt_project as t3 on t2.program=t3.id
where t2.deleted='0' and t1.deleted='0'
and t2.shadow='0'
and t1.resolution != ''
and (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end)
and (case when \$productType='' then 1=1 else t2.type=\$productType end)
and (case when \$product='' then 1=1 else t2.id=\$product end)
order by t3.`order` asc, t2.line desc, t2.`order` asc
EOT,
    'settings'  => array
    (
        'group1'      => 'product',
        'columnTotal' => 'sum',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => '0', 'showOrigin' => 0)
        ),
        'summary'     => 'use'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'productStatus', 'name' => '产品状态', 'type' => 'select', 'typeOption' => 'product.status', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'productType', 'name' => '产品类型', 'type' => 'select', 'typeOption' => 'product.type', 'default' => 'normal'),
        array('from' => 'query', 'field' => 'product', 'name' => '产品列表', 'type' => 'select', 'typeOption' => 'product', 'default' => '0')
    ),
    'fields'    => array
    (
        'product'    => array('object' => 'product', 'field' => 'name', 'type' => 'object'),
        'name'       => array('object' => 'product', 'field' => 'name', 'type' => 'string'),
        'bugID'      => array('object' => 'bug', 'field' => 'id', 'type' => 'number'),
        'resolution' => array('object' => 'bug', 'field' => 'resolution', 'type' => 'option')
    ),
    'langs'     => array
    (
        'product'    => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'name'       => array('zh-cn' => '产品名称', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'bugID'      => array('zh-cn' => 'bugID', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolution' => array('zh-cn' => '解决方案', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array(),
    'drills'    => array
    (
        array
        (
            'field'     => 'resolution',
            'object'    => 'bug',
            'whereSql'  => " where t1.deleted='0' and t1.resolution != ''",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolution', 'queryField' => 'resolution'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'product', 'queryField' => 'product')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);

$config->bi->builtin->pivots[] = array
(
    'id'          => 1025,
    'version'     => '1.1',
    'name'        => array('zh-cn' => 'Bug解决表', 'zh-tw' => 'Bug解決表', 'en' => 'Solved Bugs', 'de' => 'Solved Bugs', 'fr' => 'Solved Bugs'),
    'code'        => 'slovedBugs',
    'desc'        => array('zh-cn' => '列出解决的Bug总数，解决方案的分布，占的比例（该用户解决的Bug的数量占所有的解决的Bug的数量)。', 'zh-tw' => '列出解決的Bug總數，解決方案的分布，占的比例（該用戶解決的Bug的數量占所有的解決的Bug的數量)。', 'en' => 'percentage:self resolved / all resolved', 'de' => 'percentage:self resolved / all resolved', 'fr' => 'percentage:self resolved / all resolved'),
    'dimension'   => '1',
    'driver'      => 'mysql',
    'group'       => '61',
    'createdDate' => '2009-03-14',
    'sql'         => <<<EOT
select
    t1.resolvedBy,t1.resolution
from zt_bug as t1
left join zt_product as t2 on t1.product = t2.id
where t1.deleted='0'
and t2.deleted='0'
and t1.resolution!=''
and (case when \$startDate='' then 1=1 else cast(t1.resolvedDate as date)>=cast(\$startDate as date) end)
and (case when \$endDate='' then 1=1 else cast(t1.resolvedDate as date)<=cast(\$endDate as date) end)
and (case when \$product = '' then 1=1 else t1.product=\$product end)
and not (\$product='' and \$startDate='' and \$endDate='')
EOT,
    'settings'  => array
    (
        'summary'     => 'use',
        'columns'     => array
        (
            array('field' => 'resolution', 'slice' => 'resolution', 'stat' => 'count', 'showTotal' => 'sum', 'showMode' => 'default', 'monopolize' => 1, 'showOrigin' => 0)
        ),
        'columnTotal' => 'sum',
        'group1'      => 'resolvedBy'
    ),
    'filters'   => array
    (
        array('from' => 'query', 'field' => 'product', 'name' => '产品', 'type' => 'select', 'typeOption' => 'product', 'default' => '0'),
        array('from' => 'query', 'field' => 'startDate', 'name' => '解决日期开始', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHBEGIN'),
        array('from' => 'query', 'field' => 'endDate', 'name' => '解决日期结束', 'type' => 'date', 'typeOption' => '', 'default' => '$MONTHEND')
    ),
    'fields'    => array
    (
        'resolvedBy'     => array('object' => 'bug', 'field' => 'resolvedBy', 'type' => 'user'),
        'resolution'     => array('object' => 'bug', 'field' => 'resolution', 'type' => 'option')
    ),
    'langs'     => array
    (
        'resolvedBy'     => array('zh-cn' => '解决者', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => ''),
        'resolution'     => array('zh-cn' => '不同解决方案的Bug', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '')
    ),
    'vars'      => array
    (
        'varName'     => array('product', 'startDate', 'endDate'),
        'showName'    => array('产品', '解决日期开始', '解决日期结束'),
        'requestType' => array('select', 'date', 'date'),
        'selectList'  => array('product', 'user', 'user'),
        'default'     => array('', '$MONTHBEGIN', '$MONTHEND')
    ),
    'drills'    => array
    (
        array
        (
            'field'     => 'resolution',
            'object'    => 'bug',
            'whereSql'  => "left join zt_product as t2 on t1.product = t2.id WHERE t1.deleted='0' AND t1.resolution!=''  and (case when \$startDate='' then 1=1 else cast(t1.resolvedDate as date)>=cast(\$startDate as date) end)  and (case when \$endDate='' then 1=1 else cast(t1.resolvedDate as date)<=cast(\$endDate as date) end)  and (case when \$product = '' then 1=1 else t1.product=\$product end)",
            'condition' => array
            (
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolvedBy', 'queryField' => 'resolvedBy'),
                array('drillObject' => 'zt_bug', 'drillAlias' => 't1', 'drillField' => 'resolution', 'queryField' => 'resolution')
            )
        )
    ),
    'stage'     => 'published',
    'builtin'   => '1'
);
