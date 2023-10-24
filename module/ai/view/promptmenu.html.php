<?php
/**
 * The ai prompt menu view file of ai module of ZenTaoPMS.
 *
 * This view file is used to print the prompt menu, acts just like header php files.
 * Prompt menus are generated with php and injected with javascript. A lot of hacking
 * went into this, so please don't touch it unless you know what you are doing.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php
$this->loadModel('ai');
if($this->ai->isModelConfigured() && commonModel::hasPriv('ai', 'promptExecute')):?>
<?php
  $this->app->loadConfig('ai');
  $module = $this->app->getModuleName();
  $method = $this->app->getMethodName();
  if(isset($config->ai->menuPrint->locations[$module][$method])):
?>
  <?php
    $menuOptions = $config->ai->menuPrint->locations[$module][$method];
    $prompts     = $this->ai->getPromptsForUser($menuOptions->module);
    $prompts     = $this->ai->filterPromptsForExecution($prompts, true);
    if(!empty($prompts)):
  ?>
    <?php
      $html = '';
      $objectVarName = empty($menuOptions->objectVarName) ? $menuOptions->module : $menuOptions->objectVarName;
      $currentObjectId = !empty($this->view->$objectVarName) ? $this->view->$objectVarName->id : 0;
      if(count($prompts) > 1)
      {
        $html .= '<div class="prompts dropdown' . ((isset($menuOptions->class) ? ' ' . $menuOptions->class : '') . (isset($menuOptions->dropdownClass) ? ' ' . $menuOptions->dropdownClass : '')) . '"><button class="btn btn-link' . (isset($menuOptions->buttonClass) ? ' ' . $menuOptions->buttonClass : '') . '" type="button" data-toggle="dropdown">' . $lang->ai->promptMenu->dropdownTitle . ' <i class="icon-caret-down"></i></button><ul class="dropdown-menu pull-right">';
        foreach($prompts as $prompt) $html .= '<li>' . html::linkButton($prompt->name . ($prompt->status != 'active' ? '<span class="label label-info label-badge" style="margin-left: 4px;">' . $lang->ai->prompts->statuses[$prompt->status] . '</span>' : ''), helper::createLink('ai', 'promptExecute', "promptId=$prompt->id&objectId=$currentObjectId"), 'self', "style='width: 100%;'" . (empty($prompt->unauthorized) ? '' : ' disabled') . (empty($prompt->desc) ? '' : " data-toggle='popover' data-container='body' data-trigger='hover' data-content='$prompt->desc' data-title='$prompt->name' data-placement='left'"), 'btn btn-link text-left') . '</li>';
        $html .= '</ul></div>';
      }
      else
      {
        $prompt = current($prompts);
        $html .= html::linkButton($prompt->name . ($prompt->status != 'active' ? '<span class="label label-info label-badge" style="margin-left: 4px;">' . $lang->ai->prompts->statuses[$prompt->status] . '</span>' : ''), helper::createLink('ai', 'promptExecute', "promptId=$prompt->id&objectId=$currentObjectId"), 'self', (empty($prompt->unauthorized) ? '' : 'disabled') . (empty($prompt->desc) ? '' : " data-toggle='popover' data-container='body' data-trigger='hover' data-content='$prompt->desc' data-title='$prompt->name' data-placement='bottom'"), 'prompt btn btn-link' . ((isset($menuOptions->class) ? ' ' . $menuOptions->class : '') . (isset($menuOptions->buttonClass) ? ' ' . $menuOptions->buttonClass : '')));
      }
    ?>

    <?php if(isset($menuOptions->stylesheet)):?>
      <style><?php echo $menuOptions->stylesheet;?></style>
    <?php endif;?>

    <script>
      $(function()
      {
        if(window.location.search.includes('onlybody')) return;
        const container = window.frameElement?.closest('.load-indicator');
        if(container && container.dataset.loading)
        {
          delete container.dataset.loading;
          container.classList.remove('loading');
          container.classList.remove('no-delay');
        }

        $(`<?php echo $menuOptions->targetContainer;?>`).<?php echo isset($menuOptions->injectMethod) ? $menuOptions->injectMethod : 'append';?>(`<?php echo $html;?>`);
        $('[data-toggle="popover"]').popover({template: '<div class="popover"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'});

        $('<?php echo count($prompts) > 1 ? '.prompts.dropdown ul.dropdown-menu' : '.prompt';?>').on('click', <?php if(count($prompts) > 1) echo "'button',";?> function(e)
        {
          if(!container) return;
          container.dataset.loading = e.target.querySelector('.label') ? '<?php echo $lang->ai->execute->auditing;?>' : '<?php echo $lang->ai->execute->loading;?>';
          container.classList.add('loading');
          container.classList.add('no-delay');

          /* Checks for session storage to cancel loading status (see inputinject.html.php). */
          sessionStorage.removeItem('ai-prompt-data-injected');
          const loadCheckInterval = setInterval(function()
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
      });
    </script>

<?php endif; endif; endif;?>
