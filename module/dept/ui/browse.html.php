<?php
declare(strict_types=1);
/**
 * The browse view file of dept module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     dept
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('editLinkTemp', createLink('dept', 'edit', "deptID={id}"));
jsVar('deleteLinkTemp', createLink('dept', 'delete', "deptID={id}"));
jsVar('deleteTip', $lang->dept->confirmDelete);

$deptActions = array();
if(hasPriv('dept', 'edit'))
{
    $deptAction = array();
    $deptAction['key']  = 'edit';
    $deptAction['icon'] = 'edit';
    $deptAction['hint'] = $lang->dept->edit;
    $deptAction['onClick'] = jsRaw('window.operateDept');

    $deptActions[] = $deptAction;
}
if(hasPriv('dept', 'delete'))
{
    $deptAction = array();
    $deptAction['key']  = 'delete';
    $deptAction['icon'] = 'trash';
    $deptAction['hint'] = $lang->dept->delete;
    $deptAction['onClick'] = jsRaw('window.operateDept');

    $deptActions[] = $deptAction;
}

sidebar
(
    width('400px'),
    set::showToggle(false),
    panel
    (
        set::title($title),
        tree
        (
            set::id('deptTree'),
            set::items($tree),
            set::hover(true),
            set::itemActions($deptActions),
        ),
    ),
);

$parentNames = array();
foreach($parentDepts as $dept)
{
    $parentNames[] = cell
    (
        setClass('flex items-center'),
        a
        (
            set::title($dept->name,),
            set::href(createLink('dept', 'browse', "deptID={$dept->id}")),
            $dept->name,
        ),
        icon
        (
            setClass('mx-2'),
            'angle-right'
        ),
    );
}

$maxOrder   = 0;
$deptInputs = array();
foreach($sons as $dept)
{

    if($dept->order > $maxOrder) $maxOrder = $dept->order;
    $deptInputs[] = formGroup
    (
        setClass('w-full my-1'),
        set::name("depts[id{$dept->id}]"),
        set::value($dept->name),
    );
}
$emptyInputs = array();
for($i = 0; $i < \DEPT::NEW_CHILD_COUNT ; $i ++)
{
    $emptyInputs[] = formGroup
    (
        setClass('w-full my-1'),
        set::name("depts[]"),
        set::value(''),
    );
}

panel
(
    set::title($lang->dept->manageChild),
    set::titleClass('text-base'),
    form
    (
        set('action', createLink('dept', 'manageChild')),
        cell
        (
            setClass('flex'),
            cell
            (
                setClass('flex flex-none flex-wrap px-2'),
                cell
                (
                    setClass('flex items-center'),
                    a
                    (
                        set::title($this->app->company->name,),
                        set::href(createLink('dept', 'browse')),
                        $this->app->company->name,
                    ),
                    icon('angle-right'),
                ),
                $parentNames,
            ),
            cell
            (
                setClass('flex flex-wrap p-2'),
                width('500px'),
                $deptInputs,
                $emptyInputs,
                formGroup
                (
                    setClass('hidden'),
                    set::name('maxOrder'),
                    set::value($maxOrder),
                ),
                formGroup
                (
                    setClass('hidden'),
                    set::name('parentDeptID'),
                    set::value($deptID),
                ),
            ),
        ),
    ),
);

render();

