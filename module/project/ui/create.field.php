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
    ->value($copyProject ? data('copyProject.name') : '');

$fields->field('hasProduct')
        ->control('radioListInline', array('className' => $copyProject ? 'has-warning' : ''))
       ->value($copyProject ? data('copyProject.hasProduct') : '1');

$fields->field('name')
    ->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => $copyProject ? !!data('copyProject.multiple') : true, 'disabled' => !!$copyProject))
    ->className($copyProject ? 'has-warning' : '')
    ->tip($copyProject ? $lang->project->copyProject->nameTips : null)
    ->value($copyProject ? data('copyProject.name') : '');

if($hasCode)
{
    $fields->field('code')
        ->control('input', array('className' => $copyProject ? 'has-warning' : ''))
        ->className($copyProject ? 'has-warning' : '')
        ->tip($copyProject ? $lang->project->copyProject->codeTips : null)
        ->value($copyProject ? data('copyProject.code') : '');
}

$fields->field('days')->control('input', array('className' => $copyProject ? 'has-warning' : ''));

$fields->field('products[]')->hidden($copyProject ? !data('copyProject.hasProduct') : false);

$fields->field('plans[]')->hidden($copyProject ? !data('copyProject.hasProduct') : false);

if($model == 'waterfall' || $model == 'waterfallplus')
{
    $fields->field('stageBy')->className('hidden');
}

$fields->field('acl')->control(array('type' => 'aclBox', 'aclItems' => data('programID') ? $lang->project->subAclList : $lang->project->aclList, 'aclValue' => $copyProject ? data('copyProject.acl') : 'private', 'whitelistLabel' => $lang->project->whitelist));

$fields->field('auth')->value($copyProject ? data('copyProject.auth') : 'extend');
