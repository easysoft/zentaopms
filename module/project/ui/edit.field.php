<?php
declare(strict_types=1);
namespace zin;
global $lang, $config;

$fields = defineFieldList('project.edit', 'project');

$hasCode = !empty($config->setCode);

$fields->field('parent')->disabled(data('disableParent'));

$fields->field('name')->checkbox(array('text' => $lang->project->multiple, 'name' => 'multiple', 'checked' => data('project.multiple'), 'disabled' => true));

$fields->field('hasProduct')->disabled(true);
