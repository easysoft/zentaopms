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

jsVar('rootID', $root->id);
jsVar('viewType', $viewType);
jsVar('noSubmodule', $lang->tree->noSubmodule);

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
if($viewType == 'story' && $allProduct)
{
    $moduleRows[] = formRow
    (
        setClass('copyBox hidden'),
        formGroup
        (
            inputGroup
            (
                setClass('row-module'),
                picker(setClass('col-module'), set::name("allProduct"), set::items($allProduct), on::change("syncProduct(e.target)"), set::required(true)),
                picker(setClass('col-short'), set::name("productModule"), set::items($productModules), set::required(true)),
            ),
            btn(setID('copyModule'), on::click("syncModule"), icon('copy'), setClass('ghost'))
        )
    );
}

foreach($sons as $son)
{
    if($son->order > $maxOrder) $maxOrder = $son->order;
    $disabled = $son->type != $viewType;

    $moduleRows[] = formRow
    (
        setClass('sonModule'),
        formGroup
        (
            inputGroup
            (
                setClass('row-module no-morph'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[id$son->id]"),
                    set::type('input'),
                    set::value($son->name),
                    set::disabled($disabled),
                    set::placeholder($placeholder)
                ),
                empty($branches) ? null : picker
                (
                    set::name("branch[id$son->id]"),
                    set::items($branches),
                    set::value($son->branch),
                    set::disabled($disabled),
                    set::required(true)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[id$son->id]"),
                    set::type('input'),
                    set::value($son->short),
                    set::disabled($disabled),
                    set::placeholder($lang->tree->short)
                ),
                input
                (
                    setClass('hidden'),
                    set::name("order[id$son->id]"),
                    set::disabled($disabled),
                    set::value($son->order),
                    set::control('hidden')
                )
            ),
            batchActions(set::actionClass('action-group child-hidden'))
        )
    );
}

$initBranch = (int)$branch;
if($parentModules)
{
    $parentModule = end($parentModules);
    $initBranch   = (int)$parentModule->branch;
}
for($i = 0; $i < \tree::NEW_CHILD_COUNT; $i ++)
{
    $moduleRows[] = formRow
    (
        formGroup
        (
            inputGroup
            (
                setClass('row-module no-morph'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[]"),
                    set::type('input'),
                    set::value(''),
                    set::placeholder($placeholder)
                ),
                empty($branches) ? null : picker
                (
                    set::name("branch[]"),
                    set::items($branches),
                    set::value($initBranch),
                    set::required(true)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[]"),
                    set::type('input'),
                    set::placeholder($lang->tree->short)
                )
            ),
            batchActions(set::actionClass('action-group'))
        )
    );
}

$parentPath = array();
$parentLinkData = array('app' => $app->tab);
if(isInModal())
{
    $parentLinkData['size']    = 'lg';
    $parentLinkData['toggle']  = 'modal';
    $parentLinkData['dismiss'] = 'modal';
}
$parentPath[] = div
(
    setClass('row flex-nowrap items-center'),
    a
    (
        setClass('tree-link text-clip'),
        set('href', helper::createLink('tree', 'browse', "rootID=$root->id&view={$viewType}&currentModuleID=0&branch=$branch")),
        setData($parentLinkData),
        set::title($root->name),
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
            set::title($module->name),
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
        $viewType == 'host' ? $root->name : $lang->tree->common . $lang->hyphen . $root->name
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
                set::selected($currentModuleID),
                set::type($viewType),
                set::items($tree),
                set::canEdit(common::hasPriv('tree', 'edit') && $canBeChanged),
                set::canDelete(common::hasPriv('tree', 'delete') && $canBeChanged),
                set::canSplit($viewType != 'deliverable'),
                set::sortable(array('handle' => '.icon-move')),
                set::onSort(jsRaw('window.updateOrder'))
            )
        )
    ),
    div
    (
        setClass('flex-auto'),
        setID('modulePanel'),
        panel
        (
            setClass('pb-4'),
            set::title($manageTitle),
            to::headingActions
            (
                ($viewType == 'story' && $allProduct && $canBeChanged) ? btn(setClass('primary'), set::size('sm'), $lang->tree->syncFromProduct, on::click('toggleCopy')) : null
            ),
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
                    set::back($isFlowModule ? 'GLOBAL' : 'APP'),
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
