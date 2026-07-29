<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'aiagentmenu' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'aiteammatemenu' . DS . 'v1.php';

class aiAgentEntry extends wg
{
    protected static array $defineProps = array(
        'module:string',
        'method:string',
        'objectID?:int',
        'objectVarName?:string',
        'type:string',
        'showAgent?:bool=true',
        'showTeammate?:bool=true',
    );

    public static function getPageCSS(): ?string
    {
        global $app;
        $cssFile = $app->getAppRoot() . 'www/js/zui3/ai.css';
        return file_exists($cssFile) ? file_get_contents($cssFile) : null;
    }

    public static function getPageJS(): ?string
    {
        global $app;

        $scripts  = array();
        $commonJS = $app->getAppRoot() . 'www/js/zui3/ai.js';
        $entryJS  = __DIR__ . DS . 'js' . DS . 'v1.js';

        if(file_exists($commonJS)) $scripts[] = file_get_contents($commonJS);
        if(file_exists($entryJS))  $scripts[] = file_get_contents($entryJS);

        return empty($scripts) ? null : implode("\n", $scripts);
    }

    protected function build(): ?node
    {
        global $app, $config;

        if(!empty($app->installing)) return null;

        $app->control->loadModel('ai');
        if(!commonModel::hasPriv('ai', 'promptExecute')) return null;
        if(!$this->isZAIConfigured($app)) return null;

        $app->loadLang('ai');
        $app->loadConfig('ai');

        $module = $this->prop('module');
        $method = $this->prop('method');
        $type   = $this->prop('type');

        if(empty($module) || empty($method) || empty($type))
        {
            $this->triggerError('The module, method and type properties of widget "aiAgentEntry" are required.');
            return null;
        }

        if($type === 'detail' && !$this->prop('objectID'))
        {
            $this->triggerError('The objectID property of widget "aiAgentEntry" is required when type is "detail".');
            return null;
        }

        if($type === 'detail' && !$this->prop('objectVarName'))
        {
            $this->triggerError('The objectVarName property of widget "aiAgentEntry" is required when type is "detail".');
            return null;
        }

        $entryNode = null;
        if($module === 'doc' && $method === 'app')
        {
            $entryNode = $this->buildDocApp();
        }
        else
        {
            $prompts = $this->fetchPrompts($module, $method, $type, $app, $config);
            if(!empty($prompts))
            {
                $promptIds     = array_column($prompts, 'id');
                $teammateItems = $this->fetchTeammates($promptIds, $app, $config);

                $this->buildSuggestions($prompts, $teammateItems, $module, $method, $type, $config);

                $entryNode = $this->buildEntry($prompts, $teammateItems, $module, $method, $type, $app, $config);
            }
        }

        return $entryNode;
    }

    protected function buildEntry(array $prompts, array $teammateItems, string $module, string $method, string $type, $app, $config): ?node
    {
        $children         = array();
        $objectID         = $this->getObjectID();
        $availablePrompts = array_values(array_filter($prompts, static fn($prompt) => empty($prompt->unauthorized)));

        $objectVarName = $this->getObjectVarName($module, $method, $config);
        if($this->prop('showAgent') && !empty($availablePrompts))
        {
            if(count($availablePrompts) === 1)
            {
                $singlePrompt  = $availablePrompts[0];
                $clickHandler  = $type === 'form'
                    ? "executeWithFormContext({$singlePrompt->id})"
                    : "callZentaoAgent({$singlePrompt->id}, {$objectID})";

                $promptFields  = $app->control->ai->getPromptFields((int)$singlePrompt->id);
                $fieldsData    = $promptFields ? helper::jsonEncode(array_values($promptFields)) : '[]';
                $allowedFields = $app->control->ai->getFormAllowedFields($module, $method);
                $agentRole     = helper::jsonEncode(($singlePrompt->role ?? '') . (!empty($singlePrompt->characterization) ? "\n{$singlePrompt->characterization}" : ''));
                $agentPurpose  = helper::jsonEncode($singlePrompt->purpose ?? '');
                $agentSkills   = ($config->edition != 'open' && method_exists($app->control->ai, 'getPromptSkillIDs'))
                    ? $app->control->ai->getPromptSkillIDs($singlePrompt)
                    : [];

                $children[] = btn
                (
                    setClass('btn ai-styled size-sm font-medium'),
                    set::icon('lightning'),
                    set('data-on', 'click'),
                    set('data-call', $clickHandler),
                    set('data-prompt-fields', $fieldsData),
                    set('data-allowed-fields', helper::jsonEncode($allowedFields)),
                    set('data-agent-role', $agentRole),
                    set('data-agent-purpose', $agentPurpose),
                    set('data-agent-skills', helper::jsonEncode($agentSkills)),
                    $singlePrompt->name,
                );
            }
            else
            {
                $children[] = aiAgentMenu
                (
                    set::items($prompts),
                    set::isFormPage($type === 'form'),
                    set::objectID($objectID),
                    set::module($module),
                    set::method($method),
                );
            }
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
            setClass('flex gap-2 inline-block mx-2'),
            $children,
        );
    }

    protected function buildSuggestions(array $prompts, array $teammateItems, string $module, string $method, string $type, $config): void
    {
        if($type === 'form' || $type === 'list') return;

        global $app;
        $objectID = $this->getObjectID();

        $objectVarName = $this->getObjectVarName($module, $method, $config);
        $suggestions   = array();

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

    protected function getObjectID(): int
    {
        return (int)$this->prop('objectID');
    }

    protected function getObjectVarName(string $module, string $method, $config = null): string
    {
        $objectVarName = $this->prop('objectVarName');
        if($objectVarName) return $objectVarName;
        return $module;
    }

    protected function fetchPrompts(string $module, string $method, string $type, $app, $config): array
    {
        if(!in_array($type, array('form', 'detail'))) return array();
        return $app->control->ai->getPromptsForEntryPage($module, $method, $type);
    }

    protected function fetchTeammates(array $promptIds, $app, $config): array
    {
        $canAssign = $this->isTeammateAvailable($app, $config);
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
        $ids  = array();
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

        $canAssign = $this->isTeammateAvailable($app, $config);
        if($canAssign)
        {
            $teammates     = $app->control->loadModel('aiteammate')->browse('0');
            $promptIds     = array_column($prompts, 'id');
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

    protected function isZAIConfigured($app): bool
    {
        return (bool)$app->control->loadModel('zai')->getSetting();
    }

    protected function isTeammateAvailable($app, $config): bool
    {
        if(!$this->isZAIConfigured($app)) return false;
        if(empty($config->enableAITeammate)) return false;
        if($config->edition == 'open') return false;
        if(!hasPriv('aiteammate', 'assignagent')) return false;

        return true;
    }
}
