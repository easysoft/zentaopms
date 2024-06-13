<?php
$config->bi->builtin->dataviews = array();

$config->bi->builtin->dataviews[] = array
(
    'name'      => '版本数据',
    'code'      => 'build',
    'view'      => 'ztv_build',
    'sql'       => <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,build.name AS `name`,build.builder AS `builder`,build.stories AS `stories`,build.bugs AS `bugs`,build.date AS `date`,build.desc AS `desc` FROM zt_build AS `build`  LEFT JOIN zt_product AS `product` ON product.id   = build.product  LEFT JOIN zt_project AS `project` ON project.id   = build.project  LEFT JOIN zt_project AS `execution` ON execution.id = build.execution where `build`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'product_id'     => array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object'),
        'product_name'   => array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object'),
        'project_id'     => array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object'),
        'project_name'   => array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object'),
        'execution_id'   => array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object'),
        'execution_name' => array('name' => '所属迭代', 'field' => 'name', 'object' => 'execution', 'type' => 'object'),
        'name'           => array('name' => '名称编号', 'field' => 'name', 'object' => 'build', 'type' => 'string'),
        'builder'        => array('name' => '构建者', 'field' => 'builder', 'object' => 'build', 'type' => 'user'),
        'stories'        => array('name' => '完成的研发需求', 'field' => 'stories', 'object' => 'build', 'type' => 'string'),
        'bugs'           => array('name' => '解决的Bug', 'field' => 'bugs', 'object' => 'build', 'type' => 'string'),
        'date'           => array('name' => '打包日期', 'field' => 'date', 'object' => 'build', 'type' => 'date'),
        'desc'           => array('name' => '描述', 'field' => 'desc', 'object' => 'build', 'type' => 'string')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '产品数据',
    'code'      => 'product',
    'view'      => 'ztv_product',
    'sql'       => <<<EOT
SELECT product.id AS `id`,program.name AS `program_name`,program.id AS `program_id`,line.name AS `line_name`,product.name AS `name`,product.code AS `code`,product.type AS `type`,product.status AS `status`,product.desc AS `desc`,product.PO AS `PO`,product.QD AS `QD`,product.RD AS `RD`,product.createdBy AS `createdBy`,product.createdDate AS `createdDate` FROM zt_product AS `product`  LEFT JOIN zt_project AS `program` ON product.program = program.id  LEFT JOIN zt_module AS `line` ON product.line    = line.id where `product`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'id'           => array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'number'),
        'program_id'   => array('name' => '编号', 'field' => 'id', 'object' => 'program', 'type' => 'object'),
        'program_name' => array('name' => '所属项目集', 'field' => 'name', 'object' => 'program', 'type' => 'object'),
        'line_name'    => array('name' => '产品线', 'field' => 'name', 'object' => 'line', 'type' => 'object'),
        'name'         => array('name' => '产品名称', 'field' => 'name', 'object' => 'product', 'type' => 'string'),
        'code'         => array('name' => '产品代号', 'field' => 'code', 'object' => 'product', 'type' => 'string'),
        'type'         => array('name' => '产品类型', 'field' => 'type', 'object' => 'product', 'type' => 'option'),
        'status'       => array('name' => '状态', 'field' => 'status', 'object' => 'product', 'type' => 'option'),
        'desc'         => array('name' => '产品描述', 'field' => 'desc', 'object' => 'product', 'type' => 'string'),
        'PO'           => array('name' => '产品负责人', 'field' => 'PO', 'object' => 'product', 'type' => 'user'),
        'QD'           => array('name' => '测试负责人', 'field' => 'QD', 'object' => 'product', 'type' => 'user'),
        'RD'           => array('name' => '发布负责人', 'field' => 'RD', 'object' => 'product', 'type' => 'user'),
        'createdBy'    => array('name' => '由谁创建', 'field' => 'createdBy', 'object' => 'product', 'type' => 'user'),
        'createdDate'  => array('name' => '创建日期', 'field' => 'createdDate', 'object' => 'product', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '产品计划数据',
    'code'      => 'productplan',
    'view'      => 'ztv_productplan',
    'sql'       => <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,productplan.title AS `title`,productplan.status AS `status`,productplan.desc AS `desc`,productplan.begin AS `begin`,productplan.end AS `end` FROM zt_productplan AS `productplan`  LEFT JOIN zt_product AS `product` ON productplan.product = product.id where `productplan`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'product_id'   => array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object'),
        'product_name' => array('name' => '产品', 'field' => 'name', 'object' => 'product', 'type' => 'object'),
        'title'        => array('name' => '名称', 'field' => 'title', 'object' => 'productplan', 'type' => 'string'),
        'status'       => array('name' => '状态', 'field' => 'status', 'object' => 'productplan', 'type' => 'option'),
        'desc'         => array('name' => '描述', 'field' => 'desc', 'object' => 'productplan', 'type' => 'string'),
        'begin'        => array('name' => '开始日期', 'field' => 'begin', 'object' => 'productplan', 'type' => 'date'),
        'end'          => array('name' => '结束日期', 'field' => 'end', 'object' => 'productplan', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '产品发布数据',
    'code'      => 'release',
    'view'      => 'ztv_release',
    'sql'       => <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,build.name AS `build_name`,build.id AS `build_id`,release.name AS `name`,release.status AS `status`,release.desc AS `desc`,release.date AS `date`,release.stories AS `stories`,release.bugs AS `bugs`,release.leftBugs AS `leftBugs` FROM zt_release AS `release`  LEFT JOIN zt_product AS `product` ON release.product = product.id  LEFT JOIN zt_project AS `project` ON release.project = project.id  LEFT JOIN zt_build AS `build` ON release.build   = build.id where `release`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'product_id'   => array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object'),
        'product_name' => array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object'),
        'project_id'   => array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object'),
        'project_name' => array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object'),
        'build_id'     => array('name' => 'ID', 'field' => 'id', 'object' => 'build', 'type' => 'object'),
        'build_name'   => array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object'),
        'name'         => array('name' => '发布名称', 'field' => 'name', 'object' => 'release', 'type' => 'string'),
        'status'       => array('name' => '状态', 'field' => 'status', 'object' => 'release', 'type' => 'option'),
        'desc'         => array('name' => '描述', 'field' => 'desc', 'object' => 'release', 'type' => 'string'),
        'date'         => array('name' => '发布日期', 'field' => 'date', 'object' => 'release', 'type' => 'date'),
        'stories'      => array('name' => '完成的研发需求', 'field' => 'stories', 'object' => 'release', 'type' => 'string'),
        'bugs'         => array('name' => '解决的Bug', 'field' => 'bugs', 'object' => 'release', 'type' => 'string'),
        'leftBugs'     => array('name' => '遗留的Bug', 'field' => 'leftBugs', 'object' => 'release', 'type' => 'string')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '项目数据',
    'code'      => 'project',
    'view'      => 'ztv_project',
    'sql'       => <<<EOT
SELECT project.name AS `name`,project.code AS `code`,project.model AS `model`,project.type AS `type`,project.status AS `status`,project.desc AS `desc`,project.begin AS `begin`,project.end AS `end`,project.PO AS `PO`,project.PM AS `PM`,project.QD AS `QD`,project.RD AS `RD`,project.openedBy AS `openedBy`,project.openedDate AS `openedDate` FROM zt_project AS `project`  where `project`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'name'       => array('name' => '项目名称', 'field' => 'name', 'object' => 'project', 'type' => 'string'),
        'code'       => array('name' => '项目代号', 'field' => 'code', 'object' => 'project', 'type' => 'string'),
        'model'      => array('name' => '项目管理方式', 'field' => 'model', 'object' => 'project', 'type' => 'option'),
        'type'       => array('name' => '项目类型', 'field' => 'type', 'object' => 'project', 'type' => 'option'),
        'status'     => array('name' => '状态', 'field' => 'status', 'object' => 'project', 'type' => 'option'),
        'desc'       => array('name' => '项目描述', 'field' => 'desc', 'object' => 'project', 'type' => 'string'),
        'begin'      => array('name' => '计划开始', 'field' => 'begin', 'object' => 'project', 'type' => 'date'),
        'end'        => array('name' => '计划完成', 'field' => 'end', 'object' => 'project', 'type' => 'date'),
        'PO'         => array('name' => '产品负责人', 'field' => 'PO', 'object' => 'project', 'type' => 'user'),
        'PM'         => array('name' => '项目负责人', 'field' => 'PM', 'object' => 'project', 'type' => 'user'),
        'QD'         => array('name' => '测试负责人', 'field' => 'QD', 'object' => 'project', 'type' => 'user'),
        'RD'         => array('name' => '发布负责人', 'field' => 'RD', 'object' => 'project', 'type' => 'user'),
        'openedBy'   => array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'project', 'type' => 'user'),
        'openedDate' => array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'project', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '执行数据',
    'code'      => 'execution',
    'view'      => 'ztv_execution',
    'sql'       => <<<EOT
SELECT project.name AS `project_name`,project.id AS `project_id`,execution.name AS `name`,execution.code AS `code`,execution.type AS `type`,execution.status AS `status`,execution.desc AS `desc`,execution.begin AS `begin`,execution.end AS `end`,execution.PO AS `PO`,execution.PM AS `PM`,execution.QD AS `QD`,execution.RD AS `RD`,execution.openedBy AS `openedBy`,execution.openedDate AS `openedDate` FROM zt_project AS `execution`  LEFT JOIN zt_project AS `project` ON execution.project = project.id LIMIT 100
EOT,
    'fields'    => array
    (
        'project_id'   => array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object'),
        'project_name' => array('name' => '项目名称', 'field' => 'name', 'object' => 'project', 'type' => 'object'),
        'name'         => array('name' => '迭代名称', 'field' => 'name', 'object' => 'execution', 'type' => 'string'),
        'code'         => array('name' => '迭代代号', 'field' => 'code', 'object' => 'execution', 'type' => 'string'),
        'type'         => array('name' => '迭代类型', 'field' => 'type', 'object' => 'execution', 'type' => 'option'),
        'status'       => array('name' => '迭代状态', 'field' => 'status', 'object' => 'execution', 'type' => 'option'),
        'desc'         => array('name' => '迭代描述', 'field' => 'desc', 'object' => 'execution', 'type' => 'string'),
        'begin'        => array('name' => '计划开始', 'field' => 'begin', 'object' => 'execution', 'type' => 'date'),
        'end'          => array('name' => '计划完成', 'field' => 'end', 'object' => 'execution', 'type' => 'date'),
        'PO'           => array('name' => '产品负责人', 'field' => 'PO', 'object' => 'execution', 'type' => 'user'),
        'PM'           => array('name' => '迭代负责人', 'field' => 'PM', 'object' => 'execution', 'type' => 'user'),
        'QD'           => array('name' => '测试负责人', 'field' => 'QD', 'object' => 'execution', 'type' => 'user'),
        'RD'           => array('name' => '发布负责人', 'field' => 'RD', 'object' => 'execution', 'type' => 'user'),
        'openedBy'     => array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'execution', 'type' => 'user'),
        'openedDate'   => array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'execution', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '任务数据',
    'code'      => 'task',
    'view'      => 'ztv_task',
    'sql'       => <<<EOT
SELECT project.name AS `project_name`,project.id AS `project_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,story.title AS `story_title`,story.id AS `story_id`,taskmodule.name AS `taskmodule_name`,taskmodule.id AS `taskmodule_id`,task.name AS `name`,task.pri AS `pri`,task.type AS `type`,task.status AS `status`,task.desc AS `desc`,task.estimate AS `estimate`,task.consumed AS `consumed`,task.left AS `left`,task.estStarted AS `estStarted`,task.deadline AS `deadline`,task.assignedTo AS `assignedTo`,task.finishedBy AS `finishedBy`,task.closedBy AS `closedBy`,task.openedBy AS `openedBy`,task.openedDate AS `openedDate` FROM zt_task AS `task`  LEFT JOIN zt_project AS `execution` ON task.execution = execution.id  LEFT JOIN zt_project AS `project` ON task.project   = project.id  LEFT JOIN zt_story AS `story` ON task.story     = story.id  LEFT JOIN zt_module AS `taskmodule` ON task.module    = taskmodule.id where `task`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'project_id'      => array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object'),
        'project_name'    => array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object'),
        'execution_id'    => array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object'),
        'execution_name'  => array('name' => '所属执行', 'field' => 'name', 'object' => 'execution', 'type' => 'object'),
        'story_id'        => array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'object'),
        'story_title'     => array('name' => '相关研发需求', 'field' => 'title', 'object' => 'story', 'type' => 'object'),
        'taskmodule_id'   => array('name' => '编号', 'field' => 'id', 'object' => 'taskmodule', 'type' => 'object'),
        'taskmodule_name' => array('name' => '所属模块', 'field' => 'name', 'object' => 'taskmodule', 'type' => 'object'),
        'name'            => array('name' => '任务名称', 'field' => 'name', 'object' => 'task', 'type' => 'string'),
        'pri'             => array('name' => '优先级', 'field' => 'pri', 'object' => 'task', 'type' => 'option'),
        'type'            => array('name' => '任务类型', 'field' => 'type', 'object' => 'task', 'type' => 'option'),
        'status'          => array('name' => '任务状态', 'field' => 'status', 'object' => 'task', 'type' => 'option'),
        'desc'            => array('name' => '任务描述', 'field' => 'desc', 'object' => 'task', 'type' => 'string'),
        'estimate'        => array('name' => '最初预计', 'field' => 'estimate', 'object' => 'task', 'type' => 'string'),
        'consumed'        => array('name' => '总计消耗', 'field' => 'consumed', 'object' => 'task', 'type' => 'string'),
        'left'            => array('name' => '预计剩余', 'field' => 'left', 'object' => 'task', 'type' => 'string'),
        'estStarted'      => array('name' => '预计开始', 'field' => 'estStarted', 'object' => 'task', 'type' => 'date'),
        'deadline'        => array('name' => '截止日期', 'field' => 'deadline', 'object' => 'task', 'type' => 'date'),
        'assignedTo'      => array('name' => '指派给', 'field' => 'assignedTo', 'object' => 'task', 'type' => 'user'),
        'finishedBy'      => array('name' => '由谁完成', 'field' => 'finishedBy', 'object' => 'task', 'type' => 'user'),
        'closedBy'        => array('name' => '由谁关闭', 'field' => 'closedBy', 'object' => 'task', 'type' => 'user'),
        'openedBy'        => array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'task', 'type' => 'user'),
        'openedDate'      => array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'task', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => 'Bug数据',
    'code'      => 'bug',
    'view'      => 'ztv_bug',
    'sql'       => <<<EOT
SELECT bug.id AS `id`,bug.title AS `title`,bug.steps AS `steps`,bug.status AS `status`,bug.confirmed AS `confirmed`,bug.severity AS `severity`,product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,bugmodule.name AS `bugmodule_name`,bugmodule.id AS `bugmodule_id`,story.title AS `story_title`,story.id AS `story_id`,bug.pri AS `pri`,bug.openedBy AS `openedBy`,bug.openedDate AS `openedDate`,bug.resolvedBy AS `resolvedBy`,bug.resolution AS `resolution`,bug.resolvedDate AS `resolvedDate` FROM zt_bug AS `bug`  LEFT JOIN zt_product AS `product` ON product.id = bug.product  LEFT JOIN zt_story AS `story` ON story.id = bug.story  LEFT JOIN zt_module AS `productline` ON productline.id = product.line  LEFT JOIN zt_project AS `program` ON program.id = product.program  LEFT JOIN zt_project AS `project` ON project.id = bug.project  LEFT JOIN zt_module AS `bugmodule` ON bugmodule.id = bug.module where `bug`.deleted = '0' LIMIT 100
EOT,
    'fields'    => array
    (
        'id'             => array('name' => 'Bug编号', 'field' => 'id', 'object' => 'bug', 'type' => 'number'),
        'title'          => array('name' => 'Bug标题', 'field' => 'title', 'object' => 'bug', 'type' => 'string'),
        'steps'          => array('name' => '重现步骤', 'field' => 'steps', 'object' => 'bug', 'type' => 'text'),
        'status'         => array('name' => 'Bug状态', 'field' => 'status', 'object' => 'bug', 'type' => 'option'),
        'confirmed'      => array('name' => '是否确认', 'field' => 'confirmed', 'object' => 'bug', 'type' => 'option'),
        'severity'       => array('name' => '严重程度', 'field' => 'severity', 'object' => 'bug', 'type' => 'option'),
        'product_id'     => array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object'),
        'product_name'   => array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object'),
        'project_id'     => array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object'),
        'project_name'   => array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object'),
        'bugmodule_id'   => array('name' => '编号', 'field' => 'id', 'object' => 'bugmodule', 'type' => 'object'),
        'bugmodule_name' => array('name' => '所属模块', 'field' => 'name', 'object' => 'bugmodule', 'type' => 'object'),
        'story_id'       => array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'object'),
        'story_title'    => array('name' => '研发需求', 'field' => 'title', 'object' => 'story', 'type' => 'object'),
        'pri'            => array('name' => '优先级', 'field' => 'pri', 'object' => 'bug', 'type' => 'option'),
        'openedBy'       => array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'bug', 'type' => 'user'),
        'openedDate'     => array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'bug', 'type' => 'date'),
        'resolvedBy'     => array('name' => '解决者', 'field' => 'resolvedBy', 'object' => 'bug', 'type' => 'user'),
        'resolution'     => array('name' => '解决方案', 'field' => 'resolution', 'object' => 'bug', 'type' => 'option'),
        'resolvedDate'   => array('name' => '解决日期', 'field' => 'resolvedDate', 'object' => 'bug', 'type' => 'date')
    ),
    'group'     => '101'
);

$config->bi->builtin->dataviews[] = array
(
    'name'      => '版本Bug数据',
    'code'      => 'bugbuild',
    'view'      => 'ztv_bugbuild',
    'sql'       => <<<EOT
SELECT bug.id AS `id`,bug.title AS `title`,bug.steps AS `steps`,bug.status AS `status`,bug.confirmed AS `confirmed`,bug.severity AS `severity`,product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,build.name AS `build_name`,build.id AS `build_id`,module.name AS `module_name`,module.id AS `module_id`,testtask.name AS `testtask_name`,testtask.id AS `testtask_id`,bug.pri AS `pri`,bug.openedBy AS `openedBy`,bug.openedDate AS `openedDate`,bug.resolvedBy AS `resolvedBy`,bug.resolution AS `resolution`,bug.resolvedDate AS `resolvedDate`,casemodule.name AS `casemodule_name`,casemodule.id AS `casemodule_id` FROM zt_bug AS `bug`  LEFT JOIN zt_product AS `product` ON product.id = bug.product  LEFT JOIN zt_testtask AS `testtask` ON testtask.id = bug.testtask  LEFT JOIN zt_build AS `build` ON build.id = testtask.build  LEFT JOIN zt_project AS `execution` ON execution.id = build.execution  LEFT JOIN zt_project AS `project` ON project.id = build.project  LEFT JOIN zt_module AS `module` ON module.id = bug.module  LEFT JOIN zt_case AS `testcase` ON testcase.id = bug.case  LEFT JOIN zt_module AS `casemodule` ON casemodule.id = testcase.module LIMIT 100
EOT,
    'fields'    => array();
    'group'     => '101'
);
