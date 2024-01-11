<?php
namespace zin;

global $lang;

$bugFields = defineFieldList('bug.create', 'bug', '!branch,color');

defineField('product')
    ->hidden(data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->type('picker')->items(data('products'))->value(data('bug.productID'))->itemEnd()
    ->item((data('product.type') !== 'normal' && isset(data('products')[data('bug.productID')])) ? field('branch')->type('picker')->boxClass('flex-none')->width('100px')->name('branch')->items(data('branches'))->value(data('bug.branch')) : null);

defineField('title')
    ->width('full')
    ->control('colorInput', array('colorValue' => data('bug.color')));
