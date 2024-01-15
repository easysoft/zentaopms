<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project');

$hasCode     = !empty($config->setCode);
$copyProject = !!data('copyProjectID');

$fields->field('parent')
    ->required()
    ->labelHint($lang->program->tips)
    ->hidden(data('globalDisableProgram'))
    ->items(data('programList'));

$fields->field('model')->control('hidden')->value(data('model'));

if(!$hasCode)
{
    $fields->field('hasProduct')
        ->label($lang->project->category)
        ->control('radioListInline')
        ->items($lang->project->projectTypeList);
}

$fields->field('name')
    ->wrapBefore()
    ->required()
    ->control('colorInput');

if($hasCode)
{
    $fields->field('hasProduct')
        ->control('radioListInline')
        ->label($lang->project->category)
        ->items($lang->project->projectTypeList);

    $fields->field('code')->required();
}

$fields->field('PM')->items(data('pmUsers'));

$fields->field('begin')
    ->required()
    ->control('inputGroup')
    ->itemBegin('begin')->control('datePicker')->placeholder($lang->project->begin)->value(date('Y-m-d'))->required(true)->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('end')->control('datePicker')->placeholder($lang->project->end)->required(true)->itemEnd();

$fields->field('days')
    ->label($lang->project->days . $lang->project->daysUnit);

$fields->field('products[]')
    ->wrapBefore()
    ->setClass('className', 'productBox')
    ->hidden($copyProject ? !data('copyProject.hasProduct') : false)
    ->checkbox(array('text' => $lang->project->newProduct, 'name' => 'newProduct', 'checked' => false))
    ->items(data('allProducts'))
    ->label($lang->project->manageProducts);

$fields->field('plans[]')
    ->setClass('className', 'productBox')
    ->hidden($copyProject ? !data('copyProject.hasProduct') : false)
    ->items(array())
    ->label($lang->project->managePlans);

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('budget')
    ->label($lang->project->budget . $lang->project->budgetUnit)
    ->foldable()
    ->checkbox(array('text' => $lang->project->future, 'name' => 'future', 'checked' => false));

$fields->field('acl')
    ->width('full')
    ->foldable()
    ->wrapBefore()
    ->control('radioList')
    ->items(data('programID') ? $lang->project->subAclList : $lang->project->aclList);

$fields->field('auth')
    ->width('full')
    ->foldable()
    ->wrapBefore()
    ->control('radioList')
    ->items($lang->project->authList);
