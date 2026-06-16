<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'aiagentmenu' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'aiteammatemenu' . DS . 'v1.php';

class aiAgentEntry extends wg
{
    protected static array $defineProps = array(
        'module?:string',
        'method?:string',
        'objectID?:int',
        'objectVarName?:string',
        'type?:string="auto"',
        'renderMode?:string="full"',
        'showAgent?:bool=true',
        'showTeammate?:bool=true',
        'loadJs?:bool=true',
        'loadCss?:bool=true',
    );

    private static bool $resourcesImported = false;

    protected function importResources(): ?node
    {
        if(static::$resourcesImported) return null;
        static::$resourcesImported = true;

        global $app;
        $webRoot = $app->getWebRoot();
        $nodes = array();

        if($this->prop('loadCss'))
        {
            $cssFile = $app->getAppRoot() . 'www/js/zui3/ai.css';
            if(file_exists($cssFile)) pageCSS(file_get_contents($cssFile));
        }

        if($this->prop('loadJs'))
        {
            $nodes[] = h::importJs($webRoot . 'js/zui3/ai.js');
        }

        return empty($nodes) ? null : (count($nodes) === 1 ? $nodes[0] : html(...$nodes));
    }

    protected function build(): ?node
    {
        global $app, $config;

        $renderMode = $this->prop('renderMode');
        $resourceNodes = $this->importResources();
        if($renderMode === 'resources') return null;

        $app->control->loadModel('ai');
        if(!commonModel::hasPriv('ai', 'promptExecute')) return $resourceNodes;

        $app->loadLang('ai');
        $app->loadConfig('ai');

        $module = $this->prop('module') ?: $app->getModuleName();
        $method = $this->prop('method') ?: $app->getMethodName();

        $entryNode = null;
        if($module === 'doc' && $method === 'app')
        {
            $entryNode = $this->buildDocApp();
        }
        else
        {
            $type = $this->resolvePageType($module, $method, $config);

            $prompts = $this->fetchPrompts($module, $method, $type, $app, $config);
            if(!empty($prompts))
            {
                $promptIds = array_column($prompts, 'id');
                $teammateItems = $this->fetchTeammates($promptIds, $app, $config);

                $this->buildSuggestions($prompts, $teammateItems, $module, $method, $type, $config);

                $entryNode = $this->buildEntry($prompts, $teammateItems, $module, $method, $type, $app, $config);
            }
        }

        if($renderMode === 'entry') return $entryNode;
        if($resourceNodes === null) return $entryNode;
        if($entryNode === null) return $resourceNodes;
        return html($resourceNodes, $entryNode);
    }

    protected function buildEntry(array $prompts, array $teammateItems, string $module, string $method, string $type, $app, $config): ?node
    {
        $children = array();
        $objectID = $this->getObjectID($app);
        $objectVarName = $this->getObjectVarName($module, $method, $config);

        if($this->prop('showAgent'))
        {
            $children[] = aiAgentMenu
            (
                set::items($prompts),
                set::isFormPage($type === 'form'),
                set::objectID($objectID),
            );
        }

        if($this->prop('showTeammate') && !empty($teammateItems))
        {
            $children[] = aiTeammateMenu
            (
                set::items($teammateItems),
                set::isFormPage($type === 'form'),
                set::module($module),
                set::method($method),
                set::objectID($objectID),
                set::objectVarName($objectVarName),
            );
        }

        if(empty($children)) return null;

        return div
        (
            setClass('flex gap-2 inline-block pull-right mx-2'),
            $children,
        );
    }

    protected function buildSuggestions(array $prompts, array $teammateItems, string $module, string $method, string $type, $config): void
    {
        if($type === 'form' || $type === 'list') return;

        global $app;
        $objectID = $this->getObjectID($app);
        $objectVarName = $this->getObjectVarName($module, $method, $config);
        $suggestions = array();

        foreach($prompts as $prompt)
        {
            $suggestions[] = array(
                'id'          => "zt_agent_{$prompt->id}",
                'title'       => $prompt->name,
                'hint'        => $prompt->desc,
                'page'        => "{$module}-{$method}",
                'zentaoAgent' => array('agentID' => $prompt->id, 'objectID' => $objectID),
            );
        }

        foreach($teammateItems as $teammate)
        {
            $suggestions[] = array(
                'id'       => "zt_teammate_{$teammate->id}",
                'title'    => sprintf($GLOBALS['lang']->ai->promptMenu->assignedTo, $teammate->name),
                'hint'     => $teammate->desc,
                'page'     => "{$module}-{$method}",
                'btnProps' => (object)array(
                    'data-url'    => helper::createLink('aiteammate', 'assignagent',
                        "teammateID={$teammate->id}&objectType={$objectVarName}&objectID={$objectID}&pageInfo={$module},{$method}&from=global"),
                    'data-toggle' => 'modal',
                    'data-size'   => 'sm',
                ),
            );
        }

        jsVar('window.aiSuggestions', $suggestions);
    }

