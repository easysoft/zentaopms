<?php
/**
 * The create view of product of ZenTaoPMS.
 */
namespace zin;


/* zin: Define the form in main content */
$formItems = array();
foreach($fields as $field => $info)
{
    $width = zget($info, 'width', '1/3');

    $control['type'] = $info['control'];
    if($control['type'] == 'select') $control['type'] = 'picker';
    if(!empty($info['options'])) $control['items'] = $info['options'];
    if($info['control'] == 'multi-select')
    {
        $control['type']     = 'picker';
        $control['multiple'] = true;
        $field .= '[]';
    }
    if($info['control'] == 'radio') $control['type'] = 'radioList';

    $formGroup = formGroup
    (
        set::width($width),
        set::name($field),
        set::label($info['title']),
        set::control($control),
        set::value($info['default']),
        set::required($info['required'])
    );
    if($info['control'] == 'hidden') $formGroup = formRow(set::hidden(true), $formGroup);
    $formItems[$field] = $formGroup;
}

formPanel($formItems);

/* ====== Render page ====== */
render();
