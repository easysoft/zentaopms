<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product');

if($config->systemMode != 'light')
{
    $fields->field('program')
        ->control('picker')
        ->items(data('fields.program.options'))
        ->value(data('fields.program.default'));

    $fields->field('line')
        ->control('picker')
        ->items(data('fields.line.options'))
        ->value(data('fields.line.default'));
}

$fields->field('name')->wrapBefore()->required()->control('input');

$fields->field('type')
    ->control(array('control' => 'picker', 'required' => true))
    ->items(data('fields.type.options'))
    ->value(data('fields.type.default'));

$fields->field('code')->control('input');

$fields->field('PO')
    ->control(array('control' => 'remotepicker', 'params' => 'nodeleted|pofirst|noclosed'))
    ->value(data('fields.PO.default'));

$fields->field('reviewer')
    ->control('remotepicker')
    ->multiple()
    ->value(data('fields.reviewer.default'));

$fields->field('QD')
    ->wrapBefore()
    ->control(array('control' => 'remotepicker', 'params' => 'nodeleted|qdfirst|noclosed'))
    ->value(data('fields.QD.default'));

$fields->field('RD')
    ->control(array('control' => 'remotepicker', 'params' => 'nodeleted|devfirst|noclosed'))
    ->value(data('fields.RD.default'));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control(array('control' => 'aclBox', 'aclItems' => data('fields.acl.options'), 'aclValue' => data('fields.acl.default'),'whitelistLabel' => $lang->product->whitelist, 'aclValue' => data('fields.acl.default'), 'userValue' => data('fields.whitelist.default')));
