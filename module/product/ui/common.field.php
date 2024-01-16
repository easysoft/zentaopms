<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('product');

$fields->field('program')
    ->control('picker')
    ->items(data('fields.program.options'))
    ->value(data('fields.program.default'));

$fields->field('type')
    ->control(array('type' => 'picker', 'required' => true))
    ->items(data('fields.type.options'))
    ->value(data('fields.type.default'));

$fields->field('name')->control('input');

if(!empty($config->setCode)) $fields->field('code')->foldable()->control('input');

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
    ->foldable()
    ->control('picker')
    ->items(data('fields.QD.options'))
    ->value(data('fields.QD.default'));

$fields->field('RD')
    ->foldable()
    ->control('picker')
    ->items(data('fields.RD.options'))
    ->value(data('fields.RD.default'));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('acl')
    ->foldable()
    ->width('full')
    ->control(array('type' => 'aclBox', 'aclItems' => data('fields.acl.options'), 'whitelistLabel' => $lang->product->whitelist, 'groupLabel' => $lang->product->groups, 'groupItems' => data('fields.groups.options'), 'userLabel' => $lang->product->users, 'aclValue' => data('fields.acl.default'), 'groupValue' => data('fields.groups.default'), 'userValue' => data('fields.whitelist.default')));
