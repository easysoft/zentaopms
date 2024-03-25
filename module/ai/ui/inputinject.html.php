<?php
/**
 * The ai input inject ui view file of ai module of ZenTaoPMS.
 *
 * This view file is used to inject ai generated result into
 * input fields. Include this view file in the target forms.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */

namespace zin;

$this->app->loadLang('ai');
$this->app->loadConfig('ai');
$module = $this->app->getModuleName();
$method = $this->app->getMethodName();

/* Inject audit menu, called from within $inputInject(), be aware of javascript assembling abuse. */
$auditInject = function() use($module, $method)
{
    if(!isset($_SESSION['aiPrompt']['prompt']) || empty($_SESSION['aiPrompt']['objectId'])) return;

    $prompt   = $_SESSION['aiPrompt']['prompt'];
    $objectId = $_SESSION['aiPrompt']['objectId'];
    $isAudit  = isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60;

    $auditScript = '';
    if(isset($this->config->ai->injectAuditButton->locations[$module][$method]))
    {
        $publishBtn      = html::commonButton($this->lang->ai->promptPublish, "id='promptPublish' data-promptId=$prompt->id", 'btn btn-primary btn-wide');
        $targetContainer = $this->config->ai->injectAuditButton->locations[$module][$method]['action']->targetContainer;
        $injectMethod    = $this->config->ai->injectAuditButton->locations[$module][$method]['action']->injectMethod;

        $auditScript = <<< JAVASCRIPT
        window.injectAuditAction = () =>
        {
        JAVASCRIPT;

        if($module == 'doc')
        {
            $actionContainerStyles = empty($this->config->ai->injectAuditButton->locations[$module][$method]['action']->containerStyles) ? '{}' : $this->config->ai->injectAuditButton->locations[$module][$method]['action']->containerStyles;
            $exitAuditButton = html::commonButton($this->lang->ai->audit->exit, "id='promptAuditExit'", 'btn');

            $auditScript .= <<< JAVASCRIPT
            const actionContainerStyles = JSON.parse('$actionContainerStyles');
            const injectHTML = `$publishBtn`;
            $(`$targetContainer`)[`$injectMethod`](injectHTML);
            $(`$targetContainer`).css(actionContainerStyles);
            $('#mainContent #headerBox td:first-child').html(`$exitAuditButton`);
            JAVASCRIPT;
        }
        else
        {
            $exitAuditButton = html::commonButton($this->lang->ai->audit->exit, "id='promptAuditExit'", 'btn btn-wide');

            $auditScript .= <<< JAVASCRIPT
            const injectHTML = `$publishBtn $exitAuditButton`;
            $(`$targetContainer`)[`$injectMethod`](injectHTML);
            JAVASCRIPT;
        }

        $auditScript .= <<< JAVASCRIPT
        };
        window.injectAuditToolbar = () =>
        {
        JAVASCRIPT;

        $regenButton = html::linkButton('<i class="icon icon-refresh muted"></i> ' . $this->lang->ai->audit->regenerate, helper::createLink('ai', 'promptexecute', "promptId=$prompt->id&objectId=$objectId"), 'self', "id='promptRegenerate'", 'btn ghost');
        $auditButton = html::commonButton($this->lang->ai->audit->designPrompt, 'data-toggle="modal" data-type="iframe" data-url="' . helper::createLink('ai', 'promptaudit', "promptId=$prompt->id&objectId=$objectId") . '"', 'btn btn-info iframe');
        $targetContainer = $this->config->ai->injectAuditButton->locations[$module][$method]['toolbar']->targetContainer;
        $injectMethod    = $this->config->ai->injectAuditButton->locations[$module][$method]['toolbar']->injectMethod;
        $buttonHTML = $isAudit ? "$regenButton $auditButton" : $auditButton;

        $auditScript .= <<< JAVASCRIPT
        const buttonHTML = `$buttonHTML`;
        JAVASCRIPT;
        $toolbarContainerStyles = empty($this->config->ai->injectAuditButton->locations[$module][$method]['toolbar']->containerStyles) ? '{}' : $this->config->ai->injectAuditButton->locations[$module][$method]['toolbar']->containerStyles;
        $auditScript .= "$(`$targetContainer`).first().$injectMethod(buttonHTML);";
        $auditScript .= <<< JAVASCRIPT
            const toolbarContainerStyles = JSON.parse('$toolbarContainerStyles');
            $(`$targetContainer`).css(toolbarContainerStyles);
        };
        JAVASCRIPT;

        if($isAudit) $auditScript .= 'window.injectAuditAction();';
        $auditScript .= "if(typeof window.injectData !== 'undefined') {window.injectAuditToolbar();}";

        $loadingText = $this->lang->ai->execute->loading;
        $auditScript .= <<< JAVASCRIPT
        const publishButton = document.getElementById('promptPublish');
        if(publishButton)
        {
            publishButton.addEventListener('click', e =>
            {
                e.preventDefault();

                const promptId = publishButton.dataset.promptId;
                const publishLink = document.createElement('a');
                publishLink.href = createLink('ai', 'promptPublish', 'promptId=' + promptId + '&backToTestingLocation=true') + '#app=admin';
                publishLink.style.display = 'none';
                document.body.appendChild(publishLink);
                publishLink.click();

                /* TODO: find a way to close app. */
                // if($.appCode !== 'admin') $.apps.close($.appCode);
            });
        }
        const auditExitButton = document.getElementById('promptAuditExit');
        if(auditExitButton)
        {
            auditExitButton.addEventListener('click', e =>
            {
                e.preventDefault();

                const exitLink = document.createElement('a');
                exitLink.href = createLink('ai', 'promptAudit', 'promptId=' + promptId + '&objectId=0' + '&exit=true') + '#app=admin';
                exitLink.style.display = 'none';
                document.body.appendChild(exitLink);
                exitLink.click();

                /* TODO: find a way to close app. */
                // if($.appCode !== 'admin') $.apps.close($.appCode);
            });
        }
        const regenButton = document.getElementById('promptRegenerate');
        if(regenButton)
        {
            regenButton.addEventListener('click', e =>
            {
                $('body').attr('data-loading', `$loadingText`);
                $('body').addClass('load-indicator loading');
            });
        }
        JAVASCRIPT;
    }
    h::globalJS("(() => {requestAnimationFrame(() => {{$auditScript}});})();");
};

