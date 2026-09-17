<?php
namespace zin;
global $lang;

$fields = defineFieldList('task.cancel');

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->rows('5')
    ->control('editor');
$fields->field('status')->control('hidden')->value('cancel');