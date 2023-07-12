<?php
/**
 * The edit view of product of ZenTaoPMS.
 */
namespace zin;

$defaultWidth = '1/2';
$groupsField  = $fields['groups'];
$usersField   = $fields['whitelist'];
unset($fields['groups'], $fields['whitelist']);

/* zin: Define the form in main content */
$formItems = array();
foreach($fields as $field => $attr)
{
    if(empty($attr['control'])) continue;

    $fieldName     = zget($attr, 'name', $field);
    $attr['width'] = zget($attr, 'width', $defaultWidth);

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

if(isset($formItems['program'])) $formItems['program']->add(on::change('toggleLineByProgram();'));
$formItems['acl']->add(on::change('setWhite(e.target);'));
$formItems['line'] = formRow(set::id('lineBox'), $formItems['line']);

/* Set whitelist box. */
$formItems['whitelist'] = formRow
(
    set::id('whitelistBox'),
    formGroup
    (
        set::width('full'),
        set::label($lang->product->whitelist),
        div
        (
            setClass('w-full check-list'),
            div
            (
                inputGroup
                (
                    $lang->product->groups,
                    picker
                    (
                        set::name($groupsField['name'] . '[]'),
                        set::multiple(true),
                        set::items($groupsField['options']),
                        set::value($groupsField['default'])
                    )
                )
            ),
            div
            (
                inputGroup
                (
                    $lang->product->users,
                    picker
                    (
                        set::name($usersField['name'] . '[]'),
                        set::multiple(true),
                        set::items($usersField['options']),
                        set::value($usersField['default'])
                    )
                )
            )
        )
    )
);

formPanel($formItems);

/* ====== Render page ====== */
render();
