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

$buildDropdown = function($prompt) use ($config)
{
    $items = array();

    if(!empty($prompt->actions))
    {
        foreach($prompt->actions as $action)
        {
            $actionName = $action['name'];
            $disabled   = $action['disabled'];

            if(!isset($config->ai->actionList[$actionName])) continue;

            $actionConfig = $config->ai->actionList[$actionName];

            $item = array(
                'text'     => $actionConfig['text'],
                'disabled' => $disabled
            );

            if(isset($actionConfig['url']))
            {
                if(is_array($actionConfig['url']))
                {
                    $params = str_replace('{id}', (string)$prompt->id, $actionConfig['url']['params']);
                    $item['url'] = helper::createLink($actionConfig['url']['module'], $actionConfig['url']['method'], $params);
                }
                else
                {
                    $item['url'] = str_replace(
                        array('{id}', '{module}', '{targetForm}'),
                        array((string)$prompt->id, $prompt->module, $prompt->targetForm),
                        $actionConfig['url']
                    );
                }
            }

            if(isset($actionConfig['className'])) $item['innerClass'] = $actionConfig['className'];
            if(isset($actionConfig['data-toggle'])) $item['data-toggle'] = $actionConfig['data-toggle'];
            if(isset($actionConfig['data-size'])) $item['data-size'] = $actionConfig['data-size'];
            if(isset($actionConfig['data-confirm'])) $item['data-confirm'] = $actionConfig['data-confirm'];
            if(isset($actionConfig['data-app'])) $item['data-app'] = $actionConfig['data-app'];

            $items[] = $item;
        }
    }

    if(empty($items)) return null;

    return dropdown(
        btn(
            setClass('ghost size-sm card-action-btn'),
            set::icon('ellipsis-v')
        ),
        set::items($items),
        set::placement('bottom-end'),
        set::caret(false)
    );
};

$promptCard = function($prompt) use ($lang, $buildDropdown)
{
    $draftTag = $prompt->status === 'draft'
        ? span(
            setClass('draft-tag'),
            $lang->ai->prompts->statuses['draft']
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
                    sprintf($lang->ai->prompts->createdDate . '：%s', substr($prompt->createdDate, 0, 10))
                )
            )
        ),
        $buildDropdown($prompt)
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
