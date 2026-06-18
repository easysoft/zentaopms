<?php
declare(strict_types=1);
namespace zin;

class aiFormInject extends wg
{
    protected static array $defineProps = array(
        'module:string',
        'method:string',
        'enableSkipFields?:bool=true',
        'enablePending?:bool=true',
        'enableInject?:bool=true',
    );

    protected function build()
    {
        global $app, $config;

        $app->control->loadModel('ai');
        if(!commonModel::hasPriv('ai', 'promptExecute')) return null;
        if(!$app->control->loadModel('zai')->getSetting()) return null;

        $app->loadLang('ai');
        $app->loadConfig('ai');

        $module = $this->prop('module');
        $method = $this->prop('method');
        if(empty($module) || empty($method))
        {
            $this->triggerError('The module and method properties of widget "aiFormInject" are required.');
            return null;
        }
        $page   = "{$module}.{$method}";

        $universalFormPages = $config->ai->universalFormPages ?? array();
        if(!in_array($page, $universalFormPages)) return null;

        $prompts = $app->control->ai->getPromptsForEntryPage($module, $method, 'form');
        if(empty($prompts)) return null;

        if($this->prop('enableSkipFields')) $this->injectSkipFields($module, $method);

        if($this->prop('enablePending')) $this->injectPendingFormData($module, $method);

        if($this->prop('enableInject')) $this->injectInputData($module, $method);

        return null;
    }

    protected function injectSkipFields(string $module, string $method): void
    {
        global $config;
        $skipFields = $config->ai->perPageSkipFields[$module][$method] ?? null;
        if($skipFields) jsVar('window.zentaoSkipFields', $skipFields);
    }

    protected function injectPendingFormData(string $module, string $method): void
    {
        global $config;
        $availableForms = $config->ai->availableForms ?? array();
        if(empty($availableForms[$module]) || !in_array($method, $availableForms[$module])) return;

        $pendingData = $_SESSION['aiPendingFormData'] ?? null;
        if(empty($pendingData)) return;

        unset($_SESSION['aiPendingFormData']);

        $data = json_encode($pendingData);
        pageJS(<<<JS
        (() => {
            const data = {$data};
            if(!data) return;
            const tryFill = (tries) => {
                let form = \$('#mainContainer form').first();
                if(!form.length) form = \$('form').first();
                if(form.length && window.zui?.zentaoFormHelper) {
                    window.zui.zentaoFormHelper(form).fillFormData(data);
                    return;
                }
                if(tries < 20) setTimeout(() => tryFill(tries + 1), 400);
            };
            setTimeout(() => tryFill(0), 600);
        })();
        JS);
    }

    protected function injectInputData(string $module, string $method): void
    {
        $injectData = $_SESSION['aiInjectData'][$module][$method] ?? null;
        if(empty($injectData)) return;

        unset($_SESSION['aiInjectData'][$module]);
        jsVar('window.injectData', $injectData);
    }
}
