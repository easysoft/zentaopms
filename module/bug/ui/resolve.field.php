<?php
namespace zin;

global $lang, $app;

$app->loadLang('build');

$ajaxGetduplicateBugLink = createLink('bug', 'ajaxGetProductBugs', "projectID=&bugID=" . data('bug.id') . "&search={search}");

$fields = defineFieldList('bug.resolve');

$fields->field('resolution')->width('1/3')->required(true)->control('picker')->items($lang->bug->resolutionList);
$fields->field('duplicateBug')
    ->id('duplicateBugBox')
    ->width('1/3')
    ->control(array('control' => 'picker', 'popWidth' => 'auto', 'popMaxWidth' => 600, 'popMinWidth' => '100%', 'items' => $ajaxGetduplicateBugLink))
    ->placeholder($lang->bug->placeholder->duplicate)
    ->value('');

$fields->field('resolvedBuild')
    ->width('1/3')
    ->control('inputGroup')
    ->itemBegin('resolvedBuild')->control('picker')->items(data('builds'))->itemEnd()
    ->itemBegin('createBuild')->control(array('control' => 'checkbox', 'rootClass' => 'items-center absolute', 'rootStyle' => array('top' => '5px', 'right' => '-60px'), 'text' => $lang->bug->createBuild))->itemEnd();

$fields->field('newBuildBox')
    ->required(true)
    ->width('1/3')
    ->label(data('execution') && data('execution.type') == 'kanban' ?  $lang->bug->kanban : $lang->build->executionAB)
    ->control('inputGroup')
    ->itemBegin('buildExecution')
    ->control(array('control' => 'picker', 'items' => data('executions'), 'value' => data('bug.execution')))
    ->itemEnd()
    ->itemBegin('buildName')
    ->control(array('control' => 'formGroup', 'label' => $lang->bug->resolvedBuild, 'required' => true, 'style' => array('position' => 'absolute', 'right' => '-270px')))
    ->itemEnd()
    ->itemBegin('createBuild')->control(array('control' => 'checkbox', 'rootClass' => 'items-center absolute', 'rootStyle' => array('top' => '5px', 'right' => '-330px'), 'text' => $lang->bug->createBuild))->itemEnd();

$fields->field('resolvedDate')->width('1/3')->control('datetimePicker')->value(helper::now());
$fields->field('assignedTo')->width('1/3')->control('picker')->items(data('users'))->value(data('assignedTo'));
$fields->field('files')->width('full')->control('fileSelector');
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
