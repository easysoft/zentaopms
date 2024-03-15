<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project.create', 'project');

$model       = data('model');
$hasCode     = !empty($config->setCode);
$copyProject = !!data('copyProjectID');

$fields->field('parent')
    ->control('picker', array('className' => $copyProject ? 'has-warning' : '', 'required' => true))
    ->className($copyProject ? 'has-warning' : '')
    ->value($copyProject ? data('copyProject.parent') : data('parentProgram.id'));

$fields->field('hasProduct')
    ->control('checkBtnGroup', array('className' => $copyProject ? 'has-warning' : ''))
    ->value($copyProject ? data('copyProject.hasProduct') : '1');

if(in_array($model, array('scrum', 'kanban'))) $fields->field('name')->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => $copyProject ? !!data('copyProject.multiple') : true, 'disabled' => !!$copyProject));
$fields->field('name')
    ->className($copyProject ? 'has-warning' : '')
    ->tip($copyProject ? $lang->project->copyProject->nameTips : null)
    ->value($copyProject ? data('copyProject.name') : '');
if(!!$copyProject) $fields->field('multiple')->hidden(true)->value(data('copyProject.multiple'));

if($hasCode)
{
    $fields->field('code')
        ->control('input', array('className' => $copyProject ? 'has-warning' : ''))
        ->className($copyProject ? 'has-warning' : '')
        ->tip($copyProject ? $lang->project->copyProject->codeTips : null)
        ->value($copyProject ? data('copyProject.code') : '');
}

$fields->field('begin')
    ->tip($copyProject ? $lang->project->copyProject->endTips : null)
    ->tipClass('text-warning');
if(!$copyProject || data('copyProject.multiple') != '0') $fields->field('begin')->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => false));

$fields->field('days')
    ->control('input', array('className' => $copyProject ? 'has-warning' : ''))
    ->className($copyProject ? 'has-warning' : '')
    ->tip($copyProject ? $lang->project->copyProject->daysTips : null);

$fields->field('productsBox')->hidden(data('copyProject') && data('copyProject.hasProduct') == 0);


$fields->field('budget')->foldable();

$fields->field('acl')
       ->foldable()
       ->control(array('control' => 'aclBox', 'aclItems' => data('programID') ? $lang->project->subAclList : $lang->project->aclList, 'aclValue' => $copyProject ? data('copyProject.acl') : 'open', 'whitelistLabel' => $lang->project->whitelist, 'userValue' => data('copyProjectID') ? data('copyProject.whitelist') : ''));

$fields->field('auth')->foldable()->value($copyProject ? data('copyProject.auth') : 'extend');