    protected function resolvePageType(string $module, string $method, $config): string
    {
        $type = $this->prop('type');
        if($type !== 'auto') return $type;

        $menuConfig = $config->ai->menuPrint->locations[$module][$method] ?? null;
        if(!$menuConfig) return 'detail';

        return empty($menuConfig->objectVarName) ? 'form' : 'detail';
    }

    protected function getObjectID($app): int
    {
        $objectID = $this->prop('objectID');
        if($objectID) return (int)$objectID;

        $objectVarName = $this->prop('objectVarName');
        if(!$objectVarName) return 0;

        $viewData = $app->view ?? null;
        if($viewData && isset($viewData->$objectVarName)) return (int)($viewData->$objectVarName->id ?? 0);
        return 0;
    }

    protected function getObjectVarName(string $module, string $method, $config = null): string
    {
        $objectVarName = $this->prop('objectVarName');
        if($objectVarName) return $objectVarName;

        $menuConfig = $config ? $config->ai->menuPrint->locations[$module][$method] ?? null : null;
        return $menuConfig->objectVarName ?? $menuConfig->module ?? $module;
    }

    protected function fetchPrompts(string $module, string $method, string $type, $app, $config): array
    {
        if($type === 'form') return $app->control->ai->getPromptsForTargetForm($module, $method);

        $menuConfig = $config->ai->menuPrint->locations[$module][$method] ?? null;
        $configModule = $menuConfig->module ?? $module;
        $prompts = $app->control->ai->getPromptsForUser($configModule);
        $prompts = $app->control->ai->filterPromptsForExecution($prompts, true);

        $universalFormPages = (array)$config->ai->universalFormPages;
        if(!empty($universalFormPages))
        {
            $prompts = array_filter($prompts, function($p) use ($universalFormPages)
            {
                return !in_array($p->targetForm, $universalFormPages);
            });
        }

        return array_values($prompts);
    }

    protected function fetchTeammates(array $promptIds, $app, $config): array
    {
        $canAssign = !empty($config->enableAITeammate)
            && hasPriv('aiteammate', 'assignagent')
            && $config->edition != 'open';
        if(!$canAssign || empty($promptIds)) return array();

        $teammates = $app->control->loadModel('aiteammate')->browse('0');
        return array_values(array_filter($teammates, function($t) use ($promptIds, $app)
        {
            if(empty($t->agents)) return false;
            $agentIds = $this->parseAgentIds($t->agents, $app);
            return !empty(array_intersect($promptIds, $agentIds));
        }));
    }

    protected function parseAgentIds(string $agents, $app): array
    {
        $ids = array();
        $list = explode(',', $agents);
        foreach($list as $agent)
        {
            $agent = trim($agent);
            if($agent === '') continue;
            if(strpos($agent, 'zt_') === 0)
            {
                $codeAgents = $app->control->ai->getAgentsByCodes(array($agent), 'active');
                foreach($codeAgents as $ca) $ids[] = (int)$ca->id;
            }
            elseif(is_numeric($agent)) $ids[] = (int)$agent;
        }
        return $ids;
    }

    protected function buildDocApp(): ?node
    {
        global $app, $config;

        $app->loadLang('aiteammate');

        $prompts = $app->control->ai->getPromptsForUser('doc');
        $prompts = $app->control->ai->filterPromptsForExecution($prompts, true);

        jsVar('window.docAIPrompts', $prompts);
        jsVar('window.docAIPromptLang', array(
            'dropdownTitle' => $GLOBALS['lang']->ai->prompts->common,
            'statuses'      => $GLOBALS['lang']->ai->prompts->statuses,
        ));

        $canAssign = !empty($config->enableAITeammate) && hasPriv('aiteammate', 'assignagent') && $config->edition != 'open';
        if($canAssign)
        {
            $teammates = $app->control->loadModel('aiteammate')->browse('0');
            $promptIds = array_column($prompts, 'id');
            $showTeammates = array_values(array_filter($teammates, function($t) use ($promptIds, $app)
            {
                if(empty($t->agents)) return false;
                $agentIds = $this->parseAgentIds($t->agents, $app);
                return !empty(array_intersect($promptIds, $agentIds));
            }));

            $assignedBtnName = sprintf($GLOBALS['lang']->ai->promptMenu->assignedTo, $GLOBALS['lang']->aiteammate->common);
            jsVar('window.docAITeammates', $showTeammates);
            jsVar('window.docAITeammateLang', array(
                'dropdownTitle' => $assignedBtnName,
                'nameLabel'     => $GLOBALS['lang']->ai->promptMenu->assignedTo,
            ));
        }

        return null;
    }
}
