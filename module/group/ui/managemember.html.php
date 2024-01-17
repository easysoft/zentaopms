<?php
declare(strict_types=1);
/**
 * The manageMember view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->group->manageMember),
    set::entityText($group->name),
);

/* zin: Define the sidebar in main content. */
div
(
    setClass('w-full flex'),
    cell
    (
        setClass('w-1/5'),
        moduleMenu(set(array(
            'modules'     => $deptTree,
            'activeKey'   => $deptID,
            'closeLink'   => $this->createLink('group', 'manageMember', "groupID={$group->id}"),
            'showDisplay' => false,
            'app'         => $app->tab
        )))
    ),
    cell
    (
        setClass('w-4/5'),
        formPanel
        (
            set::submitBtnText($lang->save),
            set::formClass('border-0'),
            $groupUsers ? formRow
            (
                set::className('group-user-row'),
                formGroup
                (
                    set::className('items-center'),
                    set::label($lang->group->inside),
                    set::width('1/10'),
                    checkbox
                    (
                        set::id('allInsideChecker'),
                        set::name('allInsideChecker'),
                        set::className('check-all'),
                        set::checked(true)
                    )
                ),
                formGroup
                (
                    checkList
                    (
                        setClass('flex-wrap w-full h-full group-user-box'),
                        set::name('members[]'),
                        set::items($groupUsers),
                        set::value(implode(',', array_keys($groupUsers))),
                        set::inline(true)
                    )
                )
            ) : null,
            $groupUsers ? h::hr() : null,
            !empty($otherUsers) ? formRow
            (
                set::className('group-user-row'),
                formGroup
                (
                    set::className('items-center'),
                    set::label($lang->group->outside),
                    set::width('1/10'),
                    checkbox
                    (
                        set::id('allOtherChecker'),
                        set::name('allOtherChecker'),
                        set::className('check-all')
                    )
                ),
                formGroup
                (
                    checkList
                    (
                        setClass('flex-wrap w-full h-full group-user-box'),
                        set::name('members[]'),
                        set::items($otherUsers),
                        set::inline(true)
                    ),
                    formHidden('foo', '')
                )
            ) : null,
            $outsideUsers ? h::hr() : null,
            !empty($outsideUsers) ? formRow
            (
                set::className('group-user-row'),
                formGroup
                (
                    set::className('items-center'),
                    set::label($lang->user->outside),
                    set::width('1/10'),
                    checkbox
                    (
                        set::id('allOutSideChecker'),
                        set::name('allOutSideChecker'),
                        set::className('check-all')
                    )
                ),
                formGroup
                (
                    checkList
                    (
                        setClass('flex-wrap w-full h-full group-user-box'),
                        set::name('members[]'),
                        set::items($outsideUsers),
                        set::inline(true)
                    )
                )
            ) : null
        )
    )
);

/* ====== Render page ====== */
render();
