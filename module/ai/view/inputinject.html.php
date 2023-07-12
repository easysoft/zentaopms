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
  $module = $this->app->getModuleName();
  $method = $this->app->getMethodName();
  if(isset($config->ai->targetForm[$module]) && in_array($method, $config->ai->targetForm[$module])):
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
      $(function()
      {
        try
        {
          var data = JSON.parse(injectData);
          if(!data) return;

          for(var inputName in data)
          {
            var $input = $('[name="' + inputName + '"]');
            if(!$input.length) continue;

            var inputType = $input.prop('nodeName');
            switch(inputType) // Contains case fallthroughs, on purpose.
            {
              case 'TEXTAREA':
                /* Textareas might be controlled by KindEditors. */
                if(KindEditor.instances.length)
                {
                  var editorInstance = KindEditor.instances.find(function(e)
                  {
                    return e.srcElement.attr('name') == 'spec';
                  });
                  if(editorInstance)
                  {
                    editorInstance.html(data[inputName]);
                    break;
                  }
                }
              default:
                /* For normal inputs, just set the value is enough. */
                $input.val(data[inputName]);
                break;
            }
          }

          $.zui.messager.success('<?php echo $lang->ai->dataInject->success;?>');
        }
        catch(e)
        {
          $.zui.messager.danger('<?php echo $lang->ai->dataInject->fail;?>');
          console.error(e);
        }
      });
    </script>
  <?php endif;?>
<?php endif;?>
