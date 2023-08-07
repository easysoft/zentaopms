<?php
/**
 * The ai input inject view file of ai module of ZenTaoPMS.
 *
 * This view file is used to inject ai generated result into
 * input fields. Include this view file in the target forms.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php
  $this->app->loadConfig('ai');
  $this->app->loadLang('ai');
  $module = $this->app->getModuleName();
  $method = $this->app->getMethodName();
  if(isset($config->ai->availableForms[$module]) && in_array($method, $config->ai->availableForms[$module])):
?>
  <?php
    if(isset($_SESSION['aiInjectData']) && isset($_SESSION['aiInjectData'][$module]) && isset($_SESSION['aiInjectData'][$module][$method])) $injectData = $_SESSION['aiInjectData'][$module][$method];
    if(!empty($injectData)):
  ?>
    <?php
      js::set('injectData', $injectData);
      unset($_SESSION['aiInjectData'][$module][$method]);
      $this->app->loadLang('ai');
    ?>
    <script>
      (function()
      {
        function injectToInputElement(inputName, data, index)
        {
          if(typeof index !== 'undefined')
          {
            inputName = inputName + '[' + (index + 1) + ']';
          }

          const $input = $('[name="' + inputName + '"]');
          if(!$input.length) return;

          const inputType = $input.prop('nodeName');
          switch(inputType) // Contains case fallthroughs, on purpose.
          {
            case 'TEXTAREA':
              /* Textareas might be controlled by KindEditors. */
              if(typeof KindEditor !== 'undefined' && KindEditor.instances.length)
              {
                const editorInstance = KindEditor.instances.find(function(e)
                {
                  return e.srcElement.attr('name') == inputName;
                });
                if(editorInstance)
                {
                  if(data === null) data = '';

                  /* Remove placeholder. */
                  if(data !== '') editorInstance.$placeholder.each(function(_idx, ph) {ph.style.display = 'none';});

                  /* Set editor content and sync with textarea. */
                  editorInstance.html(data);
                  editorInstance.sync();
                  break;
                }
              }
            default:
              /* For normal inputs, just set the value is enough. */
              $input.val(data);
              break;
          }
        }
        function inject(obj)
        {
          for(const key in obj)
          {
            if(Array.isArray(obj[key]))
            {
              const arr = obj[key];
              for(let i = 0; i < arr.length; i++)
              {
                for(const key in arr[i])
                {
                  injectToInputElement(key, arr[i][key], i);
                }
              }
            }
            else if(typeof obj[key] === 'Object')
            {
              inject(obj[key]);
            }
            else
            {
              injectToInputElement(key, obj[key])
            }
          }
        }

        document.addEventListener("DOMContentLoaded", function()
        {
          try
          {
            const data = JSON.parse(injectData);
            if(!data) return;

            inject(data);

            $.zui.messager.success('<?php echo $lang->ai->dataInject->success;?>');
          }
          catch(e)
          {
            $.zui.messager.danger('<?php echo $lang->ai->dataInject->fail;?>');
            console.error(e);
          }
          finally
          {
            /* Set injected in oreder to cancel loading class on object view (see promptmenu.html.php). */
            sessionStorage.setItem('ai-prompt-data-injected', true);
          }
        });
      })();
    </script>
  <?php endif;?>
  <?php
    $prompt   = $_SESSION['aiPrompt']['prompt'];
    $objectId = $_SESSION['aiPrompt']['objectId'];
    $isAudit  = isset($_SESSION['auditPrompt']) && time() - $_SESSION['auditPrompt']['time'] < 10 * 60;
    ?>
    <script>
      (function() {
        const isAudit = <?php echo $isAudit ? 'true' : 'false';?>;
        const promptId = '<?php echo $prompt->id;?>';

        <?php if(isset($config->ai->injectAuditButton->locations[$module][$method])) :?>
        function injectAuditAction()
        {
          <?php
          $htmlStr = html::commonButton($lang->ai->prompts->action->publish, "id='promptPublish' data-promptId=$prompt->id", 'btn btn-primary btn-wide');
          $htmlStr = $htmlStr . html::commonButton($lang->ai->audit->exit, "id='promptAuditExit'", 'btn btn-wide');
          $targetContainer = $config->ai->injectAuditButton->locations[$module][$method]['action']->targetContainer;
          $injectMethod    = $config->ai->injectAuditButton->locations[$module][$method]['action']->injectMethod;
          ?>
          const htmlStr = `<?php echo $htmlStr;?>`;
          $('<?php echo $targetContainer;?>')['<?php echo $injectMethod;?>'](htmlStr);
        }

        function injectAuditToolbar()
        {
          <?php
          $htmlStr = html::a(helper::createLink('ai', 'promptexecute', "promptId=$prompt->id&objectId=$objectId"), '<i class="icon icon-refresh muted"></i> ' . $lang->ai->audit->regenerate, '', 'id="promptRegenerate" class="btn btn-link"');
          if($isAudit)
          {
            $htmlStr = $htmlStr . html::a(helper::createLink('ai', 'promptaudit', "promptId=$prompt->id&objectId=$objectId"), $lang->ai->audit->designPrompt, '', 'id="promptAudit" class="btn btn-info iframe"');
          }
          $targetContainer = $config->ai->injectAuditButton->locations[$module][$method]['toolbar']->targetContainer;
          $injectMethod    = $config->ai->injectAuditButton->locations[$module][$method]['toolbar']->injectMethod;
          if(!empty($config->ai->injectAuditButton->locations[$module][$method]['toolbar']->class)) $htmlStr = '<div class="' . $config->ai->injectAuditButton->locations[$module][$method]['toolbar']->class . '">' . $htmlStr . '</div>';
          ?>
          const targetContainer = `<?php echo $targetContainer;?>`;
          const injectMethod    = `<?php echo $injectMethod;?>`;
          const htmlStr = `<?php echo $htmlStr;?>`;
          $(targetContainer)[injectMethod](htmlStr);
        }

        if(isAudit)
        {
          injectAuditAction();
        }
        if(typeof injectData !== 'undefined')
        {
          injectAuditToolbar();
        }
        <?php endif;?>

        const publishButton = document.getElementById('promptPublish');
        if(publishButton)
        {
          publishButton.addEventListener('click', function(e)
          {
            e.preventDefault();

            const container = publishButton.ownerDocument.defaultView.parent.document.querySelector('.load-indicator');
            if(container) container.classList.toggle('loading', true);

            const promptId = publishButton.dataset.promptid;
            const aTag = document.createElement('a');
            aTag.href = createLink('ai', 'promptPublish', 'promptId=' + promptId + '&backToTestingLocation=true');
            aTag.style.display = 'none';
            document.body.appendChild(aTag);
            aTag.click();
          });
        }

        const auditExitButton = document.getElementById('promptAuditExit');
        if(auditExitButton)
        {
          auditExitButton.addEventListener('click', function(e)
          {
            e.preventDefault();

            const container = auditExitButton.ownerDocument.defaultView.parent.document.querySelector('.load-indicator');
            if(container) container.classList.toggle('loading', true);

            const aTag = document.createElement('a');
            aTag.href = createLink('ai', 'promptAudit', 'promptId=' + promptId + '&objectId=0' + '&exit=true');
            aTag.style.display = 'none';
            document.body.appendChild(aTag);
            aTag.click();
          });
        }

        const regenerateButton = document.getElementById('promptRegenerate');
        if(regenerateButton)
        {
          regenerateButton.addEventListener('click', function()
          {
            $('body').attr('data-loading', '<?php echo $lang->ai->execute->loading;?>');
            $('body').addClass('load-indicator loading');
          });
        }
      })();
    </script>
<?php endif;?>
