<?php
/**
 * The ai prompt menu ui view file of ai module of ZenTaoPMS.
 *
 * This view file is used to print the prompt menu, acts just like header php files.
 * Prompt menus are generated with php and injected with javascript. A lot of hacking
 * went into this, so please don't touch it unless you know what you are doing.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */

namespace zin;

h::css("
.detail-view > .detail-body > .detail-main > .detail-sections:first-child > .detail-section {position: relative; z-index: 1;}
.detail-view > .detail-body > .detail-main > .detail-sections:first-child > .detail-section:first-child {z-index: 2;}
");

$generateAgents = function($agents)
{
    $agentIds   = array();
    $agentCodes = array();
    $agentList  = explode(',', $agents);
    foreach($agentList as $agent)
    {
        $agent = trim($agent);
        if($agent === '') continue;

        if(strpos($agent, 'zt_') === 0)
        {
            $agentCodes[] = $agent;
        }
        elseif(is_numeric($agent))
        {
            $agentIds[] = $agent;
        }
    }

    if(!empty($agentCodes))
    {
        $codeAgents = $this->ai->getAgentsByCodes($agentCodes, 'active');
        foreach($codeAgents as $codeAgent) $agentIds[] = $codeAgent->id;
    }
    return $agentIds;
};

$promptMenuInject = function() use ($generateAgents)
{
    if(isInModal()) return;

    $this->loadModel('ai');
    if(!commonModel::hasPriv('ai', 'promptExecute')) return;

    $this->app->loadLang('ai');
    $this->app->loadConfig('ai');
    $module    = $this->app->getModuleName();
    $method    = $this->app->getMethodName();
    $isDocApp  = $module === 'doc' && $method === 'app';
    $useMethod = $method;
    if($isDocApp) $method = 'view';

    $showOnList     = in_array($module, ['product', 'project', 'projectstory', 'execution']) && in_array($useMethod, ['browse', 'story', 'task']);
    $isOtherDoc     = $isDocApp && $this->app->tab != 'doc';
    $showOnList     = $showOnList || ($isOtherDoc);
    $isProjectStory = $module === 'product' && $useMethod === 'browse' && $this->app->tab == 'project';
    if($showOnList) $method = 'view';
    if($isOtherDoc || $isProjectStory)
    {
        $module    = $this->app->tab;
        $useMethod = 'view';
    }

    if(!isset($this->config->ai->menuPrint->locations[$module][$method])) return;

    $menuOptions = $this->config->ai->menuPrint->locations[$module][$method];
    $prompts     = $this->ai->getPromptsForUser($menuOptions->module);
    $prompts     = $this->ai->filterPromptsForExecution($prompts, true);
    $btnName     = $this->lang->ai->prompts->common;

    $promptIds = array_column($prompts, 'id');
    $canAssign = !empty($this->config->enableAITeammate) && hasPriv('aiteammate', 'assignagent') && $this->config->edition != 'open';
    $teammates = array();
    if(!empty($prompts) && $canAssign) $teammates = $this->loadModel('aiteammate')->browse('0');

    $showTeammates = array_filter($teammates, function($item) use ($promptIds, $generateAgents)
    {
        if(empty($item->agents)) return false;
        $agents = $generateAgents($item->agents);
        return !empty(array_intersect($promptIds, $agents));
    });
    if(!empty($showTeammates)) $showTeammates = array_values($showTeammates);

    $assignedBtnName = '';
    if($canAssign) $assignedBtnName = sprintf($this->lang->ai->promptMenu->assignedTo, $this->lang->aiteammate->common);
    if($isDocApp && $this->app->tab == 'doc')
    {
        h::globalJS
        (
            'window.docAIPrompts = ' . json_encode($prompts) . ";\n",
            'window.docAIPromptLang = ' . json_encode(array('dropdownTitle' => $btnName, 'statuses' => $this->lang->ai->prompts->statuses)) . ";\n",
            'window.docAITeammates = ' . json_encode($showTeammates) . ";\n",
            'window.docAITeammateLang = ' . json_encode(array('dropdownTitle' => $assignedBtnName, 'nameLabel' => $this->lang->ai->promptMenu->assignedTo)) . ";\n",
        );
        return;
    }

    if($module === 'productplan' && $method === 'view')
    {
        /* 子计划不显示“拆分子计划智能体” */
        $plan = data('plan');
        if(is_object($plan) && $plan->parent > 0)
        {
            $prompts = array_filter($prompts, function($prompt)
            {
                return $prompt->module !== 'productplan' || $prompt->targetForm !== 'productplan.create';
            });
        }
    }

    if(empty($prompts)) return;

    $aiSuggestions   = [];
    $isReleaseView   = $module == 'release' && $method == 'view';
    $className       = $isReleaseView ? 'mt-1' : '';
    $html            = "<div class='flex gap-2 inline-block pull-right ml-2 $className'>";
    $objectVarName   = empty($menuOptions->objectVarName) ? $menuOptions->module : $menuOptions->objectVarName;
    $currentObjectId = !empty($this->view->$objectVarName) ? $this->view->$objectVarName->id : 0;
    $pageInfo        = "$module,$method";
    if(!$showOnList)
    {
        $html .= '<div class="prompts dropdown' . ((isset($menuOptions->class) ? ' ' . $menuOptions->class : '') . (isset($menuOptions->dropdownClass) ? ' ' . $menuOptions->dropdownClass : '')) . '"><button class="btn ai-styled size-sm size-sm font-medium' . (isset($menuOptions->buttonClass) ? ' ' . $menuOptions->buttonClass : '') . '" type="button" data-toggle="dropdown" data-placement="' . zget($menuOptions, 'buttonPlacement', 'bottom-end') . '"><i class="icon icon-lightning"></i>' . $btnName . '<span class="caret-down"></span></button><menu class="dropdown-menu menu">';
        foreach($prompts as $prompt)
        {
            $html .= '<li class="menu-item">';
            $html .= html::a
            (
                helper::createLink('ai', 'promptExecute', "promptId=$prompt->id&objectId=$currentObjectId&auto=0"),
                $prompt->name . ($prompt->status != 'active' ? '<span class="label size-sm gray-500-pale ring-gray-500" style="margin-left: 4px; white-space: nowrap;">' . $this->lang->ai->prompts->statuses[$prompt->status] . '</span>' : ''),
                '',
                "class='prompt ajax-submit' style='width: 100%;'" . (empty($prompt->unauthorized) ? '' : ' disabled') . (empty($prompt->desc) ? '' : " title='$prompt->desc'"),
                'btn ghost size-sm font-medium text-left'
            );
            $html .= '</li>';
        }
        $html .= '</menu></div>';

        if($canAssign && !empty($showTeammates))
        {
            $html    .= '<div class="prompts dropdown inline-block"><button class="btn ai-styled size-sm size-sm font-medium" type="button" data-toggle="dropdown" data-placement="' . zget($menuOptions, 'buttonPlacement', 'bottom-end') . '"><i class="icon icon-hand-right"></i>' . $assignedBtnName . '<span class="caret-down"></span></button><menu class="dropdown-menu menu">';
            foreach($showTeammates as $teammate)
            {
                $avatar = html::avatar(array('avatar' => $teammate->avatar, 'account' => $teammate->name), '20', 'rounded-full');
                $name   = sprintf($this->lang->ai->promptMenu->assignedTo, $teammate->name);
                $html  .= '<li class="menu-item">';
                $html  .= html::a
                (
                    helper::createLink('aiteammate', 'assignagent', "teammateID=$teammate->id&objectType=$objectVarName&objectID=$currentObjectId&pageInfo=$pageInfo"),
                    $avatar . $name,
                    '',
                    "style='justify-content: flex-start;' data-placement='left' data-toggle='modal' data-size='sm' title='$name'",
                    'btn ghost size-sm font-medium text-left'
                );
                $html .= '</li>';
            }
            $html .= '</menu></div>';
        }

        $html .= '</div>';
    }
    else
    {
        $page     = "$module-$useMethod";
        $objectID = $currentObjectId;
        if($isDocApp)
        {
            $objectID = $this->view->objectID;
            $page     = "doc-app";
        }
        if($isProjectStory) $page = "product-browse";

        if($canAssign && !empty($showTeammates))
        {
            foreach($showTeammates as $teammate)
            {
                $name     = sprintf($this->lang->ai->promptMenu->assignedTo, $teammate->name);
                $url      = helper::createLink('aiteammate', 'assignagent', "teammateID=$teammate->id&objectType=$objectVarName&objectID=$objectID&pageInfo=$pageInfo&from=global");
                $btnProps = (object)['data-url' => $url, 'data-toggle' => 'modal', 'data-size' => 'sm'];
                $aiSuggestions[] = (object)['id' => "zt_teammate_$teammate->id", 'title' => $name, 'hint' => $teammate->desc, 'page' => $page, 'btnProps' => $btnProps];
            }
        }

        foreach($prompts as $prompt)
        {
            $zentaoAgent = (object)['agentID' => $prompt->id, 'objectID' => $objectID];
            $aiSuggestions[] = (object)['id' => "zt_agent_$prompt->id", 'title' => $prompt->name, 'hint' => $prompt->desc, 'page' => $page, 'zentaoAgent' => $zentaoAgent];
        }
        $html = '';
    }

    /* Assemble injector script. */
    $script = <<< JAVASCRIPT
    (() => {
        if(!window.top.zai) return;
        const container = window.frameElement?.closest('.load-indicator');
        if(container && container.dataset.loading)
        {
            delete container.dataset.loading;
            container.classList.remove('loading');
            container.classList.remove('no-delay');
        }
    JAVASCRIPT;
    $script .= 'window.aiSuggestions =' . json_encode($aiSuggestions) . ';';
    $script .= 'let $aiMenu = $("' . $menuOptions->targetContainer . '").first();';
    $script .= 'if(!$aiMenu.length) $aiMenu = $("#mainContent .ai-menu-box").empty();';
    $script .= 'else $aiMenu.find(".prompts.dropdown").remove();';
    $script .= '$aiMenu.' . (!empty($menuOptions->injectMethod) ? $menuOptions->injectMethod : 'append') . "(`$html`).css('z-index', 20);\n";
    $script .= <<< JAVASCRIPT
        $('[data-toggle="popover"]').popover({template: '<div class="popover"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'});
    JAVASCRIPT;
    $script .= count($prompts) > 1 ? "$('.prompts.dropdown .dropdown-menu').on('click', 'a', e =>" : "$('.prompt').on('click', e =>";
    $script .= <<< JAVASCRIPT
        {
            if(!container) return;
            container.dataset.loading = e.target.querySelector('.label') ? '{$this->lang->ai->execute->auditing}' : '{$this->lang->ai->execute->loading}';
            container.classList.add('loading');
            container.classList.add('no-delay');

            /* Checks for session storage to cancel loading status (see inputinject.html.php). */
            sessionStorage.removeItem('ai-prompt-data-injected');
            const loadCheckInterval = setInterval(() =>
            {
                if(sessionStorage.getItem('ai-prompt-data-injected'))
                {
                    if(container && container.dataset.loading)
                    {
                        delete container.dataset.loading;
                        container.classList.remove('loading');
                        container.classList.remove('no-delay');
                    }

                    sessionStorage.removeItem('ai-prompt-data-injected');
                    clearInterval(loadCheckInterval);
                }
            }, 200);
        });
    })();
    JAVASCRIPT;

    /* Perform injection. */
    if(isset($menuOptions->stylesheet)) pageCSS($menuOptions->stylesheet);
    h::globalJS($script);
};
$promptMenuInject();
