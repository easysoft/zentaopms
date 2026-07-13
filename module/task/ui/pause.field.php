<?php
namespace zin;
global $lang;

$fields = defineFieldList('task.pause');

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->rows('5')
    ->control('editor');
$fields->field('status')->control('hidden')->value('pause');
