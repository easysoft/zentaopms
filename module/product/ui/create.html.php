<?php
/**
 * The create view of product of ZenTaoPMS.
 */
namespace zin;

/* zin: Define the form in main content */
$formItems = array();
foreach($fields as $field => $attr)
{
    if(empty($attr['control'])) continue;

    $fieldName     = zget($attr, 'name', $field);
    $attr['width'] = zget($attr, 'width', '1/2');

    $control = array();
    $control['type'] = $attr['control'];
    if($control['type'] == 'select') $control['type'] = 'picker';
    if(!empty($attr['options'])) $control['items'] = $attr['options'];
    if($attr['control'] == 'multi-select')
    {
        $control['type']     = 'picker';
        $control['multiple'] = true;
        $fieldName .= '[]';
    }
    if($attr['control'] == 'radio') $control['type'] = 'radioList';

    if($field == 'line' and common::hasPriv('product', 'manageLine') and $programID)
    {
        $formGroup = formGroup
        (
            set::width($attr['width']),
            set::label($attr['title']),
            inputGroup
            (
                select
                (
                    set::name($fieldName),
                    set::items($attr['options']),
                    set::value($attr['default']),
                    set::required($attr['required'])
                ),
                input
                (
                    set::type('text'),
                    set::name('lineName'),
                    set::class('hidden')
                ),
                span
                (
                    set::class('input-group-addon'),
                    checkbox
                    (
                        set::name('newLine'),
                        set::checked(empty($attr['options'])),
                        on::click('toggleLine(e.target)'),
                        set::text($lang->product->newLine)
                    )
                )
            )
        );
    }
    else
    {
        $formGroup = formGroup
        (
            set::width($attr['width']),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required'])
        );
    }

    if($attr['control'] == 'hidden') $formGroup = formRow(set::hidden(true), $formGroup);
    $formItems[$field] = $formGroup;
}

$formItems['program']->add(on::change('setParentProgram(e.target);'));
$formItems['acl']->add(on::change('setWhite(e.target);'));
$formItems['whitelist'] = formRow(set::id('whitelistBox'), $formItems['whitelist']);
if(empty($programID)) $formItems['line'] = formRow(set::hidden(true), $formItems['line']);

formPanel($formItems);

/* ====== Render page ====== */
render();
