<?php
declare(strict_types=1);
/**
 * The story kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('cardLang',    $lang->kanbancard);
jsVar('unlinkLang',  $lang->unlink);
jsVar('hourUnit',    $config->hourUnit);
jsVar('executionID', $executionID);
jsVar('priv', array(
    'canViewStory'        => common::hasPriv('execution', 'storyView'),
    'canUnlinkStory'      => (common::hasPriv('execution', 'unlinkStory') && $execution->hasProduct),
    'canBatchChangeStage' => common::hasPriv('story', 'batchChangeStage')
));

featureBar
(
    li
    (
        setClass('nav-item'),
        a
        (
            setClass('active'),
            set::href(inlink('storykanban', "executionID={$execution->id}")),
            $lang->execution->kanban,
            span(setClass('label size-sm rounded-full white'), $total)
        )
    )
);

$canModifyProduct   = common::canModify('product', $product);
$canModifyExecution = common::canModify('execution', $execution);
$canLinkStory       = ($execution->hasProduct || $app->tab == 'execution') && $canModifyProduct && $canModifyExecution && hasPriv('execution', 'linkStory');
$canCreate          = $canModifyProduct && $canModifyExecution && hasPriv('story', 'create');
$canBatchCreate     = $canModifyProduct && $canModifyExecution && hasPriv('story', 'batchCreate');
$createLink         = createLink('story', 'create', "product={$product->id}&branch=0&moduleID=0&storyID=0&objectID={$execution->id}") . "#app={$app->tab}";
$batchCreateLink    = createLink('story', 'batchCreate', "productID={$product->id}&branch=0&moduleID=0&storyID=0&executionID={$execution->id}") . "#app={$app->tab}";
$createItem         = array('text' => $lang->story->create,      'url' => $createLink);
$batchCreateItem    = array('text' => $lang->story->batchCreate, 'url' => $batchCreateLink);
$linkStoryUrl       = createLink('execution', 'linkStory', "project={$execution->id}");
$linkItem           = array('text' => $lang->story->linkStory, 'url' => $linkStoryUrl, 'data-app' => $app->tab);
$product ? toolbar
(
    common::hasPriv('execution', 'storykanban') ? btnGroup
    (
        btn
        (
            set::icon('format-list-bulleted'),
            set::hint($lang->execution->list),
            set::url(inlink('story', "executionID={$execution->id}")),
            setData('app', $app->tab)
        ),
        btn
        (
            setClass('text-primary font-bold shadow-inner bg-canvas'),
            set::icon('kanban'),
            set::hint($lang->execution->kanban),
            set::url($this->createLink('execution', 'storykanban', "executionID={$execution->id}")),
            setData('app', $app->tab)
        ),
    ) : null,
    hasPriv('story', 'export') ? item(set(array
    (
        'text'        => $lang->story->export,
        'icon'        => 'export',
        'class'       => 'ghost',
        'url'         => createLink('story', 'export', "productID={$product->id}&orderBy=id_desc"),
        'data-toggle' => 'modal'
    ))) : null,

    $canCreate && $canBatchCreate ? btngroup
    (
        btn
        (
            setClass('btn secondary'),
            set::icon('plus'),
            set::url($createLink),
            $lang->story->create
        ),
        dropdown
        (
            btn(setClass('btn secondary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array_filter(array($createItem, $batchCreateItem))),
            set::placement('bottom-end')
        )
    ) : null,

    $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,

    $canLinkStory && $canBeChanged ? btngroup
    (
        btn(
            setClass('btn primary'),
            set::icon('link'),
            set::url($linkStoryUrl),
            setData('app', $app->tab),
            $lang->story->linkStory
        ),
    ) : null,
) : null;

div
(
    set::id('kanban'),
    zui::kanban
    (
        set::key('kanban'),
        set::data($kanbanData),
        set::laneNameWidth(0),
        set::getItem(jsRaw('window.getItem')),
        set::getLane(jsRaw('window.getLane')),
        set::canDrop(jsRaw('window.canDrop')),
        set::onDrop(jsRaw('window.onDrop'))
    )
);
