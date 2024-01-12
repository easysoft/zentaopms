<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.create', 'bug', '!branch,color');

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->type('picker')->items(data('products'))->value(data('bug.productID'))->itemEnd()
    ->item((data('product.type') !== 'normal' && isset(data('products')[data('bug.productID')])) ? field('branch')->type('picker')->boxClass('flex-none')->width('100px')->name('branch')->items(data('branches'))->value(data('bug.branch')) : null);

$fields->field('title')
    ->width('full')
    ->control('colorInput', array('colorValue' => data('bug.color')));
