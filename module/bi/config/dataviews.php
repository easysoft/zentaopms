<?php
$config->bi->builtin->dataviews = array();

$config->bi->builtin->modules->dataviews = array(array('id' => 101, 'root' => 0, 'branch' => 0, 'name' => '内置数据分组', 'parent' => 0, 'path' => ',101,', 'grade' => 1, 'order' => 10, 'type' => 'dataview', 'from' => 0));

$build = array('name' => '版本数据', 'code' => 'build', 'view' => 'ztv_build', 'group' => '101', 'mode' => 'text');
$build['sql'] = <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,build.name AS `name`,build.builder AS `builder`,build.stories AS `stories`,build.bugs AS `bugs`,build.date AS `date`,build.desc AS `desc` FROM zt_build AS `build`  LEFT JOIN zt_product AS `product` ON product.id   = build.product  LEFT JOIN zt_project AS `project` ON project.id   = build.project  LEFT JOIN zt_project AS `execution` ON execution.id = build.execution where `build`.deleted = '0' LIMIT 100
EOT;
$buildfields = array();
$buildfields['product_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$buildfields['product_name']   = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$buildfields['project_id']     = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$buildfields['project_name']   = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$buildfields['execution_id']   = array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object');
$buildfields['execution_name'] = array('name' => '所属迭代', 'field' => 'name', 'object' => 'execution', 'type' => 'object');
$buildfields['name']           = array('name' => '名称编号', 'field' => 'name', 'object' => 'build', 'type' => 'string');
$buildfields['builder']        = array('name' => '构建者', 'field' => 'builder', 'object' => 'build', 'type' => 'user');
$buildfields['stories']        = array('name' => '完成的研发需求', 'field' => 'stories', 'object' => 'build', 'type' => 'string');
$buildfields['bugs']           = array('name' => '解决的Bug', 'field' => 'bugs', 'object' => 'build', 'type' => 'string');
$buildfields['date']           = array('name' => '打包日期', 'field' => 'date', 'object' => 'build', 'type' => 'date');
$buildfields['desc']           = array('name' => '描述', 'field' => 'desc', 'object' => 'build', 'type' => 'string');
$build['fields'] = $buildfields;
$config->bi->builtin->dataviews[] = $build;

