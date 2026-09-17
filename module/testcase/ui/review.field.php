<?php
namespace zin;

global $lang, $app;

$fields = defineFieldList('testcase.review');

$fields->field('reviewedDate')->width('1/3')->control('datePicker')->value(helper::today());
$fields->field('result')->width('1/3')->control(array('control' => 'picker', 'required' => true, 'items' => $lang->testcase->reviewResultList));
$fields->field('reviewedBy')->width('full')->control(array('control' => 'picker', 'items' => data('users'), 'multiple' => true))->value($app->user->account);
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
