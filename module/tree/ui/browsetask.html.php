<?php
declare(strict_types=1);
/**
 * The browsetask view file of tree module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jinyong Zhu<zhujinyong@easycorp.ltd>
 * @package     tree
 * @link        https://www.zentao.net
 */
namespace zin;

$maxOrder = 0;

/* Generate module rows. */
$moduleRows = array();
if($newModule and !$productID)
{
    foreach($products as $product)
    {
        $moduleRows[] = formRow
        (
            formGroup
            (
                setClass('row-module'),
                input
                (
                    setClass('col-module'),
                    set::name("products[id$product->id]"),
                    set::type('input'),
                    set::value($product->name),
                    set::disabled(true)
                )
            )
        );
    }
}

foreach($sons as $son)
{
    if($son->order > $maxOrder) $maxOrder = $son->order;

    $moduleRows[] = formRow
    (
        formGroup
        (
            inputGroup
            (
                setClass('row-module'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[id$son->id]"),
                    set::type('input'),
                    set::value($son->name),
                    set::placeholder($lang->tree->name)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[id$son->id]"),
                    set::type('input'),
                    set::value($son->short),
                    set::placeholder($lang->tree->short)
                ),
                input
                (
                    setClass('hidden'),
                    set::name("order[id$son->id]"),
                    set::value($son->order),
                    set::control('hidden')
                )
            )
        )
    );
}

for($i = 0; $i < \tree::NEW_CHILD_COUNT; $i ++)
{
    $moduleRows[] = formRow
    (
        formGroup
        (
            inputGroup
            (
                setClass('row-module'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[$i]"),
                    set::type('input'),
                    set::value(''),
                    set::placeholder($lang->tree->name)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[$i]"),
                    set::type('input'),
                    set::value(''),
                    set::placeholder($lang->tree->short)
                ),
                input
                (
                    setClass('hidden'),
                    set::name("branch[$i]"),
                    set::value(0),
                    set::control('hidden')
                )
            ),
            batchActions()
        )
    );
}

$parentPath = array();
$parentPath[] = div
(
    setClass('row flex-nowrap items-center text-clip'),
    a
    (
        setClass('tree-link text-clip'),
        set::title($root->name),
        set('href', helper::createLink('tree', 'browsetask', "rootID=$root->id&productID=$productID")),
        $root->name
    ),
    h::i
    (
        setClass('icon icon-angle-right muted align-middle'),
        setStyle('color', '#313C52')
    )
);
foreach($parentModules as $module)
{
    $parentPath[] = div
    (
        setClass('row flex-nowrap items-center'),
        a
        (
            setClass('tree-link text-clip'),
            set('href', helper::createLink('tree', 'browsetask', "rootID=$root->id&productID=$productID&module=$module->id")),
            $module->name
        ),
        h::i
        (
            setClass('icon icon-angle-right muted align-middle'),
            setStyle('color', '#313C52')
        )
    );
}

div
(
    setClass('flex gap-x-4 mb-3'),
    backBtn
    (
        set::icon('back'),
        set::type('secondary'),
        set::back('execution-task'),
        $lang->goback
    ),
    div
    (
        setClass('entity-label flex items-center gap-x-2 text-lg font-bold'),
        $lang->tree->common . $lang->colon . $root->name
    )
);

div
(
    setClass('row gap-4'),
    sidebar
    (
        set::width(400),
        set::minWidth(350),
        set::maxWidth(550),
        set::toggleBtn(false),
        panel
        (
            set::title($title),
            treeEditor
            (
                set('selected', $currentModuleID),
                set('type', 'task'),
                set('items', $tree),
                set('canEdit', common::hasPriv('tree', 'edit') && $canBeChanged),
                set('canDelete', common::hasPriv('tree', 'delete') && $canBeChanged)
            )
        )
    ),
    div
    (
        setStyle('max-width', '70%'),
        panel
        (
            setClass('pb-4'),
            set::title($execution->multiple ? $lang->tree->manageTaskChild : $lang->tree->manageProjectChild),
            div
            (
                setClass('flex'),
                div
                (
                    setClass('pr-2 tree-item-content row items-center'),
                    setStyle('padding-bottom', '48px'),
                    setStyle('max-width', '30%'),
                    $parentPath
                ),
                form
                (
                    setClass('flex-1 form-horz'),
                    set::url(helper::createLink('tree', 'manageChild', "root=$root->id&viewType=task")),
                    $moduleRows,
                    set::actions(array('submit', 'cancel')),
                    set::back('execution-task'),
                    set::actionsClass('justify-start'),
                    input
                    (
                        set::type('hidden'),
                        set::name('parentModuleID'),
                        set::value($currentModuleID),
                        set::control('hidden')
                    ),
                    input
                    (
                        set::type('hidden'),
                        set::name('maxOrder'),
                        set::value($maxOrder),
                        set::control('hidden')
                    )
                )
            )
        )
    )
);

render();
