<?php
$config->productplan->dtable = new stdclass();

$config->productplan->dtable->fieldList['id']['name']     = 'id';
$config->productplan->dtable->fieldList['id']['title']    = $lang->idAB;
$config->productplan->dtable->fieldList['id']['type']     = 'checkID';
$config->productplan->dtable->fieldList['id']['fixed']    = 'left';
$config->productplan->dtable->fieldList['id']['checkbox'] = true;
$config->productplan->dtable->fieldList['id']['sortType'] = true;
$config->productplan->dtable->fieldList['id']['align']    = 'left';
$config->productplan->dtable->fieldList['id']['group']    = 'g1';

$config->productplan->dtable->fieldList['title']['name']         = 'title';
$config->productplan->dtable->fieldList['title']['title']        = $lang->productplan->title;
$config->productplan->dtable->fieldList['title']['type']         = 'title';
$config->productplan->dtable->fieldList['title']['link']         = helper::createLink('productplan', 'view', 'planID={id}');
$config->productplan->dtable->fieldList['title']['fixed']        = 'left';
$config->productplan->dtable->fieldList['title']['sortType']     = true;
$config->productplan->dtable->fieldList['title']['align']        = 'left';
$config->productplan->dtable->fieldList['title']['nestedToggle'] = true;
$config->productplan->dtable->fieldList['title']['group']        = 'g1';

$config->productplan->dtable->fieldList['status']['name']      = 'status';
$config->productplan->dtable->fieldList['status']['title']     = $lang->productplan->status;
$config->productplan->dtable->fieldList['status']['type']      = 'status';
$config->productplan->dtable->fieldList['status']['sortType']  = true;
$config->productplan->dtable->fieldList['status']['align']     = 'left';
$config->productplan->dtable->fieldList['status']['statusMap'] = $lang->productplan->statusList;
$config->productplan->dtable->fieldList['status']['group']     = 'g2';
$config->productplan->dtable->fieldList['status']['show']      = true;

$config->productplan->dtable->fieldList['branch']['name']     = 'branch';
$config->productplan->dtable->fieldList['branch']['title']    = '';
$config->productplan->dtable->fieldList['branch']['type']     = 'text';
$config->productplan->dtable->fieldList['branch']['sortType'] = true;
$config->productplan->dtable->fieldList['branch']['group']    = 'g3';
$config->productplan->dtable->fieldList['branch']['show']     = true;

$config->productplan->dtable->fieldList['begin']['name']     = 'begin';
$config->productplan->dtable->fieldList['begin']['title']    = $lang->productplan->begin;
$config->productplan->dtable->fieldList['begin']['type']     = 'date';
$config->productplan->dtable->fieldList['begin']['sortType'] = true;
$config->productplan->dtable->fieldList['begin']['group']    = 'g4';
$config->productplan->dtable->fieldList['begin']['show']     = true;

$config->productplan->dtable->fieldList['end']['name']     = 'end';
$config->productplan->dtable->fieldList['end']['title']    = $lang->productplan->end;
$config->productplan->dtable->fieldList['end']['type']     = 'date';
$config->productplan->dtable->fieldList['end']['sortType'] = true;
$config->productplan->dtable->fieldList['end']['group']    = 'g4';
$config->productplan->dtable->fieldList['end']['show']     = true;

$config->productplan->dtable->fieldList['stories']['name']     = 'stories';
$config->productplan->dtable->fieldList['stories']['title']    = $lang->productplan->stories;
$config->productplan->dtable->fieldList['stories']['type']     = 'number';
$config->productplan->dtable->fieldList['stories']['sortType'] = false;
$config->productplan->dtable->fieldList['stories']['width']    = 84;
$config->productplan->dtable->fieldList['stories']['group']    = 'g5';
$config->productplan->dtable->fieldList['stories']['show']     = true;

$config->productplan->dtable->fieldList['bugs']['name']     = 'bugs';
$config->productplan->dtable->fieldList['bugs']['title']    = $lang->productplan->bugs;
$config->productplan->dtable->fieldList['bugs']['type']     = 'number';
$config->productplan->dtable->fieldList['bugs']['sortType'] = false;
$config->productplan->dtable->fieldList['bugs']['group']    = 'g5';
$config->productplan->dtable->fieldList['bugs']['show']     = true;

