<?php
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

$data         = array();
$totalStories = 0;

foreach($productStats as $productID => $product)
{
    $item = new stdClass();

    if(!empty($product->PO))
    {
        $item->PO        = zget($users, $product->PO);
        $item->POAvatar  = $avatarList[$product->PO];
        $item->POAccount = $product->PO;
    }
    $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];

    $item->name              = $product->name;
    $item->id                = $product->id;
    $item->type              = 'product';
    $item->draftStories      = $product->stories['draft'];
    $item->activeStories     = $product->stories['active'];
    $item->changingStories   = $product->stories['changing'];
    $item->reviewingStories  = $product->stories['reviewing'];
    $item->storyCompleteRate = ($totalStories == 0 ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100);
    $item->unResolvedBugs    = $product->unResolved;
    $item->bugFixedRate      = (($product->unResolved + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unResolved + $product->fixedBugs), 3) * 100);
    $item->plans             = $product->plans;
    $item->releases          = $product->releases;
    $item->productLine       = $product->lineName;
    $item->execution         = $product->executions;
    $item->testCaseCoverage  = $product->coverage;
    $item->releasesOld       = rand(0, 10);

    $data[] = $item;
}

/* Closure function for generate program menu. */
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
    modalTrigger
    (
        to::trigger(btn
        (
            setClass('ghost text-darker'),
            set::icon('export'),
            $lang->export
        )),
        set::size('sm'),
        set::url(createLink('product', 'export', "programID=$programID&status=$browseType&orderBy=$orderBy&param=$param")),
        set::type('ajax')
    ),
    div(setClass('nav-divider')),
    $config->systemMode == 'ALM' ? modalTrigger
    (
        to::trigger(btn
        (
            setClass('ghost text-primary'),
            set::icon('edit'),
            $lang->product->editLine
        )),
        set::url(createLink('product', 'manageLine', $browseType)),
        set::type('ajax')
    ) : null,
    item(set(array
    (
        'text'  => $lang->product->create,
        'icon'  => 'plus',
        'class' => 'primary',
        'url'   => createLink('product', 'create')
    )))
);

jsVar('langSummary', $lang->product->pageSummary);
dtable
(
    set::cols($cols),
    set::data($data),
    set::checkable(true),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$recTotal}&recPerPage={$recPerPage}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array
        (
            array('text' => $lang->edit, 'btnType' => 'primary', 'url' => createLink('product', 'batchEdit'))
        )
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

render();
