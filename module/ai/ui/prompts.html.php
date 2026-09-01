<?php
declare(strict_types=1);
namespace zin;

$viewType = $this->cookie->aiPromptsViewType ? $this->cookie->aiPromptsViewType : 'card';

featureBar(set::current($status), set::linkParams("module={$module}&status={key}"));

$canCreate       = $this->config->edition != 'open' && common::hasPriv('ai', 'createprompt');
$createLink      = inlink('promptbasicinfo', "promptID=0&module={$module}");
$timerCreateItem = $canCreate ? array('text' => $lang->ai->timer->create, 'url' => inlink('timerbasicinfo')) : null;
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
    $canCreate ? btngroup
    (
        btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->ai->prompts->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array($timerCreateItem)),
            set::placement('bottom-end')
        )
    ) : null
);

$timerType = isset($lang->ai->agentType->timer) ? $lang->ai->agentType->timer : 'timer';
$timerTag  = isset($lang->ai->timer->tag) ? $lang->ai->timer->tag : '';
jsVar('timerAgentType', $timerType);
jsVar('timerAgentTag', $timerTag);

$cols = $config->ai->dtable->prompts;
$cols['actions']['list'] = $config->ai->actionList;
$prompts = initTableData($prompts, $cols, $this->ai);
initTableActions($cols, 'timerbasicinfo');
foreach($prompts as $prompt)
{
    if($prompt->actionPurpose)
    {
        $prompt->targetFormLabel = $this->ai->getTargetFormLabel($prompt->actionPurpose, true, $prompt->module);
    }

    $designAction = $this->ai->getPromptDesignAction($prompt);
    $isTimerAgent = !empty($prompt->type) && $prompt->type === $timerType;
    if(!empty($prompt->actions) && ($designAction != 'promptbasicinfo' || $isTimerAgent))
    {
        foreach($prompt->actions as $actionKey => &$action)
        {
            if(!is_array($action) || empty($action['name'])) continue;
            if($action['name'] == 'promptbasicinfo' && $designAction != 'promptbasicinfo') $action['name'] = $designAction;
            if($isTimerAgent && $action['name'] == 'promptaudit') unset($prompt->actions[$actionKey]);
        }
        unset($action);
        if($isTimerAgent) $prompt->actions = array_values($prompt->actions);
    }
}

$userListMap = array();
foreach($userList as $user)
{
    $userListMap[$user->account] = $user;
}

$promptProcessObjectList = $lang->ai->prompts->modules;
if(method_exists($this->ai, 'getPromptProcessObjectList')) $promptProcessObjectList = $this->ai->getPromptProcessObjectList();
unset($promptProcessObjectList['']);

$moduleList = $this->config->edition == 'open' ? array_intersect_key($promptProcessObjectList, array_flip($promptModules)) : $promptProcessObjectList;
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
                        array((string)$prompt->id, $prompt->module, $prompt->actionPurpose),
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

$promptCard = function($prompt) use ($lang, $buildDropdown, $userListMap, $timerType, $timerTag)
{
    $creator = isset($userListMap[$prompt->createdBy]) ? $userListMap[$prompt->createdBy] : null;
    $creatorName = $creator ? $creator->realname : $prompt->createdBy;

    $timerLabel = (!empty($prompt->type) && $prompt->type === $timerType && $timerTag !== '')
        ? span(setClass('timer-tag'), $timerTag)
        : null;
    $draftTag = $prompt->status === 'draft'
        ? span(
            setClass('draft-tag'),
            $lang->ai->prompts->statuses['draft']
        )
        : null;
    return div(
        setClass('prompt-card'),
        a(
            hasPriv('ai', 'promptview') ? set::href(inlink('promptview', "id={$prompt->id}")) : null,
            h3(
                setClass('card-title'),
                set::title($prompt->name),
                span(setClass('prompt-name'), $prompt->name),
                $timerLabel,
                $draftTag
            ),
            div(
                setClass('card-description'),
                set::title($prompt->desc),
                $prompt->desc
            ),
            div(
                setClass('card-meta'),
                span(
                    setClass('created-date'),
                    sprintf($lang->ai->prompts->createdDate . '：%s', substr($prompt->createdDate, 0, 10))
                ),
                div(
                    setClass('creator'),
                    set::title($creatorName),
                    avatar(
                        set::size('sm'),
                        set::text($creatorName),
                        $creator && !empty($creator->avatar) ? set::src($creator->avatar) : null
                    )
                )
            )
        ),
        $buildDropdown($prompt)
    );
};

function renderCardView($promptCard, $prompts)
{
    return div(
        setClass('page-prompts'),
        div(
            setClass('prompts-container'),
            array_map($promptCard, $prompts)
        ),
        div(
            setClass('pager-container'),
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
        set::onRenderCell(jsRaw('window.onRenderPromptNameCell')),
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