$config->productplan->dtable->fieldList['hour']['name']     = 'hour';
$config->productplan->dtable->fieldList['hour']['title']    = $lang->productplan->hour;
$config->productplan->dtable->fieldList['hour']['type']     = 'number';
$config->productplan->dtable->fieldList['hour']['sortType'] = false;
$config->productplan->dtable->fieldList['hour']['group']    = 'g5';
$config->productplan->dtable->fieldList['hour']['show']     = true;

$config->productplan->dtable->fieldList['execution']['name']     = 'execution';
$config->productplan->dtable->fieldList['execution']['title']    = $lang->productplan->execution;
$config->productplan->dtable->fieldList['execution']['type']     = 'number';
$config->productplan->dtable->fieldList['execution']['sortType'] = false;
$config->productplan->dtable->fieldList['execution']['group']    = 'g6';
$config->productplan->dtable->fieldList['execution']['show']     = true;

$config->productplan->dtable->fieldList['desc']['name']     = 'desc';
$config->productplan->dtable->fieldList['desc']['title']    = $lang->productplan->desc;
$config->productplan->dtable->fieldList['desc']['type']     = 'text';
$config->productplan->dtable->fieldList['desc']['sortType'] = false;
$config->productplan->dtable->fieldList['desc']['group']    = 'g7';
$config->productplan->dtable->fieldList['desc']['show']     = true;

$config->productplan->dtable->fieldList['actions']['name']     = 'actions';
$config->productplan->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->dtable->fieldList['actions']['fixed']    = 'right';
$config->productplan->dtable->fieldList['actions']['required'] = true;
$config->productplan->dtable->fieldList['actions']['width']    = 'auto';
$config->productplan->dtable->fieldList['actions']['type']     = 'actions';
$config->productplan->dtable->fieldList['actions']['minWidth'] = 200;

$config->productplan->dtable->fieldList['actions']['actionsMap']['start']['icon'] = 'play';
$config->productplan->dtable->fieldList['actions']['actionsMap']['start']['hint'] = $this->lang->productplan->startAB;
$config->productplan->dtable->fieldList['actions']['actionsMap']['start']['url']  = 'javascript:startProductPlan("{planID}")';

$config->productplan->dtable->fieldList['actions']['actionsMap']['finish']['icon'] = 'checked';
$config->productplan->dtable->fieldList['actions']['actionsMap']['finish']['hint'] = $this->lang->productplan->finishAB;
$config->productplan->dtable->fieldList['actions']['actionsMap']['finish']['url']  = 'javascript:finishProductPlan("{planID}")';

$config->productplan->dtable->fieldList['actions']['actionsMap']['close']['icon']        = 'off';
$config->productplan->dtable->fieldList['actions']['actionsMap']['close']['hint']        = $this->lang->productplan->closeAB;
$config->productplan->dtable->fieldList['actions']['actionsMap']['close']['data-toggle'] = 'modal';

$config->productplan->dtable->fieldList['actions']['actionsMap']['activate']['icon'] = 'active';
$config->productplan->dtable->fieldList['actions']['actionsMap']['activate']['hint'] = $this->lang->productplan->activateAB;
$config->productplan->dtable->fieldList['actions']['actionsMap']['activate']['url']  = 'javascript:activateProductPlan("{planID}")';

$config->productplan->dtable->fieldList['actions']['actionsMap']['plus']['icon'] = 'plus';

$config->productplan->dtable->fieldList['actions']['actionsMap']['link']['icon'] = 'link';
$config->productplan->dtable->fieldList['actions']['actionsMap']['link']['hint'] = $this->lang->productplan->linkStory;

$config->productplan->dtable->fieldList['actions']['actionsMap']['bug']['icon'] = 'bug';
$config->productplan->dtable->fieldList['actions']['actionsMap']['bug']['hint'] = $this->lang->productplan->linkBug;

$config->productplan->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';

$config->productplan->dtable->fieldList['actions']['actionsMap']['split']['icon'] = 'split';
$config->productplan->dtable->fieldList['actions']['actionsMap']['split']['hint'] = $lang->productplan->createChildren;
$config->productplan->dtable->fieldList['actions']['actionsMap']['split']['text'] = $lang->productplan->createChildren;

$config->productplan->dtable->fieldList['actions']['actionsMap']['trash']['icon'] = 'trash';
$config->productplan->dtable->fieldList['actions']['actionsMap']['trash']['url']  = 'javascript:deleteProductPlan("{planID}")';
$config->productplan->dtable->fieldList['actions']['actionsMap']['delete']['url'] = 'javascript:deleteProductPlan("{planID}")';
