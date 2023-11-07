<?php
global $lang, $app;

$config->kanban->dtable = new stdclass();
$config->kanban->dtable->card = new stdclass();
$config->kanban->dtable->card->fieldList['id']['name']  = 'id';
$config->kanban->dtable->card->fieldList['id']['title'] = $lang->idAB;
$config->kanban->dtable->card->fieldList['id']['type']  = 'checkID';
$config->kanban->dtable->card->fieldList['id']['fixed'] = 'left';
$config->kanban->dtable->card->fieldList['id']['group'] = 1;

$config->kanban->dtable->card->fieldList['title']['name']  = 'kanban';
$config->kanban->dtable->card->fieldList['title']['title'] = $lang->kanban->name;
$config->kanban->dtable->card->fieldList['title']['type']  = 'title';
$config->kanban->dtable->card->fieldList['title']['fixed'] = 'left';
$config->kanban->dtable->card->fieldList['title']['group'] = 1;

$config->kanban->dtable->card->fieldList['card']['name']  = 'name';
$config->kanban->dtable->card->fieldList['card']['title'] = $lang->kanbancard->name;
$config->kanban->dtable->card->fieldList['card']['type']  = 'title';
$config->kanban->dtable->card->fieldList['card']['fixed'] = 'left';
$config->kanban->dtable->card->fieldList['card']['group'] = 1;

$config->kanban->dtable->card->fieldList['pri']['name']  = 'pri';
$config->kanban->dtable->card->fieldList['pri']['title'] = $lang->kanbancard->pri;
$config->kanban->dtable->card->fieldList['pri']['type']  = 'pri';
$config->kanban->dtable->card->fieldList['pri']['group'] = 2;

$config->kanban->dtable->card->fieldList['assignedTo']['name']  = 'assignedTo';
$config->kanban->dtable->card->fieldList['assignedTo']['title'] = $lang->kanbancard->assignedTo;
$config->kanban->dtable->card->fieldList['assignedTo']['type']  = 'user';
$config->kanban->dtable->card->fieldList['assignedTo']['group'] = 3;

$config->kanban->dtable->card->fieldList['begin']['name']  = 'begin';
$config->kanban->dtable->card->fieldList['begin']['title'] = $lang->kanbancard->begin;
$config->kanban->dtable->card->fieldList['begin']['type']  = 'date';
$config->kanban->dtable->card->fieldList['begin']['group'] = 4;

$config->kanban->dtable->card->fieldList['end']['name']  = 'end';
$config->kanban->dtable->card->fieldList['end']['title'] = $lang->kanbancard->end;
$config->kanban->dtable->card->fieldList['end']['type']  = 'date';
$config->kanban->dtable->card->fieldList['end']['group'] = 4;

$config->kanban->dtable->plan = new stdclass();
$config->kanban->dtable->plan->fieldList['id']['name']  = 'id';
$config->kanban->dtable->plan->fieldList['id']['title'] = $lang->idAB;
$config->kanban->dtable->plan->fieldList['id']['type']  = 'checkID';
$config->kanban->dtable->plan->fieldList['id']['fixed'] = 'left';
$config->kanban->dtable->plan->fieldList['id']['group'] = 1;

$config->kanban->dtable->plan->fieldList['title']['name']  = 'kanban';
$config->kanban->dtable->plan->fieldList['title']['title'] = $lang->kanban->name;
$config->kanban->dtable->plan->fieldList['title']['type']  = 'title';
$config->kanban->dtable->plan->fieldList['title']['fixed'] = 'left';
$config->kanban->dtable->plan->fieldList['title']['group'] = 1;

$config->kanban->dtable->plan->fieldList['card']['name']  = 'name';
$config->kanban->dtable->plan->fieldList['card']['title'] = $lang->kanbancard->name;
$config->kanban->dtable->plan->fieldList['card']['type']  = 'title';
$config->kanban->dtable->plan->fieldList['card']['fixed'] = 'left';
$config->kanban->dtable->plan->fieldList['card']['group'] = 1;

$config->kanban->dtable->plan->fieldList['pri']['name']  = 'pri';
$config->kanban->dtable->plan->fieldList['pri']['title'] = $lang->kanbancard->pri;
$config->kanban->dtable->plan->fieldList['pri']['type']  = 'pri';
$config->kanban->dtable->plan->fieldList['pri']['group'] = 2;

$config->kanban->dtable->plan->fieldList['assignedTo']['name']  = 'assignedTo';
$config->kanban->dtable->plan->fieldList['assignedTo']['title'] = $lang->kanbancard->assignedTo;
$config->kanban->dtable->plan->fieldList['assignedTo']['type']  = 'text';
$config->kanban->dtable->plan->fieldList['assignedTo']['group'] = 3;

$config->kanban->dtable->plan->fieldList['begin']['name']  = 'begin';
$config->kanban->dtable->plan->fieldList['begin']['title'] = $lang->kanbancard->begin;
$config->kanban->dtable->plan->fieldList['begin']['type']  = 'date';
$config->kanban->dtable->plan->fieldList['begin']['group'] = 4;

$config->kanban->dtable->plan->fieldList['end']['name']  = 'end';
$config->kanban->dtable->plan->fieldList['end']['title'] = $lang->kanbancard->end;
$config->kanban->dtable->plan->fieldList['end']['type']  = 'date';
$config->kanban->dtable->plan->fieldList['end']['group'] = 4;