$product = array('name' => '产品数据', 'code' => 'product', 'view' => 'ztv_product', 'group' => '101', 'mode' => 'text');
$product['sql'] = <<<EOT
SELECT product.id AS `id`,program.name AS `program_name`,program.id AS `program_id`,line.name AS `line_name`,product.name AS `name`,product.code AS `code`,product.type AS `type`,product.status AS `status`,product.desc AS `desc`,product.PO AS `PO`,product.QD AS `QD`,product.RD AS `RD`,product.createdBy AS `createdBy`,product.createdDate AS `createdDate` FROM zt_product AS `product`  LEFT JOIN zt_project AS `program` ON product.program = program.id  LEFT JOIN zt_module AS `line` ON product.line    = line.id where `product`.deleted = '0' LIMIT 100
EOT;
$productfields = array();
$productfields['id']           = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'number');
$productfields['program_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'program', 'type' => 'object');
$productfields['program_name'] = array('name' => '所属项目集', 'field' => 'name', 'object' => 'program', 'type' => 'object');
$productfields['line_name']    = array('name' => '产品线', 'field' => 'name', 'object' => 'line', 'type' => 'object');
$productfields['name']         = array('name' => '产品名称', 'field' => 'name', 'object' => 'product', 'type' => 'string');
$productfields['code']         = array('name' => '产品代号', 'field' => 'code', 'object' => 'product', 'type' => 'string');
$productfields['type']         = array('name' => '产品类型', 'field' => 'type', 'object' => 'product', 'type' => 'option');
$productfields['status']       = array('name' => '状态', 'field' => 'status', 'object' => 'product', 'type' => 'option');
$productfields['desc']         = array('name' => '产品描述', 'field' => 'desc', 'object' => 'product', 'type' => 'string');
$productfields['PO']           = array('name' => '产品负责人', 'field' => 'PO', 'object' => 'product', 'type' => 'user');
$productfields['QD']           = array('name' => '测试负责人', 'field' => 'QD', 'object' => 'product', 'type' => 'user');
$productfields['RD']           = array('name' => '发布负责人', 'field' => 'RD', 'object' => 'product', 'type' => 'user');
$productfields['createdBy']    = array('name' => '由谁创建', 'field' => 'createdBy', 'object' => 'product', 'type' => 'user');
$productfields['createdDate']  = array('name' => '创建日期', 'field' => 'createdDate', 'object' => 'product', 'type' => 'date');
$product['fields'] = $productfields;
$config->bi->builtin->dataviews[] = $product;

$productplan = array('name' => '产品计划数据', 'code' => 'productplan', 'view' => 'ztv_productplan', 'group' => '101', 'mode' => 'text');
$productplan['sql'] = <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,productplan.title AS `title`,productplan.status AS `status`,productplan.desc AS `desc`,productplan.begin AS `begin`,productplan.end AS `end` FROM zt_productplan AS `productplan`  LEFT JOIN zt_product AS `product` ON productplan.product = product.id where `productplan`.deleted = '0' LIMIT 100
EOT;
$productplanfields = array();
$productplanfields['product_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$productplanfields['product_name'] = array('name' => '产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$productplanfields['title']        = array('name' => '名称', 'field' => 'title', 'object' => 'productplan', 'type' => 'string');
$productplanfields['status']       = array('name' => '状态', 'field' => 'status', 'object' => 'productplan', 'type' => 'option');
$productplanfields['desc']         = array('name' => '描述', 'field' => 'desc', 'object' => 'productplan', 'type' => 'string');
$productplanfields['begin']        = array('name' => '开始日期', 'field' => 'begin', 'object' => 'productplan', 'type' => 'date');
$productplanfields['end']          = array('name' => '结束日期', 'field' => 'end', 'object' => 'productplan', 'type' => 'date');
$productplan['fields'] = $productplanfields;
$config->bi->builtin->dataviews[] = $productplan;

$release = array('name' => '产品发布数据', 'code' => 'release', 'view' => 'ztv_release', 'group' => '101', 'mode' => 'text');
$release['sql'] = <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,build.name AS `build_name`,build.id AS `build_id`,release.name AS `name`,release.status AS `status`,release.desc AS `desc`,release.date AS `date`,release.stories AS `stories`,release.bugs AS `bugs`,release.leftBugs AS `leftBugs` FROM zt_release AS `release`  LEFT JOIN zt_product AS `product` ON release.product = product.id  LEFT JOIN zt_project AS `project` ON release.project = project.id  LEFT JOIN zt_build AS `build` ON release.build   = build.id where `release`.deleted = '0' LIMIT 100
EOT;
$releasefields = array();
$releasefields['product_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$releasefields['product_name'] = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$releasefields['project_id']   = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$releasefields['project_name'] = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$releasefields['build_id']     = array('name' => 'ID', 'field' => 'id', 'object' => 'build', 'type' => 'object');
$releasefields['build_name']   = array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object');
$releasefields['name']         = array('name' => '发布名称', 'field' => 'name', 'object' => 'release', 'type' => 'string');
$releasefields['status']       = array('name' => '状态', 'field' => 'status', 'object' => 'release', 'type' => 'option');
$releasefields['desc']         = array('name' => '描述', 'field' => 'desc', 'object' => 'release', 'type' => 'string');
$releasefields['date']         = array('name' => '发布日期', 'field' => 'date', 'object' => 'release', 'type' => 'date');
$releasefields['stories']      = array('name' => '完成的研发需求', 'field' => 'stories', 'object' => 'release', 'type' => 'string');
$releasefields['bugs']         = array('name' => '解决的Bug', 'field' => 'bugs', 'object' => 'release', 'type' => 'string');
$releasefields['leftBugs']     = array('name' => '遗留的Bug', 'field' => 'leftBugs', 'object' => 'release', 'type' => 'string');
$release['fields'] = $releasefields;
$config->bi->builtin->dataviews[] = $release;

$project = array('name' => '项目数据', 'code' => 'project', 'view' => 'ztv_project', 'group' => '101', 'mode' => 'text');
$project['sql'] = <<<EOT
SELECT project.name AS `name`,project.code AS `code`,project.model AS `model`,project.type AS `type`,project.status AS `status`,project.desc AS `desc`,project.begin AS `begin`,project.end AS `end`,project.PO AS `PO`,project.PM AS `PM`,project.QD AS `QD`,project.RD AS `RD`,project.openedBy AS `openedBy`,project.openedDate AS `openedDate` FROM zt_project AS `project`  where `project`.deleted = '0' LIMIT 100
EOT;
$projectfields = array();
$projectfields['name']       = array('name' => '项目名称', 'field' => 'name', 'object' => 'project', 'type' => 'string');
$projectfields['code']       = array('name' => '项目代号', 'field' => 'code', 'object' => 'project', 'type' => 'string');
$projectfields['model']      = array('name' => '项目管理方式', 'field' => 'model', 'object' => 'project', 'type' => 'option');
$projectfields['type']       = array('name' => '项目类型', 'field' => 'type', 'object' => 'project', 'type' => 'option');
$projectfields['status']     = array('name' => '状态', 'field' => 'status', 'object' => 'project', 'type' => 'option');
$projectfields['desc']       = array('name' => '项目描述', 'field' => 'desc', 'object' => 'project', 'type' => 'string');
$projectfields['begin']      = array('name' => '计划开始', 'field' => 'begin', 'object' => 'project', 'type' => 'date');
$projectfields['end']        = array('name' => '计划完成', 'field' => 'end', 'object' => 'project', 'type' => 'date');
$projectfields['PO']         = array('name' => '产品负责人', 'field' => 'PO', 'object' => 'project', 'type' => 'user');
$projectfields['PM']         = array('name' => '项目负责人', 'field' => 'PM', 'object' => 'project', 'type' => 'user');
$projectfields['QD']         = array('name' => '测试负责人', 'field' => 'QD', 'object' => 'project', 'type' => 'user');
$projectfields['RD']         = array('name' => '发布负责人', 'field' => 'RD', 'object' => 'project', 'type' => 'user');
$projectfields['openedBy']   = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'project', 'type' => 'user');
$projectfields['openedDate'] = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'project', 'type' => 'date');
$project['fields'] = $projectfields;
$config->bi->builtin->dataviews[] = $project;

$execution = array('name' => '执行数据', 'code' => 'execution', 'view' => 'ztv_execution', 'group' => '101', 'mode' => 'text');
$execution['sql'] = <<<EOT
SELECT project.name AS `project_name`,project.id AS `project_id`,execution.name AS `name`,execution.code AS `code`,execution.type AS `type`,execution.status AS `status`,execution.desc AS `desc`,execution.begin AS `begin`,execution.end AS `end`,execution.PO AS `PO`,execution.PM AS `PM`,execution.QD AS `QD`,execution.RD AS `RD`,execution.openedBy AS `openedBy`,execution.openedDate AS `openedDate` FROM zt_project AS `execution`  LEFT JOIN zt_project AS `project` ON execution.project = project.id LIMIT 100
EOT;
$executionfields = array();
$executionfields['project_id']   = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$executionfields['project_name'] = array('name' => '项目名称', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$executionfields['name']         = array('name' => '迭代名称', 'field' => 'name', 'object' => 'execution', 'type' => 'string');
$executionfields['code']         = array('name' => '迭代代号', 'field' => 'code', 'object' => 'execution', 'type' => 'string');
$executionfields['type']         = array('name' => '迭代类型', 'field' => 'type', 'object' => 'execution', 'type' => 'option');
$executionfields['status']       = array('name' => '迭代状态', 'field' => 'status', 'object' => 'execution', 'type' => 'option');
$executionfields['desc']         = array('name' => '迭代描述', 'field' => 'desc', 'object' => 'execution', 'type' => 'string');
$executionfields['begin']        = array('name' => '计划开始', 'field' => 'begin', 'object' => 'execution', 'type' => 'date');
$executionfields['end']          = array('name' => '计划完成', 'field' => 'end', 'object' => 'execution', 'type' => 'date');
$executionfields['PO']           = array('name' => '产品负责人', 'field' => 'PO', 'object' => 'execution', 'type' => 'user');
$executionfields['PM']           = array('name' => '迭代负责人', 'field' => 'PM', 'object' => 'execution', 'type' => 'user');
$executionfields['QD']           = array('name' => '测试负责人', 'field' => 'QD', 'object' => 'execution', 'type' => 'user');
$executionfields['RD']           = array('name' => '发布负责人', 'field' => 'RD', 'object' => 'execution', 'type' => 'user');
$executionfields['openedBy']     = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'execution', 'type' => 'user');
$executionfields['openedDate']   = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'execution', 'type' => 'date');
$execution['fields'] = $executionfields;
$config->bi->builtin->dataviews[] = $execution;

