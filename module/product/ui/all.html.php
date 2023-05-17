<?php
namespace zin;

$cols = array_values($config->product->dtable->fieldList);

/* TODO: implements extend fields. */
$extendFields = $this->product->getFlowExtendFields();

$data         = array();
$totalStories = 0;
$programs     = array();

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

    $item->name              = $product->name; /* TODO replace with <a> */
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
    $item->parent            = null;
    $item->productLine       = $product->lineName;
    $item->execution         = $product->executions;
    $item->testCaseCoverage  = $product->coverage;
    $item->releasesOld       = rand(0, 10);

    $data[] = $item;
}

$programMenuLink = createLink(
    $this->app->rawModule,
    $this->app->rawMethod,
    array(
        'browseType' => $browseType,
        'orderBy'    => $orderBy,
        'param'      => $param,
        'recTotal'   => $recTotal,
        'recPerPage' => $recPerPage,
        'pageID'     => $pageID,
        'programID'  => '%d'
    )
);

featureBar
(
    to::before
    (
        programMenu
        (
            setStyle(array('margin-right' => '20px')),
            set
            (
                array
                (
                    'title'       => $lang->program->all,
                    'programs'    => $programs,
                    'activeKey'   => !empty($programs) ? $programID : null,
                    'closeLink'   => sprintf($programMenuLink, 0),
                    'onClickItem' => jsRaw("function(data){window.programMenuOnClick(data, '$programMenuLink');}")
                )
            )
        )
    ),
    hasPriv('product', 'batchEdit') ? item
    (
        set::type('checkbox'),
        set::text($lang->product->edit),
        set::checked($this->cookie->editProject)
    ) : NULL,
    li(searchToggle(set::open($browseType == 'bySearch')))
);

toolbar
(
    item(set(array
    (
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'ghost text-darker',
        'url'   => createLink('product', 'export', $browseType, "status=$browseType&orderBy=$orderBy"),
    ))),
    div(setClass('nav-divider')),
    $config->systemMode == 'ALM' ? item(set(array
    (
        'text'  => $lang->product->editLine,
        'icon'  => 'edit',
        'class' => 'ghost',
        'url'   => createLink('product', 'manageLine', $browseType),
    ))) : NULL,
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
    set::footPager(usePager()),
    set::nested(true),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
