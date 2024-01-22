<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project.edit', 'project');

$model   = data('model');
$hasCode = !empty($config->setCode);

$fields->field('parent')->disabled(data('disableParent'));

if(in_array($model, array('scrum', 'kanban'))) $fields->field('name')->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => data('project.multiple'), 'disabled' => true));

$fields->field('hasProduct')->disabled(true);

$fields->field('acl')
       ->foldable()
       ->control(array('control' => 'aclBox', 'aclItems' => data('programID') ? $lang->project->subAclList : $lang->project->aclList, 'aclValue' => data('project.acl'), 'whitelistLabel' => $lang->project->whitelist, 'groupLabel' => $lang->product->groups, 'groupItems' => data('groups'), 'groupValue' => data('project.groups'), 'userLabel' => $lang->product->users, 'userValue' => data('project.whitelist')));
