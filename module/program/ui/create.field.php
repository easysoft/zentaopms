<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('program.create', 'program');

$fields->field('acl')
    ->items(data('parentProgram') ? $lang->program->subAclList : $lang->program->aclList)
    ->value('open');
