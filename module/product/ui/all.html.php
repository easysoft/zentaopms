<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

/* Get column settings of the data table. */
$cols = array_values($config->product->dtable->fieldList);
/* Set the name link. */
foreach($cols as &$col)
{
    if($col['name'] != 'name') continue;

    $col['link'] = sprintf($col['link'], createLink('product', 'browse', array('productID' => '${row.id}')));
    break;
}

$extendFieldList = $this->product->getFlowExtendFields();
foreach($extendFieldList as $field => $name)
{
    $extCol = $config->product->dtable->extendField;
    $extCol['name']  = $field;
    $extCol['title'] = $name;

    $cols[] = $extCol;
}

/* Closure function for generating table data. */
$fnGenerateTableData = function($productList) use($users, $avatarList)
{
    $data = array();
    foreach($productList as $product)
    {
        $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];
        $totalBugs    = $product->unResolved + $product->fixedBugs;

        $item = new stdClass();

        if(!empty($product->PO))
        {
            $item->PO        = zget($users, $product->PO);
            $item->POAvatar  = $avatarList[$product->PO];
            $item->POAccount = $product->PO;
        }

        $item->name              = $product->name;
        $item->id                = $product->id;
        $item->type              = 'product';
        $item->draftStories      = $product->stories['draft'];
        $item->activeStories     = $product->stories['active'];
        $item->changingStories   = $product->stories['changing'];
        $item->reviewingStories  = $product->stories['reviewing'];
        $item->storyCompleteRate = ($totalStories == 0 ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100);
        $item->unResolvedBugs    = $product->unResolved;
        $item->bugFixedRate      = ($totalBugs == 0 ? 0 : round($product->fixedBugs / $totalBugs, 3) * 100);
        $item->plans             = $product->plans;
        $item->releases          = $product->releases;
        $item->productLine       = $product->lineName;
        $item->execution         = $product->executions;
        $item->testCaseCoverage  = $product->coverage;

        $data[] = $item;
    }

    return $data;
};

/* Closure function for generating program menu. */
$fnGenerateProgramMenu = function($programList) use($lang, $programID, $browseType, $orderBy, $param, $recTotal, $recPerPage, $pageID)
{
    $programMenuLink = createLink(
        $this->app->rawModule,
        $this->app->rawMethod,
        array(
            'browseType' => $browseType == 'bySearch' ? 'noclosed' : $browseType,
            'orderBy'    => $orderBy,
            'param'      => $browseType == 'bySearch' ? 0 : $param,
            'recTotal'   => $recTotal,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID,
            'programID'  => '%d'
        )
    );

    /* Attach icon to each program. */
    $programs = array_map(function($program)
    {
        $program->icon = 'icon-cards-view';
        return $program;
    }, $programList);

    return programMenu
    (
        setStyle(array('margin-right' => '20px')),
        set(array
        (
            'title'       => $lang->program->all,
            'programs'    => $programs,
            'activeKey'   => !empty($programList) ? $programID : null,
            'closeLink'   => sprintf($programMenuLink, 0),
            'onClickItem' => jsRaw("function(data){window.programMenuOnClick(data, '$programMenuLink');}")
        ))
    );
};

/* ====== Define the page structure with zin widgets ====== */
featureBar
(
    to::before($fnGenerateProgramMenu($programList)),
    set::link(createLink
    (
        $this->app->rawModule,
        $this->app->rawMethod,
        array
        (
            'browseType' => '{key}',
            'orderBy'    => $orderBy,
            'param'      => $param,
            'recTotal'   => 0,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID,
            'programID'  => $programID
        )
    )),
    hasPriv('product', 'batchEdit') ? item
    (
        set::type('checkbox'),
        set::text($lang->product->edit),
        set::checked($this->cookie->editProject)
    ) : null,
    li(searchToggle(set::open($browseType == 'bySearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

toolbar
(
    btn
    (
        setClass('ghost text-darker'),
        set::icon('export'),
        set('data-toggle', 'modal'),
        set('data-url', createLink('product', 'export', "programID=$programID&status=$browseType&orderBy=$orderBy&param=$param")),
        $lang->export
    ),
    div(setClass('nav-divider')),
    $config->systemMode == 'ALM' ? btn
    (
        setClass('ghost text-primary'),
        set::icon('edit'),
        set('data-toggle', 'modal'),
        set('data-url', createLink('product', 'manageLine', $browseType)),
        $lang->product->editLine
    ) : null,
    item(set(array
    (
        'text'  => $lang->product->create,
        'icon'  => 'plus',
        'class' => 'primary',
        'url'   => createLink('product', 'create')
    )))
);

dtable
(
    set::cols($cols),
    set::data($fnGenerateTableData($productStats)),
    set::checkable(true),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$recTotal}&recPerPage={$recPerPage}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(array
        (
            'text'    => $lang->edit,
            'btnType' => 'primary',
            'url'     => createLink('product', 'batchEdit'),
            'onClick' => jsRaw('onClickBatchEdit')
        ))
    )),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(createLink('product', 'all', "browseType={$browseType}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={page}"))
    )
);

jsVar('langSummary', $lang->product->pageSummary);

render();
