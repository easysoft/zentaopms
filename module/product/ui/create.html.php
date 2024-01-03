<?php
/**
 * The create view of product of ZenTaoPMS.
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
    if($attr['control'] == 'select') $control['type'] = 'picker';
    if($attr['control'] == 'radio')  $control['type'] = 'radioList';
    if($attr['control'] == 'multi-select')
    {
        $control['type']     = 'picker';
        $control['multiple'] = true;
        $fieldName          .= '[]';
    }

    if(isset($attr['options'])) $control['items'] = $attr['options'];

    if($field == 'line' && hasPriv('product', 'manageLine') && $programID)
    {
        $formGroup = formGroup
        (
            set::width($attr['width']),
            set::label($attr['title']),
            inputGroup
            (
                picker
                (
                    set::id($fieldName),
                    set::name($fieldName),
                    set::items($attr['options']),
                    set::value($attr['default']),
                    set::required($attr['required'])
                ),
                input
                (
                    set::type('text'),
                    set::name('lineName'),
                    set::className('hidden')
                ),
                span
                (
                    set::className('input-group-addon'),
                    checkbox
                    (
                        set::name('newLine'),
                        set::checked(empty($attr['options'])),
                        set::text($lang->product->newLine)
                    )
                )
            )
        );
    }
    elseif($field != 'line')
    {
        $formGroup = formGroup
        (
            set::id($fieldName),
            set::width($attr['width']),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            $attr['required'] ? '' : set::value($attr['default']),
            set::required($attr['required'])
        );
    }

    if($attr['control'] == 'hidden') $formGroup = formRow(set::hidden(true), $formGroup);
    $formItems[$field] = $formGroup;
}

if(empty($programID) and isset($formItems['line'])) $formItems['line'] = formRow(set::hidden(true), $formItems['line']);

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
                whitelist
                (
                    set::label($lang->product->users),
                    set::id($usersField['name']),
                    set::name($usersField['name'] . '[]'),
                    set::items($usersField['options']),
                    set::value($usersField['default']),
                )
            )
        )
    )
);

formPanel
(
    set::title($title),
    on::change('#program', 'setParentProgram'),
    on::change('#acl', 'setWhite(e.target)'),
    on::click('[name=newLine]', 'toggleLine(e.target)'),
    $formItems
);

/* ====== Render page ====== */
render();
