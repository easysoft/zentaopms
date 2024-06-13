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
    'fields'    => array(),
    'group'     => '101'
);
