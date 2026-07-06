<?php
declare(strict_types=1);
namespace zin;

class aiFormInject extends wg
{
    protected static array $injectedPages = array();

    protected static array $defineProps = array(
        'module:string',
        'method:string',
        'enablePending?:bool=true',
        'enableInject?:bool=true',
        'enableAudit?:bool=true',
    );

    public static function getPageJS(): ?string
    {
        $jsFile = __DIR__ . DS . 'js' . DS . 'v1.js';
        return file_exists($jsFile) ? file_get_contents($jsFile) : null;
    }

    protected function build()
    {
        global $app;

        if(!empty($app->installing)) return null;

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

        $page = "{$module}.{$method}";
        if(isset(static::$injectedPages[$page])) return null;
        static::$injectedPages[$page] = true;

        if($this->prop('enablePending'))    $this->injectPendingFormData($module, $method);
        if($this->prop('enableInject'))     $this->injectInputData($module, $method);
        if($this->prop('enableAudit'))      $this->injectAuditControls($module, $method);

        return null;
    }

    protected function injectPendingFormData(string $module, string $method): void
    {
        $pendingData = $_SESSION['aiPendingFormData'] ?? null;
        if(empty($pendingData)) return;

        unset($_SESSION['aiPendingFormData']);

        jsVar('window.zentaoAIFormInjectPendingData', $pendingData);
        pageJS('window.zentaoAIFormInject && window.zentaoAIFormInject.applyPendingFormData();');
    }

    protected function injectInputData(string $module, string $method): void
    {
        global $app;

        $injectData = $_SESSION['aiInjectData'][$module][$method] ?? null;
        if(empty($injectData)) return;

        unset($_SESSION['aiInjectData'][$module]);

        jsVar('window.aiInjectSuccess', $app->lang->ai->dataInject->success);
        jsVar('window.aiInjectFail',    $app->lang->ai->dataInject->fail);
        jsVar('window.injectData',      $injectData);
        pageJS('window.zentaoAIFormInject && window.zentaoAIFormInject.applyInjectData();');
    }

    protected function injectAuditControls(string $module, string $method): void
    {
        global $config, $app;

        if(!isset($_SESSION['aiPrompt']['prompt']) || empty($_SESSION['aiPrompt']['objectId'])) return;
        if(!isset($config->ai->injectAuditButton->locations[$module][$method])) return;

        $prompt   = $_SESSION['aiPrompt']['prompt'];
        $objectId = $_SESSION['aiPrompt']['objectId'];
        $isAudit  = isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60;

        $publishBtn   = html::commonButton($app->lang->ai->promptPublish, "id='promptPublish' data-promptId=$prompt->id", 'btn btn-primary btn-wide ajax-submit');
        $exitBtnType  = $module == 'doc' ? 'btn' : 'btn btn-wide';
        $exitAuditBtn = html::commonButton($app->lang->ai->audit->exit, "id='promptAuditExit'", $exitBtnType);

        $location      = $config->ai->injectAuditButton->locations[$module][$method];
        $actionConfig  = $location['action'];
        $toolbarConfig = $location['toolbar'];

        $regenButton = html::linkButton('<i class="icon icon-refresh muted"></i> ' . $app->lang->ai->audit->regenerate, helper::createLink('ai', 'promptexecute', "promptId=$prompt->id&objectId=$objectId"), 'self', "id='promptRegenerate'", 'btn ghost');
        $auditButton = html::commonButton($app->lang->ai->audit->designPrompt, 'id="promptAudit" data-toggle="modal" data-type="iframe" data-url="' . helper::createLink('ai', 'promptaudit', "promptId=$prompt->id&objectId=$objectId") . '"', 'btn btn-info iframe');

        $toolbarContainer = $toolbarConfig->targetContainer;
        $toolbarMethod    = $toolbarConfig->injectMethod;
        $toolbarStyles    = empty($toolbarConfig->containerStyles) ? '{}' : $toolbarConfig->containerStyles;
        $toolbarClass     = $toolbarConfig->class ?? '';
        $buttonHTML       = $isAudit ? "<div class='btn-group'>$regenButton $auditButton</div>" : $regenButton;
        $loadingText      = $app->lang->ai->execute->loading;

        jsVar('window.zentaoAIFormInjectAuditConfig', array(
            'actionMode'       => $module == 'doc' ? 'doc' : 'default',
            'actionContainer'  => $actionConfig->targetContainer,
            'actionMethod'     => $actionConfig->injectMethod,
            'actionStyles'     => empty($actionConfig->containerStyles) ? '{}' : $actionConfig->containerStyles,
            'publishButton'    => $publishBtn,
            'exitButton'       => $exitAuditBtn,
            'docExitContainer' => '#mainContent #headerBox td:first-child',
            'toolbarContainer' => $toolbarContainer,
            'toolbarMethod'    => $toolbarMethod,
            'toolbarStyles'    => $toolbarStyles,
            'toolbarClass'     => $toolbarClass,
            'buttonHTML'       => $buttonHTML,
            'loadingText'      => $loadingText,
            'initAction'       => $isAudit,
            'injectToolbar'    => true,
        ));
        pageJS('window.zentaoAIFormInject && window.zentaoAIFormInject.initAuditControls();');
    }
}
