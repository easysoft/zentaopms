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
  $this->app->loadConfig('ai');
  $module = $this->app->getModuleName();
  $method = $this->app->getMethodName();
  if(isset($config->ai->menuPrint->locations[$module][$method])):
?>
  <?php
    $menuOptions = $config->ai->menuPrint->locations[$module][$method];
    $prompts     = $this->loadModel('ai')->getPromptsForUser($menuOptions->module);
    if(!empty($prompts)):
  ?>
    <?php
      $html = '';
      if(count($prompts) > 1)
      {
        $html .= '<div class="dropdown' . ((isset($menuOptions->class) ? ' ' . $menuOptions->class : '') . (isset($menuOptions->dropdownClass) ? ' ' . $menuOptions->dropdownClass : '')) . '"><button class="btn btn-link' . (isset($menuOptions->buttonClass) ? ' ' . $menuOptions->buttonClass : '') . '" type="button" data-toggle="dropdown">AI <i class="icon-caret-down"></i></button><ul class="dropdown-menu">';
        foreach($prompts as $prompt) $html .= '<li>' . html::commonButton($prompt->name, "title='{$prompt->desc}' style='width: 100%;'", 'btn btn-link') . '</li>';
        $html .= '</ul></div>';
      }
      else
      {
        $prompt = current($prompts);
        $html .= html::commonButton($prompt->name, "title='{$prompt->desc}'", 'btn btn-link' . ((isset($menuOptions->class) ? ' ' . $menuOptions->class : '') . (isset($menuOptions->buttonClass) ? ' ' . $menuOptions->buttonClass : '')));
      }
    ?>

    <?php if(isset($menuOptions->stylesheet)):?>
      <style><?php echo $menuOptions->stylesheet;?></style>
    <?php endif;?>

    <script>
      $(function() {$(`<?php echo $menuOptions->targetContainer;?>`).<?php echo isset($menuOptions->injectMethod) ? $menuOptions->injectMethod : 'append';?>(`<?php echo $html;?>`);});
    </script>

<?php endif; endif;?>
