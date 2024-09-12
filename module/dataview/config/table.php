<?php
global $lang, $app;
$app->loadLang('bi');
$config->dataview->schema = new stdclass();
$config->dataview->schema->dtable = new stdclass();
$config->dataview->schema->dtable->fieldList = array();
$config->dataview->schema->dtable->fieldList['id']['name'] = 'id';
$config->dataview->schema->dtable->fieldList['id']['type'] = 'id';

$config->dataview->schema->dtable->fieldList['name']['name'] = 'name';
$config->dataview->schema->dtable->fieldList['name']['type'] = 'title';

$config->dataview->schema->dtable->fieldList['desc']['name'] = 'desc';
$config->dataview->schema->dtable->fieldList['desc']['type'] = 'desc';

$config->dataview->schema->dtable->fieldList['type']['name'] = 'type';
$config->dataview->schema->dtable->fieldList['type']['type'] = 'category';

$config->dataview->schema->dtable->fieldList['length']['name']  = 'length';
$config->dataview->schema->dtable->fieldList['length']['type']  = 'number';

$config->dataview->schema->dtable->fieldList['null']['name']  = 'null';
$config->dataview->schema->dtable->fieldList['null']['type']  = 'text';
