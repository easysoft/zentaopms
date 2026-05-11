<?php
declare(strict_types=1);
/**
 * The execution view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

$expireDays = isset($config->kanban->reminder->expireDays) ? $config->kanban->reminder->expireDays : 1;
$menuItems  = array(
    li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('custom', 'kanban', 'type=closedSetting')),
            $type == 'closedSetting' ? setClass('active') : null,
            $lang->custom->closeSetting
        )
    ),
    li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('custom', 'kanban', 'type=expireReminder')),
            $type == 'expireReminder' ? setClass('active') : null,
            $lang->custom->kanbanExpireReminder
        )
    )
);

if($type == 'closedSetting')
{
    $form[] = formGroup
    (
        set::width('2/3'),
        set::label($lang->custom->closedKanban),
        radioList
        (
            set::name('kanban'),
            set::items($lang->custom->CRKanban),
            set::value(isset($config->CRKanban) ? $config->CRKanban : 0),
            set::inline(true)
        )
    );

    $form[] = formGroup
    (
        set::label(''),
        span
        (
            icon('info text-warning mr-2'),
            $lang->custom->notice->readOnlyOfKanban
        )
    );
}
else
{
    $form[] = formGroup
    (
        set::width('1/3'),
        set::label($lang->custom->kanbanExpireDays),
        inputGroup
        (
            control
            (
                set::name('expireDays'),
                set::type('number'),
                set::min(0),
                set::value($expireDays)
            ),
            $lang->custom->unitList['days']
        )
    );

    $form[] = formGroup
    (
        set::label(''),
        span
        (
            icon('info text-warning mr-2'),
            $lang->custom->notice->kanbanReminder
        )
    );
}
div
(
    setClass('flex'),
    sidebar
    (
        div
        (
            setClass('cell p-2.5 bg-white'),
            menu
            (
                $menuItems
            )
        )
    ),
    formPanel
    (
        set::actions(array('submit')),
        set::labelWidth('120px'),
        setClass('flex-auto'),
        setClass('ml-4'),
        $form
    )
);
