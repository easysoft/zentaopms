<?php
/**
 * The admin view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
include '../../common/view/chosen.html.php';
?>
<?php if(isset($pageCSS)) css::internal($pageCSS); ?>
<form class="form-horizontal load-indicator" id="blockAdminForm" method='post' target='hiddenwin'>
  <?php if(!empty($modules)):?>
  <div class="form-group">
    <label for="modules" class="col-sm-3"><?php echo $lang->block->lblModule;?></label>
    <div class="col-sm-7">
      <?php
      $moduleID = '';
      if($block) $moduleID = $block->source != '' ? $block->source : $block->block;
      ?>
      <?php echo html::select('modules', $modules, $moduleID, "class='form-control chosen'")?>
    </div>
  </div>
  <?php else:?>
  <?php echo html::hidden('modules', $module);?>
  <?php endif;?>
  <div id="blocksList"><?php if(!empty($blocks)) echo $blocks;?></div>
  <div id="blockParams"></div>
  <div class="form-group">
    <div class="col-sm-7 col-sm-offset-3">
      <button type="submit" class="btn btn-wide btn-primary"><?php echo $lang->save;?></button>
      <button type="button" class="btn btn-wide" data-dismiss="modal"><?php echo $lang->cancel;?></button>
    </div>
  </div>
</form>
<?php js::set('blockID', $blockID);?>
<?php js::set('of', $lang->block->of);?>
<?php js::set('title', isset($block->title) ? $block->title : '');?>
<?php if(!empty($module)) js::set('module', $module);?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