/* Inject input data. */
$inputInject = function() use($module, $method, &$auditInject)
{
    if(!isset($this->config->ai->availableForms[$module]) || !in_array($method, $this->config->ai->availableForms[$module])) return;

    if(isset($_SESSION['aiInjectData']) && isset($_SESSION['aiInjectData'][$module]) && isset($_SESSION['aiInjectData'][$module][$method])) $injectData = $_SESSION['aiInjectData'][$module][$method];
    if(empty($injectData)) return;

    jsVar('window.injectData', $injectData);
    unset($_SESSION['aiInjectData'][$module]);

    $this->app->loadLang('ai');

    h::globalJS(<<< JAVASCRIPT
    (() => {
        window.injectToInputElement = (inputName, data, index, tries = 0) =>
        {
            let name = inputName;
            if(typeof index !== 'undefined') name = name + '[' + index + ']';

            const inputEl = $('[name="' + name + '"]');

            /* Retry if input is not found, sometimes form renders late. */
            if(!inputEl.length && tries < 5) return setTimeout(() => window.injectToInputElement(inputName, data, index, ++tries), 1000);

            const inputType = inputEl.prop('nodeName');
            switch(inputType) // Contains case fallthroughs, on purpose.
            {
                case 'ZEN-EDITOR':
                    /* Set Zen Editor content with its exposed method, loop till editor is ready. */
                    const zenEditorEl = inputEl.get().pop();
                    const setZenEditorData = () =>
                    {
                        try
                        {
                            zenEditorEl.setHTML(data).catch(_ => setTimeout(setZenEditorData, 100));
                        }
                        catch(_)
                        {
                            setTimeout(setZenEditorData, 100);
                        }
                    };
                    setZenEditorData();
                    break;
                case 'INPUT':
                    /* Watch out for zui date pickers. */
                    if(inputEl.hasClass('pick-value'))
                    {
                        if(inputEl.parent().hasClass('date-picker'))
                        {
                            if(inputEl.parent().parent().data().hasOwnProperty('zuiDatetimepicker'))
                            {
                                inputEl.zui('dateTimePicker').$.setValue(data);
                                break;
                            }
                            if(inputEl.parent().parent().data().hasOwnProperty('zuiDatepicker'))
                            {
                                inputEl.zui('datePicker').$.setValue(data);
                                break;
                            }
                        }
                    }
                case 'TEXTAREA':
                    /* Textareas might be controlled by KindEditors. */
                    if(typeof KindEditor !== 'undefined' && KindEditor.instances.length)
                    {
                        const editorInstance = KindEditor.instances.find(e => e.srcElement.attr('name') == name);
                        if(editorInstance)
                        {
                            if(data === null) data = '';

                            /* Set editor content and sync with textarea. */
                            editorInstance.html(data);
                            editorInstance.sync();
                            break;
                        }
                    }
                default:
                    /* For normal inputs, just set the value is enough. */
                    inputEl.val(data);
                    break;
            }
        }
        window.inject = (obj) =>
        {
            for(const key in obj)
            {
                window.dealWithSpecialInputType(key, obj[key]);
                if(Array.isArray(obj[key]))
                {
                    const isStartWith0 = $('[name="' + key + '[0]' + '"]').length;
                    const arr = obj[key];
                    for(let i = 0; i < arr.length; i++)
                    {
                        for(const key in arr[i])
                        {
                            window.injectToInputElement(key, arr[i][key], isStartWith0 ? i : i + 1);
                        }
                    }
                }
                else if(typeof obj[key] === 'Object')
                {
                    inject(obj[key]);
                }
                else
                {
                    window.injectToInputElement(key, obj[key])
                }
            }
        }

        window.dealWithSpecialInputType = (key, value) =>
        {
            if(key === 'steps')
            {
                const steps = document.getElementById('steps');
                if(!steps) return;
                if(value.length > steps.children.length)
                {
                    const gap = value.length - steps.children.length;
                    for(let i = 0; i < gap; i++)
                    {
                        const addButton = document.querySelector('#steps > tr:last-child > .step-actions button:first-child');
                        if(!addButton) return;
                        addButton.click();
                    }
                }
            }
        }

        requestAnimationFrame(() =>
        {
            try
            {
                const data = JSON.parse(window.injectData);
                if(!data) return;

                window.inject(data);

                zui.Messager.show({content: '{$this->lang->ai->dataInject->success}', type: 'success'});
            }
            catch(e)
            {
                zui.Messager.show({content:'{$this->lang->ai->dataInject->fail}', type: 'danger'});
                console.error(e);
            }
            finally
            {
                /* Set injected in oreder to cancel loading class on object view (see ./promptmenu.html.php). */
                sessionStorage.setItem('ai-prompt-data-injected', true);

                const container = window.frameElement?.closest('.load-indicator');
                if(container && container.dataset.loading)
                {
                    sessionStorage.removeItem('ai-prompt-data-injected');
                    delete container.dataset.loading;

                    container.classList.remove('loading');
                    container.classList.remove('no-delay');
                }
            }
        });
    })();
    JAVASCRIPT);

    $auditInject();
};
$inputInject();
