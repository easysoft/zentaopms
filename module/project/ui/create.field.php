<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project.create', 'project');

$model       = data('model');
$hasCode     = !empty($config->setCode);
$copyProject = !empty(data('copyProjectID'));
$programID   = $copyProject ? data('copyProject.parent') : data('parentProgram.id');

$fields->field('parent')
    ->control('picker', array('className' => $copyProject ? 'has-warning' : '', 'required' => true))
    ->className($copyProject ? 'has-warning' : '')
    ->value($programID);

$fields->field('hasProduct')
    ->disabled($copyProject)
    ->control('checkBtnGroup', array('className' => $copyProject ? 'has-warning' : ''))
    ->value($copyProject ? data('copyProject.hasProduct') : '1');

if(in_array($model, array('scrum', 'kanban'))) $fields->field('name')->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => $copyProject ? !empty(data('copyProject.multiple')) : true, 'disabled' => $copyProject));
$fields->field('name')
    ->className($copyProject ? 'has-warning' : '')
    ->tip($copyProject ? $lang->project->copyProject->nameTips : null)
    ->tipClass($copyProject ? 'text-warning' : null)
    ->value($copyProject ? data('copyProject.name') : '');
if($copyProject) $fields->field('multiple')->hidden(true)->value(in_array($model, array('scrum', 'kanban')) ? data('copyProject.multiple') : 'on');

if($hasCode)
{
    $fields->field('code')
        ->control('input', array('className' => $copyProject ? 'has-warning' : ''))
        ->className($copyProject ? 'has-warning' : '')
        ->tip($copyProject ? $lang->project->copyProject->codeTips : null)
        ->tipClass($copyProject ? 'text-warning' : null)
        ->value($copyProject ? data('copyProject.code') : '');
}

$fields->field('begin')
    ->tip($copyProject ? $lang->project->copyProject->endTips : ' ')
    ->tipClass('text-warning');
if(!$copyProject || data('copyProject.multiple') != '0') $fields->field('begin')->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => false));

$fields->field('days')
    ->control('input', array('className' => $copyProject ? 'has-warning' : ''))
    ->className($copyProject ? 'has-warning' : '')
    ->tip($copyProject ? $lang->project->copyProject->daysTips : null)
    ->tipClass($copyProject ? 'text-warning' : null);

$fields->field('productsBox')->hidden(data('copyProject') && data('copyProject.hasProduct') == 0);


$fields->field('budget')->foldable();

$fields->field('acl')
       ->foldable()
       ->control(array('control' => 'aclBox', 'aclItems' => !empty($programID) ? $lang->project->subAclList : $lang->project->aclList, 'aclValue' => $copyProject ? data('copyProject.acl') : 'open', 'whitelistLabel' => $lang->project->whitelist, 'userValue' => data('copyProjectID') ? data('copyProject.whitelist') : ''));

$fields->field('auth')->foldable()->value($copyProject ? data('copyProject.auth') : 'extend');

$storyType = in_array($model, array('waterfall', 'waterfallplus', 'ipd')) ? 'story,requirement' : 'story';
$fields->field('storyType')->foldable()->value($storyType);
