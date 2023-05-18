<?php
/**
 * The edit view of product of ZenTaoPMS.
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
    if($attr['control'] == 'select')$control['type']  = 'picker';
    if($attr['control'] == 'radio') $control['type']  = 'radioList';
    if(!empty($attr['options']))    $control['items'] = $attr['options'];
    if($attr['control'] == 'multi-select')
    {
        $control['type']     = 'picker';
        $control['multiple'] = true;
        $fieldName          .= '[]';
    }

    $formGroup = formGroup
    (
        set::width($attr['width']),
        set::name($fieldName),
        set::label($attr['title']),
        set::control($control),
        set::value($attr['default']),
        set::required($attr['required'])
    );

    if($attr['control'] == 'hidden') $formGroup = formRow(set::hidden(true), $formGroup);
    $formItems[$field] = $formGroup;
}

$formItems['program']->add(on::change('toggleLineByProgram();'));
$formItems['acl']->add(on::change('setWhite(e.target);'));
$formItems['whitelist'] = formRow(set::id('whitelistBox'), $formItems['whitelist']);
$formItems['line']      = formRow(set::id('lineBox'), $formItems['line']);

formPanel($formItems);

/* ====== Render page ====== */
render();
