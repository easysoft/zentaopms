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

$inputInject = function()
{
    $this->app->loadConfig('ai');
    $this->app->loadLang('ai');
    $module = $this->app->getModuleName();
    $method = $this->app->getMethodName();
    if(!isset($this->config->ai->availableForms[$module]) || !in_array($method, $this->config->ai->availableForms[$module])) return;

    if(isset($_SESSION['aiInjectData']) && isset($_SESSION['aiInjectData'][$module]) && isset($_SESSION['aiInjectData'][$module][$method])) $injectData = $_SESSION['aiInjectData'][$module][$method];
    if(empty($injectData)) return;

    jsVar('window.injectData', $injectData);
    unset($_SESSION['aiInjectData'][$module]);

    $this->app->loadLang('ai');

    js(<<< JAVASCRIPT
        window.injectToInputElement = (inputName, data, index) =>
        {
            if(typeof index !== 'undefined') inputName = inputName + '[' + index + ']';

            const inputEl = $('[name="' + inputName + '"]');
            if(!inputEl.length) return;

            const inputType = inputEl.prop('nodeName');
            switch(inputType) // Contains case fallthroughs, on purpose.
            {
                case 'TEXTAREA':
                    /* Textareas might be controlled by KindEditors. */
                    if(typeof KindEditor !== 'undefined' && KindEditor.instances.length)
                    {
                        const editorInstance = KindEditor.instances.find(e => e.srcElement.attr('name') == inputName);
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
    JAVASCRIPT);

    /* TODO: handle prompt audit, see original ../view/inputinject.html.php. */
};
$inputInject();
