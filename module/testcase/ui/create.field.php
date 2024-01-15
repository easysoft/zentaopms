<?php
namespace zin;

global $lang;

$fields = defineFieldList('testcase.create', 'testcase', '!branch,color');

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items(data('products'))->required(true)->value(empty(data('case.product')) ? data('productID') : data('case.product'))->itemEnd()
    ->item(data('product.type') == 'normal' ? null : field('branch')->control('picker')->width('100px')->items(data('branches'))->value(data('branch')));

$fields->field('title')
    ->width('5/6')
    ->control('colorInput', array('colorValue' => data('case.color')))
    ->checkbox(data('needReview') ? array('name' => 'needReview', 'text' => $lang->testcase->forceReview, 'checked' => true) : null);