$task = array('name' => '任务数据', 'code' => 'task', 'view' => 'ztv_task', 'group' => '101', 'mode' => 'text');
$task['sql'] = <<<EOT
SELECT project.name AS `project_name`,project.id AS `project_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,story.title AS `story_title`,story.id AS `story_id`,taskmodule.name AS `taskmodule_name`,taskmodule.id AS `taskmodule_id`,task.name AS `name`,task.pri AS `pri`,task.type AS `type`,task.status AS `status`,task.desc AS `desc`,task.estimate AS `estimate`,task.consumed AS `consumed`,task.left AS `left`,task.estStarted AS `estStarted`,task.deadline AS `deadline`,task.assignedTo AS `assignedTo`,task.finishedBy AS `finishedBy`,task.closedBy AS `closedBy`,task.openedBy AS `openedBy`,task.openedDate AS `openedDate` FROM zt_task AS `task`  LEFT JOIN zt_project AS `execution` ON task.execution = execution.id  LEFT JOIN zt_project AS `project` ON task.project   = project.id  LEFT JOIN zt_story AS `story` ON task.story     = story.id  LEFT JOIN zt_module AS `taskmodule` ON task.module    = taskmodule.id where `task`.deleted = '0' LIMIT 100
EOT;
$taskfields = array();
$taskfields['project_id']      = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$taskfields['project_name']    = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$taskfields['execution_id']    = array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object');
$taskfields['execution_name']  = array('name' => '所属执行', 'field' => 'name', 'object' => 'execution', 'type' => 'object');
$taskfields['story_id']        = array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'object');
$taskfields['story_title']     = array('name' => '相关研发需求', 'field' => 'title', 'object' => 'story', 'type' => 'object');
$taskfields['taskmodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'taskmodule', 'type' => 'object');
$taskfields['taskmodule_name'] = array('name' => '所属模块', 'field' => 'name', 'object' => 'taskmodule', 'type' => 'object');
$taskfields['name']            = array('name' => '任务名称', 'field' => 'name', 'object' => 'task', 'type' => 'string');
$taskfields['pri']             = array('name' => '优先级', 'field' => 'pri', 'object' => 'task', 'type' => 'option');
$taskfields['type']            = array('name' => '任务类型', 'field' => 'type', 'object' => 'task', 'type' => 'option');
$taskfields['status']          = array('name' => '任务状态', 'field' => 'status', 'object' => 'task', 'type' => 'option');
$taskfields['desc']            = array('name' => '任务描述', 'field' => 'desc', 'object' => 'task', 'type' => 'string');
$taskfields['estimate']        = array('name' => '最初预计', 'field' => 'estimate', 'object' => 'task', 'type' => 'string');
$taskfields['consumed']        = array('name' => '总计消耗', 'field' => 'consumed', 'object' => 'task', 'type' => 'string');
$taskfields['left']            = array('name' => '预计剩余', 'field' => 'left', 'object' => 'task', 'type' => 'string');
$taskfields['estStarted']      = array('name' => '预计开始', 'field' => 'estStarted', 'object' => 'task', 'type' => 'date');
$taskfields['deadline']        = array('name' => '截止日期', 'field' => 'deadline', 'object' => 'task', 'type' => 'date');
$taskfields['assignedTo']      = array('name' => '指派给', 'field' => 'assignedTo', 'object' => 'task', 'type' => 'user');
$taskfields['finishedBy']      = array('name' => '由谁完成', 'field' => 'finishedBy', 'object' => 'task', 'type' => 'user');
$taskfields['closedBy']        = array('name' => '由谁关闭', 'field' => 'closedBy', 'object' => 'task', 'type' => 'user');
$taskfields['openedBy']        = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'task', 'type' => 'user');
$taskfields['openedDate']      = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'task', 'type' => 'date');
$task['fields'] = $taskfields;
$config->bi->builtin->dataviews[] = $task;

$bug = array('name' => 'Bug数据', 'code' => 'bug', 'view' => 'ztv_bug', 'group' => '101', 'mode' => 'text');
$bug['sql'] = <<<EOT
SELECT bug.id AS `id`,bug.title AS `title`,bug.steps AS `steps`,bug.status AS `status`,bug.confirmed AS `confirmed`,bug.severity AS `severity`,product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,bugmodule.name AS `bugmodule_name`,bugmodule.id AS `bugmodule_id`,story.title AS `story_title`,story.id AS `story_id`,bug.pri AS `pri`,bug.openedBy AS `openedBy`,bug.openedDate AS `openedDate`,bug.resolvedBy AS `resolvedBy`,bug.resolution AS `resolution`,bug.resolvedDate AS `resolvedDate` FROM zt_bug AS `bug`  LEFT JOIN zt_product AS `product` ON product.id = bug.product  LEFT JOIN zt_story AS `story` ON story.id = bug.story  LEFT JOIN zt_module AS `productline` ON productline.id = product.line  LEFT JOIN zt_project AS `program` ON program.id = product.program  LEFT JOIN zt_project AS `project` ON project.id = bug.project  LEFT JOIN zt_module AS `bugmodule` ON bugmodule.id = bug.module where `bug`.deleted = '0' LIMIT 100
EOT;
$bugfields = array();
$bugfields['id']             = array('name' => 'Bug编号', 'field' => 'id', 'object' => 'bug', 'type' => 'number');
$bugfields['title']          = array('name' => 'Bug标题', 'field' => 'title', 'object' => 'bug', 'type' => 'string');
$bugfields['steps']          = array('name' => '重现步骤', 'field' => 'steps', 'object' => 'bug', 'type' => 'text');
$bugfields['status']         = array('name' => 'Bug状态', 'field' => 'status', 'object' => 'bug', 'type' => 'option');
$bugfields['confirmed']      = array('name' => '是否确认', 'field' => 'confirmed', 'object' => 'bug', 'type' => 'option');
$bugfields['severity']       = array('name' => '严重程度', 'field' => 'severity', 'object' => 'bug', 'type' => 'option');
$bugfields['product_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$bugfields['product_name']   = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$bugfields['project_id']     = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$bugfields['project_name']   = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$bugfields['bugmodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'bugmodule', 'type' => 'object');
$bugfields['bugmodule_name'] = array('name' => '所属模块', 'field' => 'name', 'object' => 'bugmodule', 'type' => 'object');
$bugfields['story_id']       = array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'object');
$bugfields['story_title']    = array('name' => '研发需求', 'field' => 'title', 'object' => 'story', 'type' => 'object');
$bugfields['pri']            = array('name' => '优先级', 'field' => 'pri', 'object' => 'bug', 'type' => 'option');
$bugfields['openedBy']       = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'bug', 'type' => 'user');
$bugfields['openedDate']     = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'bug', 'type' => 'date');
$bugfields['resolvedBy']     = array('name' => '解决者', 'field' => 'resolvedBy', 'object' => 'bug', 'type' => 'user');
$bugfields['resolution']     = array('name' => '解决方案', 'field' => 'resolution', 'object' => 'bug', 'type' => 'option');
$bugfields['resolvedDate']   = array('name' => '解决日期', 'field' => 'resolvedDate', 'object' => 'bug', 'type' => 'date');
$bug['fields'] = $bugfields;
$config->bi->builtin->dataviews[] = $bug;

$bugbuild = array('name' => '版本Bug数据', 'code' => 'bugbuild', 'view' => 'ztv_bugbuild', 'group' => '101', 'mode' => 'text');
$bugbuild['sql'] = <<<EOT
SELECT bug.id AS `id`,bug.title AS `title`,bug.steps AS `steps`,bug.status AS `status`,bug.confirmed AS `confirmed`,bug.severity AS `severity`,product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,build.name AS `build_name`,build.id AS `build_id`,module.name AS `module_name`,module.id AS `module_id`,testtask.name AS `testtask_name`,testtask.id AS `testtask_id`,bug.pri AS `pri`,bug.openedBy AS `openedBy`,bug.openedDate AS `openedDate`,bug.resolvedBy AS `resolvedBy`,bug.resolution AS `resolution`,bug.resolvedDate AS `resolvedDate`,casemodule.name AS `casemodule_name`,casemodule.id AS `casemodule_id` FROM zt_bug AS `bug`  LEFT JOIN zt_product AS `product` ON product.id = bug.product  LEFT JOIN zt_testtask AS `testtask` ON testtask.id = bug.testtask  LEFT JOIN zt_build AS `build` ON build.id = testtask.build  LEFT JOIN zt_project AS `execution` ON execution.id = build.execution  LEFT JOIN zt_project AS `project` ON project.id = build.project  LEFT JOIN zt_module AS `module` ON module.id = bug.module  LEFT JOIN zt_case AS `testcase` ON testcase.id = bug.case  LEFT JOIN zt_module AS `casemodule` ON casemodule.id = testcase.module LIMIT 100
EOT;
$bugbuildfields = array();
$bugbuildfields['id']              = array('name' => 'Bug编号', 'field' => 'id', 'object' => 'bugbuild', 'type' => 'number');
$bugbuildfields['title']           = array('name' => 'Bug标题', 'field' => 'title', 'object' => 'bugbuild', 'type' => 'string');
$bugbuildfields['steps']           = array('name' => '重现步骤', 'field' => 'steps', 'object' => 'bugbuild', 'type' => 'text');
$bugbuildfields['status']          = array('name' => 'Bug状态', 'field' => 'status', 'object' => 'bugbuild', 'type' => 'option');
$bugbuildfields['confirmed']       = array('name' => '是否确认', 'field' => 'confirmed', 'object' => 'bugbuild', 'type' => 'option');
$bugbuildfields['severity']        = array('name' => '严重程度', 'field' => 'severity', 'object' => 'bugbuild', 'type' => 'option');
$bugbuildfields['product_id']      = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$bugbuildfields['product_name']    = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$bugbuildfields['project_id']      = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$bugbuildfields['project_name']    = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$bugbuildfields['build_id']        = array('name' => 'ID', 'field' => 'id', 'object' => 'build', 'type' => 'object');
$bugbuildfields['build_name']      = array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object');
$bugbuildfields['module_id']       = array('name' => '编号', 'field' => 'id', 'object' => 'module', 'type' => 'object');
$bugbuildfields['module_name']     = array('name' => '所属模块', 'field' => 'name', 'object' => 'module', 'type' => 'object');
$bugbuildfields['testtask_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'testtask', 'type' => 'object');
$bugbuildfields['testtask_name']   = array('name' => '测试单', 'field' => 'name', 'object' => 'testtask', 'type' => 'object');
$bugbuildfields['pri']             = array('name' => '优先级', 'field' => 'pri', 'object' => 'bugbuild', 'type' => 'option');
$bugbuildfields['openedBy']        = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'bugbuild', 'type' => 'user');
$bugbuildfields['openedDate']      = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'bugbuild', 'type' => 'datetime');
$bugbuildfields['resolvedBy']      = array('name' => '解决者', 'field' => 'resolvedBy', 'object' => 'bugbuild', 'type' => 'user');
$bugbuildfields['resolution']      = array('name' => '解决方案', 'field' => 'resolution', 'object' => 'bugbuild', 'type' => 'option');
$bugbuildfields['resolvedDate']    = array('name' => '解决日期', 'field' => 'resolvedDate', 'object' => 'bugbuild', 'type' => 'datetime');
$bugbuildfields['casemodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'casemodule', 'type' => 'object');
$bugbuildfields['casemodule_name'] = array('name' => '模块', 'field' => 'name', 'object' => 'casemodule', 'type' => 'object');
$bugbuild['fields'] = $bugbuildfields;
$config->bi->builtin->dataviews[] = $bugbuild;

$story = array('name' => '需求数据', 'code' => 'story', 'view' => 'ztv_story', 'group' => '101', 'mode' => 'text');
$story['sql'] = <<<EOT
SELECT story.id AS `id`,story.title AS `title`,story.status AS `status`,story.stage AS `stage`,story.pri AS `pri`,product.name AS `product_name`,product.id AS `product_id`,storymodule.name AS `storymodule_name`,storymodule.id AS `storymodule_id`,story.closedDate AS `closedDate`,story.closedReason AS `closedReason`,story.openedBy AS `openedBy`,story.openedDate AS `openedDate` FROM zt_story AS `story`  LEFT JOIN zt_product AS `product` ON product.id = story.product  LEFT JOIN zt_module AS `storymodule` ON storymodule.id = story.module where `story`.deleted = '0' LIMIT 100
EOT;
$storyfields = array();
$storyfields['id']               = array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'number');
$storyfields['title']            = array('name' => '研发需求名称', 'field' => 'title', 'object' => 'story', 'type' => 'string');
$storyfields['status']           = array('name' => '当前状态', 'field' => 'status', 'object' => 'story', 'type' => 'option');
$storyfields['stage']            = array('name' => '所处阶段', 'field' => 'stage', 'object' => 'story', 'type' => 'option');
$storyfields['pri']              = array('name' => '优先级', 'field' => 'pri', 'object' => 'story', 'type' => 'option');
$storyfields['product_id']       = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$storyfields['product_name']     = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$storyfields['storymodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'storymodule', 'type' => 'object');
$storyfields['storymodule_name'] = array('name' => '所属模块', 'field' => 'name', 'object' => 'storymodule', 'type' => 'object');
$storyfields['closedDate']       = array('name' => '关闭日期', 'field' => 'closedDate', 'object' => 'story', 'type' => 'date');
$storyfields['closedReason']     = array('name' => '关闭原因', 'field' => 'closedReason', 'object' => 'story', 'type' => 'option');
$storyfields['openedBy']         = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'story', 'type' => 'user');
$storyfields['openedDate']       = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'story', 'type' => 'date');
$story['fields'] = $storyfields;
$config->bi->builtin->dataviews[] = $story;

$testcase = array('name' => '用例数据', 'code' => 'testcase', 'view' => 'ztv_testcase', 'group' => '101', 'mode' => 'text');
$testcase['sql'] = <<<EOT
SELECT testcase.id AS `id`,testcase.title AS `title`,testcase.pri AS `pri`,testcase.type AS `type`,testcase.stage AS `stage`,testcase.status AS `status`,testcase.version AS `version`,product.name AS `product_name`,product.id AS `product_id`,story.title AS `story_title`,story.id AS `story_id`,casemodule.name AS `casemodule_name`,casemodule.id AS `casemodule_id`,testcase.openedBy AS `openedBy`,testcase.openedDate AS `openedDate` FROM zt_case AS `testcase`  LEFT JOIN zt_product AS `product` ON product.id = testcase.product  LEFT JOIN zt_module AS `casemodule` ON casemodule.id = testcase.module  LEFT JOIN zt_story AS `story` ON story.id = testcase.story  LEFT JOIN zt_casestep AS `casestep` ON casestep.case = testcase.id where `testcase`.deleted = '0' LIMIT 100
EOT;
$testcasefields = array();
$testcasefields['id']              = array('name' => '用例编号', 'field' => 'id', 'object' => 'testcase', 'type' => 'number');
$testcasefields['title']           = array('name' => '用例标题', 'field' => 'title', 'object' => 'testcase', 'type' => 'string');
$testcasefields['pri']             = array('name' => '优先级', 'field' => 'pri', 'object' => 'testcase', 'type' => 'option');
$testcasefields['type']            = array('name' => '用例类型', 'field' => 'type', 'object' => 'testcase', 'type' => 'option');
$testcasefields['stage']           = array('name' => '适用环节', 'field' => 'stage', 'object' => 'testcase', 'type' => 'option');
$testcasefields['status']          = array('name' => '用例状态', 'field' => 'status', 'object' => 'testcase', 'type' => 'option');
$testcasefields['version']         = array('name' => '用例版本', 'field' => 'version', 'object' => 'testcase', 'type' => 'number');
$testcasefields['product_id']      = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$testcasefields['product_name']    = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$testcasefields['story_id']        = array('name' => '编号', 'field' => 'id', 'object' => 'story', 'type' => 'object');
$testcasefields['story_title']     = array('name' => '相关研发需求', 'field' => 'title', 'object' => 'story', 'type' => 'object');
$testcasefields['casemodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'casemodule', 'type' => 'object');
$testcasefields['casemodule_name'] = array('name' => '所属模块', 'field' => 'name', 'object' => 'casemodule', 'type' => 'object');
$testcasefields['openedBy']        = array('name' => '由谁创建', 'field' => 'openedBy', 'object' => 'testcase', 'type' => 'user');
$testcasefields['openedDate']      = array('name' => '创建日期', 'field' => 'openedDate', 'object' => 'testcase', 'type' => 'date');
$testcase['fields'] = $testcasefields;
$config->bi->builtin->dataviews[] = $testcase;

$casestep = array('name' => '用例步骤数据', 'code' => 'casestep', 'view' => 'ztv_casestep', 'group' => '101', 'mode' => 'text');
$casestep['sql'] = <<<EOT
SELECT testcase.title AS `testcase_title`,testcase.id AS `testcase_id`,casestep.type AS `type`,casestep.desc AS `desc`,casestep.expect AS `expect`,casestep.version AS `version` FROM zt_casestep AS `casestep`  LEFT JOIN zt_case AS `testcase` ON testcase.id = casestep.`case` LIMIT 100
EOT;
$casestepfields = array();
$casestepfields['testcase_id']    = array('name' => '用例编号', 'field' => 'id', 'object' => 'testcase', 'type' => 'object');
$casestepfields['testcase_title'] = array('name' => '用例', 'field' => 'title', 'object' => 'testcase', 'type' => 'object');
$casestepfields['type']           = array('name' => '步骤类型', 'field' => 'type', 'object' => 'casestep', 'type' => 'option');
$casestepfields['desc']           = array('name' => '步骤', 'field' => 'desc', 'object' => 'casestep', 'type' => 'string');
$casestepfields['expect']         = array('name' => '预期', 'field' => 'expect', 'object' => 'casestep', 'type' => 'string');
$casestepfields['version']        = array('name' => '用例版本', 'field' => 'version', 'object' => 'casestep', 'type' => 'number');
$casestep['fields'] = $casestepfields;
$config->bi->builtin->dataviews[] = $casestep;

$testtask = array('name' => '测试单列表', 'code' => 'testtask', 'view' => 'ztv_testtask', 'group' => '101', 'mode' => 'text');
$testtask['sql'] = <<<EOT
SELECT product.name AS `product_name`,product.id AS `product_id`,project.name AS `project_name`,project.id AS `project_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,build.name AS `build_name`,build.id AS `build_id`,testtask.id AS `id`,testtask.name AS `name`,testtask.type AS `type`,testtask.owner AS `owner`,testtask.pri AS `pri`,testtask.begin AS `begin`,testtask.end AS `end`,testtask.status AS `status` FROM zt_testtask AS `testtask`  LEFT JOIN zt_product AS `product` ON product.id   = testtask.product  LEFT JOIN zt_project AS `project` ON project.id   = testtask.project  LEFT JOIN zt_project AS `execution` ON execution.id = testtask.execution  LEFT JOIN zt_build AS `build` ON build.id     = testtask.build LIMIT 100
EOT;
$testtaskfields = array();
$testtaskfields['product_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'product', 'type' => 'object');
$testtaskfields['product_name']   = array('name' => '所属产品', 'field' => 'name', 'object' => 'product', 'type' => 'object');
$testtaskfields['project_id']     = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$testtaskfields['project_name']   = array('name' => '所属项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$testtaskfields['execution_id']   = array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object');
$testtaskfields['execution_name'] = array('name' => '所属执行', 'field' => 'name', 'object' => 'execution', 'type' => 'object');
$testtaskfields['build_id']       = array('name' => 'ID', 'field' => 'id', 'object' => 'build', 'type' => 'object');
$testtaskfields['build_name']     = array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object');
$testtaskfields['id']             = array('name' => '编号', 'field' => 'id', 'object' => 'testtask', 'type' => 'number');
$testtaskfields['name']           = array('name' => '名称', 'field' => 'name', 'object' => 'testtask', 'type' => 'string');
$testtaskfields['type']           = array('name' => '测试类型', 'field' => 'type', 'object' => 'testtask', 'type' => 'option');
$testtaskfields['owner']          = array('name' => '负责人', 'field' => 'owner', 'object' => 'testtask', 'type' => 'user');
$testtaskfields['pri']            = array('name' => '优先级', 'field' => 'pri', 'object' => 'testtask', 'type' => 'option');
$testtaskfields['begin']          = array('name' => '开始日期', 'field' => 'begin', 'object' => 'testtask', 'type' => 'date');
$testtaskfields['end']            = array('name' => '结束日期', 'field' => 'end', 'object' => 'testtask', 'type' => 'date');
$testtaskfields['status']         = array('name' => '当前状态', 'field' => 'status', 'object' => 'testtask', 'type' => 'option');
$testtask['fields'] = $testtaskfields;
$config->bi->builtin->dataviews[] = $testtask;

$testrun = array('name' => '测试单用例执行情况', 'code' => 'testrun', 'view' => 'ztv_testrun', 'group' => '101', 'mode' => 'text');
$testrun['sql'] = <<<EOT
SELECT testtask.name AS `testtask_name`,testtask.id AS `testtask_id`,testcase.title AS `testcase_title`,testcase.id AS `testcase_id`,testrun.assignedTo AS `assignedTo`,project.name AS `project_name`,project.id AS `project_id`,build.name AS `build_name`,build.id AS `build_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,casemodule.name AS `casemodule_name`,casemodule.id AS `casemodule_id`,testrun.lastRunner AS `lastRunner`,testrun.lastRunDate AS `lastRunDate`,testrun.lastRunResult AS `lastRunResult` FROM zt_testrun AS `testrun`  LEFT JOIN zt_case AS `testcase` ON testcase.id   = testrun.case  LEFT JOIN zt_product AS `product` ON product.id    = testcase.product  LEFT JOIN zt_testtask AS `testtask` ON testtask.id   = testrun.task  LEFT JOIN zt_module AS `casemodule` ON casemodule.id = testcase.module  LEFT JOIN zt_project AS `project` ON project.id    = testtask.project  LEFT JOIN zt_project AS `execution` ON execution.id  = testtask.execution  LEFT JOIN zt_build AS `build` ON build.id      = testtask.build LIMIT 100
EOT;
$testrunfields = array();
$testrunfields['testtask_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'testtask', 'type' => 'object');
$testrunfields['testtask_name']   = array('name' => '测试单', 'field' => 'name', 'object' => 'testtask', 'type' => 'object');
$testrunfields['testcase_id']     = array('name' => '用例编号', 'field' => 'id', 'object' => 'testcase', 'type' => 'object');
$testrunfields['testcase_title']  = array('name' => '用例', 'field' => 'title', 'object' => 'testcase', 'type' => 'object');
$testrunfields['assignedTo']      = array('name' => '指派给', 'field' => 'assignedTo', 'object' => 'testrun', 'type' => 'user');
$testrunfields['project_id']      = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$testrunfields['project_name']    = array('name' => '项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$testrunfields['build_id']        = array('name' => '编号', 'field' => 'id', 'object' => 'build', 'type' => 'object');
$testrunfields['build_name']      = array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object');
$testrunfields['execution_id']    = array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object');
$testrunfields['execution_name']  = array('name' => '执行', 'field' => 'name', 'object' => 'execution', 'type' => 'object');
$testrunfields['casemodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'casemodule', 'type' => 'object');
$testrunfields['casemodule_name'] = array('name' => '模块维护', 'field' => 'name', 'object' => 'casemodule', 'type' => 'object');
$testrunfields['lastRunner']      = array('name' => '最后执行人', 'field' => 'lastRunner', 'object' => 'testrun', 'type' => 'user');
$testrunfields['lastRunDate']     = array('name' => '最后执行时间', 'field' => 'lastRunDate', 'object' => 'testrun', 'type' => 'user');
$testrunfields['lastRunResult']   = array('name' => '结果', 'field' => 'lastRunResult', 'object' => 'testrun', 'type' => 'option');
$testrun['fields'] = $testrunfields;
$config->bi->builtin->dataviews[] = $testrun;

$testresult = array('name' => '测试单用例每次执行结果', 'code' => 'testresult', 'view' => 'ztv_testresult', 'group' => '101', 'mode' => 'text');
$testresult['sql'] = <<<EOT
SELECT testresult.caseResult AS `caseResult`,testresult.stepResults AS `stepResults`,testresult.lastRunner AS `lastRunner`,testresult.date AS `date`,testcase.title AS `testcase_title`,testcase.id AS `testcase_id`,testtask.name AS `testtask_name`,testtask.id AS `testtask_id`,execution.name AS `execution_name`,execution.id AS `execution_id`,project.name AS `project_name`,project.id AS `project_id`,casemodule.name AS `casemodule_name`,casemodule.id AS `casemodule_id`,build.name AS `build_name`,build.id AS `build_id`,caselib.name AS `caselib_name`,caselib.id AS `caselib_id` FROM zt_testresult AS `testresult`  LEFT JOIN zt_case AS `testcase` ON testcase.id   = testresult.case  LEFT JOIN zt_testrun AS `testrun` ON testrun.id    = testresult.run  LEFT JOIN zt_testtask AS `testtask` ON testrun.task  = testtask.id  LEFT JOIN zt_project AS `project` ON project.id    = testtask.project  LEFT JOIN zt_project AS `execution` ON execution.id  = testtask.execution  LEFT JOIN zt_module AS `casemodule` ON casemodule.id = testcase.module  LEFT JOIN zt_build AS `build` ON build.id      = testtask.build  LEFT JOIN zt_testsuite AS `caselib` ON caselib.id    = testcase.lib  LEFT JOIN zt_product AS `product` ON product.id    = testcase.product LIMIT 100
EOT;
$testresultfields = array();
$testresultfields['caseResult']      = array('name' => '测试结果', 'field' => 'caseResult', 'object' => 'testresult', 'type' => 'option');
$testresultfields['stepResults']     = array('name' => '步骤结果', 'field' => 'stepResults', 'object' => 'testresult', 'type' => 'json');
$testresultfields['lastRunner']      = array('name' => '最后执行人', 'field' => 'lastRunner', 'object' => 'testresult', 'type' => 'user');
$testresultfields['date']            = array('name' => '测试时间', 'field' => 'date', 'object' => 'testresult', 'type' => 'date');
$testresultfields['testcase_id']     = array('name' => '用例编号', 'field' => 'id', 'object' => 'testcase', 'type' => 'object');
$testresultfields['testcase_title']  = array('name' => '用例', 'field' => 'title', 'object' => 'testcase', 'type' => 'object');
$testresultfields['testtask_id']     = array('name' => '编号', 'field' => 'id', 'object' => 'testtask', 'type' => 'object');
$testresultfields['testtask_name']   = array('name' => '测试单', 'field' => 'name', 'object' => 'testtask', 'type' => 'object');
$testresultfields['execution_id']    = array('name' => '迭代编号', 'field' => 'id', 'object' => 'execution', 'type' => 'object');
$testresultfields['execution_name']  = array('name' => '执行', 'field' => 'name', 'object' => 'execution', 'type' => 'object');
$testresultfields['project_id']      = array('name' => '项目ID', 'field' => 'id', 'object' => 'project', 'type' => 'object');
$testresultfields['project_name']    = array('name' => '项目', 'field' => 'name', 'object' => 'project', 'type' => 'object');
$testresultfields['casemodule_id']   = array('name' => '编号', 'field' => 'id', 'object' => 'casemodule', 'type' => 'object');
$testresultfields['casemodule_name'] = array('name' => '模块维护', 'field' => 'name', 'object' => 'casemodule', 'type' => 'object');
$testresultfields['build_id']        = array('name' => '编号', 'field' => 'id', 'object' => 'build', 'type' => 'object');
$testresultfields['build_name']      = array('name' => '版本', 'field' => 'name', 'object' => 'build', 'type' => 'object');
$testresultfields['caselib_id']      = array('name' => '编号', 'field' => 'id', 'object' => 'caselib', 'type' => 'object');
$testresultfields['caselib_name']    = array('name' => '用例库', 'field' => 'name', 'object' => 'caselib', 'type' => 'object');
$testresult['fields'] = $testresultfields;
$config->bi->builtin->dataviews[] = $testresult;
