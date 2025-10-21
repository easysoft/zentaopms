<?php
declare(strict_types=1);
namespace zin;

$viewType = $this->cookie->aiPromptsViewType ? $this->cookie->aiPromptsViewType : 'card';

featureBar(set::current($status), set::linkParams("module={$module}&status={key}"));
toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(
            array(
                'icon'      => 'format-list-bulleted',
                'class'     => 'btn-icon switchButton' . ($viewType == 'list' ? ' text-primary' : ''),
                'data-type' => 'list',
                'hint'      => $lang->ai->prompts->viewTypeList['list']
            ),
            array(
                'icon'      => 'cards-view',
                'class'     => 'btn-icon switchButton' . ($viewType == 'card' ? ' text-primary' : ''),
                'data-type' => 'card',
                'hint'      => $lang->ai->prompts->viewTypeList['card']
            )
        )
    ))),
    $this->config->edition != 'open' && common::hasPriv('ai', 'createprompt') ? item(set(array(
        'class'       => 'primary',
        'icon'        => 'plus',
        'text'        => $lang->ai->prompts->create,
        'url'         => inlink('createprompt'),
        'data-toggle' => 'modal',
        'data-size'   => 'sm'
    ))) : null
);

$cols    = $config->ai->dtable->prompts;
$prompts = initTableData($prompts, $cols, $this->ai);
foreach($prompts as $prompt)
{
    if($prompt->targetForm)
    {
        $targetFormPath = explode('.', $prompt->targetForm);
        if(count($targetFormPath) == 2) $prompt->targetFormLabel = $lang->ai->targetForm[$targetFormPath[0]]['common'] . ' / ' . $lang->ai->targetForm[$targetFormPath[0]][$targetFormPath[1]];
    }
}

unset($lang->ai->prompts->modules['']);
$moduleList = $this->config->edition == 'open' ? array_intersect_key($lang->ai->prompts->modules, array_flip($promptModules)) : $lang->ai->prompts->modules;
$moduleTree = array();
$index      = 1;
$activeKey  = 0;
foreach($moduleList as $moduleKey => $moduleName)
{
    $item = new stdClass();
    $item->id     = $index;
    $item->parent = 0;
    $item->name   = $moduleName;
    $item->url    = inlink('prompts', "module=$moduleKey");
    if($moduleKey == $module) $activeKey = $item->id;
    $moduleTree[] = $item;
    $index++;
}

sidebar
(
    moduleMenu
    (
        set::showDisplay(false),
        set::modules($moduleTree),
        set::activeKey($activeKey),
        set::closeLink(inlink('prompts'))
    )
);

$promptCard = function($prompt) use ($lang)
{
    $draftTag = $prompt->status === 'draft'
        ? span(
            setClass('draft-tag'),
            '未发布'
        )
        : null;
    return div(
        setClass('prompt-card'),
        a(
            set::href(inlink('promptview', "id={$prompt->id}")),
            h3(
                setClass('card-title'),
                set::title($prompt->name),
                span($prompt->name),
                $draftTag
            ),
            div(
                setClass('card-description'),
                set::title($prompt->desc),
                $prompt->desc
            ),
            div(
                setClass('card-meta'),
                div(
                    setClass('creator'),
                    img(),
                    span($prompt->createdBy)
                ),
                span(
                    setClass('created-date'),
                    sprintf('创建时间：%s', $prompt->createdDate)
                )
            )
        )
    );
};

function renderCardView($promptCard, $prompts)
{
    return div(
        set::class('page-prompts'),
        div(
            set::class('prompts-container'),
            array_map($promptCard, $prompts)
        ),
        div(
            set::class('pager-container'),
            pager(set(usePager()))
        )
    );
}

function renderListView($cols, $prompts, $users, $module, $status, $orderBy, $pager, $lang)
{
    return dtable
    (
        set::cols($cols),
        set::data($prompts),
        set::userMap($users),
        set::orderBy($orderBy),
        set::sortLink(inlink('prompts', "module={$module}&status={$status}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footPager(usePager()),
        set::emptyTip($lang->ai->prompts->emptyList)
    );
}

if($viewType == 'list')
{
    renderListView($cols, $prompts, $users, $module, $status, $orderBy, $pager, $lang);
}
else
{
    renderCardView($promptCard, $prompts);
}
