<?php
declare(strict_types=1);
namespace zin;

set::title($title);
jsVar('dashboard', $dashboard);

$paramsRows = array();
$param      = $params['type'];

foreach($params as $code => $row)
{
    $paramsRows[] = formGroup
    (
        set::label($row['name']),
        set::name('params'),
        set::class('form-row'),
        set::control(array
        (
            'type'  => $row['control'],
            'items' => isset($row['options']) ? $row['options'] : null
        ))  
    );
    if($code == 'type')
    {
        $paramsRows[] = formGroup
        (
            set::label($lang->block->name),
            set::name('title'),
            set::class('form-row'),
            set::control('input')  
        );

        $paramsRows[] = formGroup
        (
            set::label($lang->block->grid),
            set::name("grid"),
            set::class('form-row'),
            set::control(array
            (
                'type'  => 'select',
                'items' => $lang->block->gridOptions
            ))  
        );
    }
}

form
(
    on::change('#module', 'getForm'),
    on::change('#block', 'getForm'),
    formGroup
    (
        set::label($lang->block->lblModule),
        set::name('module'),
        set::control(array
        (
            'type'  => 'select',
            'items' => $modules
        ))  
    ),
    div
    (
        set::id('blockRow'),
        set::class('form-row'),
        $blocks
        ? formGroup
        (
            set::label($lang->block->lblBlock),
            set::name('block'),
            set::value($block),
            set::control(array
            (
                'type'  => 'select',
                'items' => array('') + $blocks
            ))  
        ) : null
    ),
    div
    (
        set::id('paramsRow'),
        set::class('form-grid'),
        $paramsRows
    )
);

render('modalDialog');
