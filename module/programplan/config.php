<?php
$config->programplan->create = new stdclass();
$config->programplan->edit   = new stdclass();
$config->programplan->create->requiredFields = 'name,begin,end';
$config->programplan->edit->requiredFields   = 'name,begin,end';

$config->programplan->datatable = new stdclass();
$config->programplan->datatable->defaultField = array('id', 'name', 'percent', 'attribute', 'milestone', 'begin', 'end', 'realBegan', 'realEnd', 'openedBy', 'openedDate', 'actions');

$config->programplan->datatable->fieldList['id']['title']    = 'idAB';
$config->programplan->datatable->fieldList['id']['fixed']    = 'left';
$config->programplan->datatable->fieldList['id']['width']    = '70';
$config->programplan->datatable->fieldList['id']['required'] = 'yes';

$config->programplan->datatable->fieldList['name']['title']    = 'name';
$config->programplan->datatable->fieldList['name']['fixed']    = 'left';
$config->programplan->datatable->fieldList['name']['width']    = 'auto';
$config->programplan->datatable->fieldList['name']['required'] = 'yes';

$config->programplan->datatable->fieldList['percent']['title']    = 'percent';
$config->programplan->datatable->fieldList['percent']['fixed']    = 'no';
$config->programplan->datatable->fieldList['percent']['width']    = '90';
$config->programplan->datatable->fieldList['percent']['required'] = 'no';

$config->programplan->datatable->fieldList['attribute']['title']    = 'attribute';
$config->programplan->datatable->fieldList['attribute']['fixed']    = 'no';
$config->programplan->datatable->fieldList['attribute']['width']    = '80';
$config->programplan->datatable->fieldList['attribute']['required'] = 'no';

$config->programplan->datatable->fieldList['milestone']['title']    = 'milestone';
$config->programplan->datatable->fieldList['milestone']['fixed']    = 'no';
$config->programplan->datatable->fieldList['milestone']['width']    = '100';
$config->programplan->datatable->fieldList['milestone']['required'] = 'no';

$config->programplan->datatable->fieldList['openedBy']['title']    = 'openedBy';
$config->programplan->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->programplan->datatable->fieldList['openedBy']['width']    = '90';
$config->programplan->datatable->fieldList['openedBy']['required'] = 'no';

$config->programplan->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->programplan->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->programplan->datatable->fieldList['openedDate']['width']    = '90';
$config->programplan->datatable->fieldList['openedDate']['required'] = 'no';

$config->programplan->datatable->fieldList['begin']['title']    = 'begin';
$config->programplan->datatable->fieldList['begin']['fixed']    = 'no';
$config->programplan->datatable->fieldList['begin']['width']    = '90';
$config->programplan->datatable->fieldList['begin']['required'] = 'no';

$config->programplan->datatable->fieldList['end']['title']    = 'end';
$config->programplan->datatable->fieldList['end']['fixed']    = 'no';
$config->programplan->datatable->fieldList['end']['width']    = '90';
$config->programplan->datatable->fieldList['end']['required'] = 'no';

$config->programplan->datatable->fieldList['realBegan']['title']    = 'realBegan';
$config->programplan->datatable->fieldList['realBegan']['fixed']    = 'no';
$config->programplan->datatable->fieldList['realBegan']['width']    = '90';
$config->programplan->datatable->fieldList['realBegan']['required'] = 'no';

$config->programplan->datatable->fieldList['realEnd']['title']    = 'realEnd';
$config->programplan->datatable->fieldList['realEnd']['fixed']    = 'no';
$config->programplan->datatable->fieldList['realEnd']['width']    = '90';
$config->programplan->datatable->fieldList['realEnd']['required'] = 'no';

$config->programplan->datatable->fieldList['actions']['title']    = 'actions';
$config->programplan->datatable->fieldList['actions']['fixed']    = 'right';
$config->programplan->datatable->fieldList['actions']['width']    = '150';
$config->programplan->datatable->fieldList['actions']['required'] = 'yes';
$config->programplan->datatable->fieldList['actions']['sort']     = 'no';
