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
    if($attr['control'] == 'select') $control['type']  = 'picker';
    if($attr['control'] == 'radio')  $control['type']  = 'radioList';
    if(isset($attr['options']))      $control['items'] = $attr['options'];
    if($attr['control'] == 'multi-select')
    {
        $control['type']     = 'picker';
        $control['multiple'] = true;
        $fieldName          .= '[]';
    }

    if($control['type'] == 'picker')
    {
        $formGroup = formGroup
        (
            set::width($attr['width']),
            set::label($attr['title']),
            set::required($attr['required']),
            picker
            (
                set::id(zget($attr, 'name', $field)),
                set::items($control['items']),
                set::name($fieldName),
                set::value($attr['default']),
                !empty($control['multiple']) ? set::multiple(true) : null
            )
        );
    }
    elseif($control['type'] == 'editor')
    {
        $formGroup = formGroup
        (
            set::width($attr['width']),
            set::label($attr['title']),
            set::required($attr['required']),
            editor
            (
                set::name($fieldName),
                html($attr['default'])
            )
        );
    }
    else
    {
        $formGroup = formGroup
        (
            set::id(zget($attr, 'name', $field)),
            set::width($attr['width']),
            set::name($fieldName),
            set::label($attr['title'] ?? null),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required'])
        );
    }

    if($attr['control'] == 'hidden') $formGroup = formRow(set::hidden(true), $formGroup);
    $formItems[$field] = $formGroup;
}

$formItems['line'] = formRow
(
    set::id('lineBox'),
    (isset($fields['program']) && $fields['program']['default'] == 0) ? set::hidden(true) : null,
    $formItems['line']
);

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
                        set::id($groupsField['name']),
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
                    whitelist
                    (
                        set::id($usersField['name']),
                        set::name($usersField['name'] . '[]'),
                        set::items($usersField['options']),
                        set::value($usersField['default'])
                    )
                )
            )
        )
    )
);

formPanel
(
    on::change('#program', 'toggleLineByProgram(e.target)'),
    on::change('#acl', 'setWhite(e.target)'),
    $formItems
);

/* ====== Render page ====== */
render();
