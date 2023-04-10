<?php
namespace zin;

include_once './data.php';

$cols = array_values($config->product->all->dtable->fieldList);

/* TODO: implements extend fields. */
$extendFields = $this->product->getFlowExtendFields();

$data         = array();
$totalStories = 0;
$programs     = array();
foreach($productStructure as $programID => $program)
{
    if(isset($programLines[$programID]))
    {
        foreach($programLines[$programID] as $lineID => $lineName)
        {
            if(!isset($program[$lineID]))
            {
                $program[$lineID] = array();
                $program[$lineID]['product']  = '';
                $program[$lineID]['lineName'] = $lineName;
            }
        }
    }

    if(isset($program['programName']))
    {
        $pro = new stdClass();
        $pro->id     = $program['id'];
        $pro->name   = $program['programName'];
        $pro->parent = null;

        $programs[] = $pro;
    }

    foreach($program as $lineID => $line)
    {
        /* Products of Product Line. */
        if(isset($line['products']) and is_array($line['products']))
        {
            foreach($line['products'] as $productID => $product)
            {
                $item = new stdClass();

                if(!empty($product->PO))
                {
                    $item->PO               = zget($users, $product->PO);
                    $item->POAvatar         = $usersAvatar[$product->PO];
                    $item->POAccount        = $product->PO;
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
                $item->productLine       = $product->line ? $line['lineName'] :  '';
                $item->execution         = rand(0, 10);
                $item->feedback          = rand(0, 100);
                $item->testCaseCoverRate = rand(0, 100);
                $item->releasesOld       = rand(0, 10);
                /* TODO attach extend fields. */

                $data[] = $item;
            }
        }
    }
}

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
                    'title'     => $lang->program->all,
                    'programs'  => $programs,
                    'activeKey' => !empty($programs) ? $programs[0]->id : null,
                    'closeLink' => '#',
                    'onClickItem' => jsRaw('function(e){console.log(e)}')
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
    li(searchToggle()),
    li
    (
        btn
        (
            set::class('ghost'),
            set::icon('fold-all'),
            set::text($lang->sort)
        )
    )
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

js
(
<<<RENDERCELL
window.footerGenerator = function()
{
    const count = this.layout.allRows.filter((x) => x.data.type === "product").length;
    const statistic = '{$lang->product->pageSummary}'.replace('%s', ' ' + count + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(!col || !row || col.name !== 'releases') return result;

    var changed = row.data.releases - row.data.releasesOld;

    if(changed === 0) result[0] = 0;
    if(changed > 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>'};
    if(changed < 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>'};

    return result;
}
RENDERCELL
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::nested(true),
    //set::onRenderCell(jsRaw('window.renderReleaseCountCell')),
    set::onRenderCell(jsRaw('function(result, data){return window.renderReleaseCountCell(result, data);}')),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

sidebar
(
    moduleMenu
    (
        set::productID(1),
        set::title('所有分类'),
        set::activeKey('4'),
        set::closeLink('#'),
    )
);

render();
