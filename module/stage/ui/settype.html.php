<?php
declare(strict_types=1);
/**
 * The set type view file of stage module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     stage
 * @link        https://www.zentao.net
 */
namespace zin;

$formItems[] = formRow
(
    set::width('1/3'),
    formGroup
    (
        set::label($lang->custom->key)
    ),
    formGroup
    (
        set::label($lang->custom->value)
    ),
    formGroup()
);

div
(
    setClass('hidden'),
    setID('stageFieldRow'),

    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label('addRow'),
            set::name('values[]')
        ),
        div
        (
            setClass('pl-2 flex self-center'),
            btn
            (
                setClass('btn ghost add-item'),
                on::click('addRow'),
                icon('plus')
            ),
            btn
            (
                setClass('btn ghost del-item'),
                on::click('removeRow'),
                icon('trash')
            )
        )
    )
);

foreach($fieldList as $key => $value)
{
    $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;

    $formItems[] = formRow
    (
        input
        (
            set::type('hidden'),
            set::name('keys[]'),
            set::value($key)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($key === '' ? 'NULL' : $key),
            set::name('values[]'),
            set::value(isset($dbFields[$key]) ? $dbFields[$key]->value : $value),
            set::readonly(empty($key))
        ),
        div
        (
            setClass('pl-2 flex self-center'),
            btn
            (
                setClass('btn ghost add-item'),
                on::click('addRow'),
                icon('plus')
            ),
            btn
            (
                setClass('btn ghost del-item'),
                on::click('removeRow'),
                icon('trash')
            )
        )
    );
}

$formActions = array('submit');

if(common::hasPriv('custom', 'restore'))
{
    $formActions[] = array(
        'url'          => createLink('custom', 'restore', "module=stage&field=typeList"),
        'text'         => $lang->custom->restore,
        'class'        => 'btn-wide ajax-submit',
        'data-confirm' => $lang->custom->confirmRestore
    );
}

$appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
$formItems[] = formGroup
(
    set::width('1/2'),
    set::label(''),
    set::name('lang'),
    set::items($appliedTo),
    set::value($lang2Set),
    set::control('radioListInline')
);

$menuItems[] = li
(
    setClass('menu-item'),
    a
    (
        setClass('active'),
        set::href(createLink('stage', 'settype')),
        $lang->stage->setTypeAB
    )
);

if($config->edition == 'open' && hasPriv('stage', 'browse'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('stage', 'browse')),
            $lang->stage->browseAB
        )
    );
}

div
(
    setClass('row has-sidebar-left'),
    sidebar
    (
        div
        (
            setClass('cell p-2.5 bg-white'),
            menu($menuItems)
        )
    ),
    formPanel
    (
        set::headingClass('justify-start'),
        setClass('flex-auto ml-0.5'),
        set::actionsClass('w-1/2'),
        set::title($lang->stage->setTypeAB),
        set::actions($formActions),
        $formItems
    )
);
