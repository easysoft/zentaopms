<?php
declare(strict_types=1);
/**
 * The manage project admin view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

sidebar
(
    set::id('sidebar'),
    moduleMenu(set(array(
        'modules'     => $deptTree,
        'activeKey'   => $deptID,
        'closeLink'   => $this->createLink('group', 'manageProjectAdmin', "groupID={$groupID}"),
        'showDisplay' => false
    )))
);

$tbody         = array();
$hiddenProgram = in_array($config->systemMode, array('ALM', 'PLM')) ? '' : 'hidden';
$rowspan       = in_array($config->systemMode, array('ALM', 'PLM')) ? 4 : 3;

if($projectAdmins)
{
    $i = 1;
    foreach($projectAdmins as $account => $group)
    {
        $tbody[] = h::tr
        (
            setClass("line{$group->group}"),
            h::td
            (
                set::rowspan($rowspan),
                picker
                (
                    set::name("members[$group->group][]"),
                    set::items($allUsers),
                    set::value($account),
                    set::multiple(true)
                )
            ),
            h::td
            (
                setClass($hiddenProgram),
                inputGroup
                (
                    $lang->group->manageProgram,
                    picker
                    (
                        set::disabled($group->programs == 'all'),
                        set::name("program[$group->group][]"),
                        set::items($programs),
                        set::value($group->programs == 'all' ? '' : $group->programs),
                        set::multiple(true)
                    )
                )
            ),
            h::td
            (
                setClass("$hiddenProgram text-center"),
                checkbox
                (
                    set::name("programAll[$group->group]"),
                    set::items(array(1 => '')),
                    set::checked($group->programs == 'all')
                )
            ),
            $hiddenProgram ? h::td
            (
                setClass('projectTd'),
                inputGroup
                (
                    $lang->group->manageProject,
                    picker
                    (
                        set::disabled($group->projects == 'all'),
                        set::name("project[$group->group][]"),
                        set::items($projects),
                        set::checked($group->projects == 'all'),
                        set::multiple(true)
                    )
                )
            ) : null,
            $hiddenProgram ? h::td
            (
                setClass('text-center'),
                checkbox
                (
                    set::name("projectAll[$group->group]"),
                    set::items(array(1 => '')),
                    set::checked($group->projects == 'all')
                )
            ) : null,
            h::td
            (
                setClass('text-center'),
                set::rowspan($rowspan),
                btnGroup
                (
                    set::items
                    (
                        array
                        (
                          array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                          array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                        )
                    )
                )
            )
        );

        if(empty($hiddenProgram))
        {
            $tbody[] = h::tr
            (
                setClass("line{$group->group}"),
                h::td
                (
                    setClass('projectTd'),
                    inputGroup
                    (
                        $lang->group->manageProject,
                        picker
                        (
                            set::disabled($group->projects == 'all'),
                            set::name("project[$group->group][]"),
                            set::value($group->projects == 'all' ? '' : $group->projects),
                            set::items($projects),
                            set::multiple(true)
                        )
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    checkbox
                    (
                        set::name("projectAll[$group->group]"),
                        set::items(array(1 => '')),
                        set::checked($group->projects == 'all')
                    )
                )
            );
        }

        $tbody[] = h::tr
            (
                setClass("line{$group->group}"),
                h::td
                (
                    setClass('productTd'),
                    inputGroup
                    (
                        $lang->group->manageProduct,
                        picker
                        (
                            set::disabled($group->products == 'all'),
                            set::name("product[$group->group][]"),
                            set::items($products),
                            set::value($group->products == 'all' ? '' : $group->products),
                            set::multiple(true)
                        )
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    checkbox
                    (
                        set::name("productAll[$group->group]"),
                        set::items(array(1 => '')),
                        set::checked($group->products == 'all')
                    )
                )
            );

        $tbody[] = h::tr
            (
                setClass("line{$group->group}"),
                h::td
                (
                    setClass('executionTd'),
                    inputGroup
                    (
                        $lang->group->manageExecution,
                        picker
                        (
                            set::disabled($group->executions == 'all'),
                            set::name("execution[$group->group][]"),
                            set::value($group->executions == 'all' ? '' : $group->executions),
                            set::items($executions),
                            set::multiple(true)
                        )
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    checkbox
                    (
                        set::name("executionAll[$group->group]]"),
                        set::items(array(1 => '')),
                        set::checked($group->executions == 'all')
                    )
                )
            );

        $i ++;
    }
}
else
{
    $tbody[] = h::tr
        (
            setClass('line1'),
            h::td
            (
                set::rowspan($rowspan),
                picker
                (
                    set::name('members[1][]'),
                    set::items($allUsers),
                    set::multiple(true)
            )
        ),
        h::td
        (
            setClass($hiddenProgram),
            inputGroup
            (
                $lang->group->manageProgram,
                picker
                (
                    set::name('program[1][]'),
                    set::items($programs),
                    set::multiple(true)
                )
            )
        ),
        h::td
        (
            setClass("$hiddenProgram text-center"),
            checkbox
            (
                set::name('programAll[1]'),
                set::items(array(1 => ''))
            )
        ),
        $hiddenProgram ? h::td
        (
            setClass('projectTd'),
            inputGroup
            (
                $lang->group->manageProject,
                picker
                (
                    set::name('project[1][]'),
                    set::items($projects),
                    set::multiple(true)
                )
            )
        ) : null,
        $hiddenProgram ? h::td
        (
            setClass('text-center'),
            checkbox
            (
                set::name('projectAll[1]'),
                set::items(array(1 => ''))
            )
        ) : null,
        h::td
        (
            setClass('text-center'),
            set::rowspan($rowspan),
            btnGroup
            (
                set::items
                (
                    array
                    (
                        array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                        array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                    )
                )
            )
        )
    );

    if(empty($hiddenProgram))
    {
        $tbody[] = h::tr
        (
            setClass('line1'),
            h::td
            (
                setClass('projectTd'),
                inputGroup
                (
                    $lang->group->manageProject,
                    picker
                    (
                        set::name('project[1][]'),
                        set::items($projects),
                        set::multiple(true)
                    )
                )
            ),
            h::td
            (
                setClass('text-center'),
                checkbox
                (
                    set::name('projectAll[1]'),
                    set::items(array(1 => ''))
                )
            )
        );
    }

    $tbody[] = h::tr
    (
        setClass('line1'),
        h::td
        (
            setClass('productTd'),
            inputGroup
            (
                $lang->group->manageProduct,
                picker
                (
                    set::name('product[1][]'),
                    set::items($products),
                    set::multiple(true)
                )
            )
        ),
        h::td
        (
            setClass('text-center'),
            checkbox
            (
                set::name('productAll[1]'),
                set::items(array(1 => ''))
            )
        )
    );

    $tbody[] = h::tr
    (
        setClass('line1'),
        h::td
        (
            setClass('executionTd'),
            inputGroup
            (
                $lang->group->manageExecution,
                picker
                (
                    set::name('execution[1][]'),
                    set::items($executions),
                    set::multiple(true)
                )
            )
        ),
        h::td
        (
            setClass('text-center'),
            checkbox
            (
                set::name('executionAll[1]'),
                set::items(array(1 => ''))
            )
        )
    );
}

panel
(
    form
    (
        on::click('.btn-add', 'addItem'),
        on::click('.btn-delete', 'deleteItem'),
        on::change('[name^=programAll]', 'toggleDisabled'),
        on::change('[name^=productAll]', 'toggleDisabled'),
        on::change('[name^=projectAll]', 'toggleDisabled'),
        on::change('[name^=executionAll]', 'toggleDisabled'),
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                setClass('text-center'),
                h::th
                (
                    width('220px'),
                    $lang->group->inside
                ),
                h::th($lang->group->object),
                h::th
                (
                    width('100px'),
                    $lang->group->allCheck
                ),
                h::th
                (
                    width('120px'),
                    $lang->actions
                )
            ),
            $tbody
        )
    )
);
