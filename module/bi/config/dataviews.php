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
    'fields'    => array(),
    'group'     => '101'
);
