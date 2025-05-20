<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project.edit', 'project');

$model   = data('model');
$hasCode = !empty($config->setCode);

$fields->field('parent')->disabled(data('disableParent'));

if(in_array($model, array('scrum', 'kanban'))) $fields->field('name')->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => data('project.multiple') == '1', 'disabled' => true));

$fields->field('hasProduct')->disabled(true);

if(data('project.multiple') != '0') $fields->field('begin')->checkbox(array('text' => $lang->project->longTime, 'name' => 'longTime', 'checked' => data('project.end') == LONG_TIME));

$budgetFuture = data('project.budget') !== null && !data('project.budget');
if(strpos($config->project->edit->requiredFields, 'budget') === false) $fields->field('budget')->checkbox(array('text' => $lang->project->future, 'name' => 'future', 'checked' => $budgetFuture));
$fields->field('budget')->value(data('project.budget') !== null && data('project.budget') == 0 ? '' : data('project.budget'));

$fields->field('acl')->control(array('control' => 'aclBox', 'aclItems' => data('project.parent') ? $lang->project->subAclList : $lang->project->aclList, 'aclValue' => data('project.acl'), 'whitelistLabel' => $lang->project->whitelist, 'userValue' => data('project.whitelist')));
$fields->field('taskDateLimit')->width('full')->value(data('project.taskDateLimit'));
$fields->field('storyType')->width('full')->value(data('project.storyType'));