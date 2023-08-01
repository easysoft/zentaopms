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

        document.addEventListener('DOMContentLoaded', function()
        {
          /* Set injected in oreder to cancel loading class on object view (see promptmenu.html.php). */
          sessionStorage.setItem('ai-prompt-data-injected', true);
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
        });
      })();
    </script>
  <?php endif;?>
    <script>
      $(function() {
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

        const regenerateButton = document.getElementById('promptRegenerate');
        if(regenerateButton)
        {
          regenerateButton.addEventListener('click', function()
          {
            $('body').attr('data-loading', '<?php echo $lang->ai->execute->loading;?>');
            $('body').addClass('load-indicator loading');
          });
        }
      });
    </script>
<?php endif;?>
