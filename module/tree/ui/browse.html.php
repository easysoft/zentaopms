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

$manageTitle = $lang->tree->manageChild;
if(strpos($viewType, 'doc') !== false)
{
    $manageTitle = $lang->doc->manageType;
}
elseif($viewType == 'host')
{
    $manageTitle = $lang->host->groupMaintenance;
}
elseif(strpos($viewType, 'trainskill') === false and strpos($viewType, 'trainpost') === false)
{
    $manageChild = 'manage' . ucfirst($viewType) . 'Child';
    $manageTitle = $lang->tree->$manageChild;
}

$maxOrder = 0;

/* Generate module rows. */
$moduleRows = array();
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
                    set::placeholder($placeholder)
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
            ),
            batchActions
            (
                set::actionClass('action-group child-hidden')
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
                    set::placeholder($placeholder)
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
            batchActions
            (
                set::actionClass('action-group')
            )
        )
    );
}

$parentPath = array();
$parentPath[] = div
(
    setClass('row flex-nowrap items-center'),
    a
    (
        setClass('tree-link text-clip'),
        set('href', helper::createLink('tree', 'browse', "rootID=$root->id&view={$viewType}&currentModuleID=0&branch=$branch")),
        set('data-app', $app->tab),
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
            set('href', helper::createLink('tree', 'browse', "rootID=$root->id&view={$viewType}&currentModuleID=$module->id&branch=$branch")),
            set('data-app', $app->tab),
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
    setClass('row gap-4'),
    !isInModal() ? backBtn
    (
        set::icon('back'),
        set::type('secondary'),
        $lang->goback
    ) : null,
    div
    (
        setClass('entity-label flex items-center gap-x-2 text-lg font-bold'),
        $lang->tree->common . $lang->colon . $root->name
    )
);

div
(
    setClass('row gap-4 mt-2'),
    sidebar
    (
        set::toggleBtn(false),
        set::width(400),
        set::minWidth(350),
        set::maxWidth(550),
        panel
        (
            set::title($title),
            ($app->tab == 'product' and $viewType == 'story') ? to::headingActions
            (
                btn
                (
                    setClass('primary size-sm'),
                    set::url('tree', 'viewHistory', "productID=$rootID"),
                    toggle::modal(),
                    $lang->history
                )
            ) : null,
            treeEditor
            (
                set('selected', $currentModuleID),
                set('type', $viewType),
                set('items', $tree),
                set('canEdit', common::hasPriv('tree', 'edit') && $canBeChanged),
                set('canDelete', common::hasPriv('tree', 'delete') && $canBeChanged)
            )
        )
    ),
    div
    (
        setClass('flex-auto'),
        panel
        (
            setClass('pb-4'),
            set::title($manageTitle),
            div
            (
                setClass('flex'),
                div
                (
                    setClass('pr-2 tree-item-content row items-center'),
                    setStyle('max-width', '380px'),
                    setStyle('padding-bottom', '48px'),
                    $parentPath
                ),
                form
                (
                    setClass('flex-1 form-horz'),
                    set::url(helper::createLink('tree', 'manageChild', "root=$root->id&viewType=$viewType")),
                    set('data-app', $app->tab),
                    $moduleRows,
                    set::actionsClass('justify-start'),
                    set::submitBtnText($lang->save),
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
