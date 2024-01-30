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
        ->hidden(empty(data('fields.program.default')))
        ->control('picker')
        ->items(data('fields.line.options'))
        ->value(data('fields.line.default'));
}

$fields->field('name')->wrapBefore()->required()->control('input');

$fields->field('type')
    ->control(array('type' => 'picker', 'required' => true))
    ->items(data('fields.type.options'))
    ->value(data('fields.type.default'));

$fields->field('code')->control('input');

$fields->field('PO')
    ->control('picker')
    ->items(data('fields.PO.options'))
    ->value(data('fields.PO.default'));

$fields->field('reviewer')
    ->control('picker')
    ->multiple()
    ->items(data('fields.reviewer.options'))
    ->value(data('fields.reviewer.default'));

$fields->field('QD')
    ->wrapBefore()
    ->control('picker')
    ->items(data('fields.QD.options'))
    ->value(data('fields.QD.default'));

$fields->field('RD')
    ->control('picker')
    ->items(data('fields.RD.options'))
    ->value(data('fields.RD.default'));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->width('full')
    ->control(array('type' => 'aclBox', 'aclItems' => data('fields.acl.options'), 'aclValue' => data('fields.acl.default'),'whitelistLabel' => $lang->product->whitelist, 'aclValue' => data('fields.acl.default'), 'userValue' => data('fields.whitelist.default')));
