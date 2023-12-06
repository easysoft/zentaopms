<?php
/**
 * The admin view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
include '../../common/view/chosen.html.php';
?>
<?php if(isset($pageCSS)) css::internal($pageCSS); ?>
<form class="form-horizontal load-indicator" id="blockAdminForm" method='post' target='hiddenwin'>
  <div class="form-group">
    <label for="modules" class="col-sm-3"><?php echo $lang->block->lblModule;?></label>
    <div class="col-sm-7">
      <?php echo html::select('module', $modules, '', "class='form-control chosen'")?>
    </div>
  </div>
  <?php if($blocks):?>
  <div id="blocksList">
    <div class="form-group">
      <label for="moduleBlock" class="col-sm-3"><?php echo $this->lang->block->lblBlock;?></label>
      <div class="col-sm-7">
        <?php html::select('block', $blocks, '', "class='form-control chosen'");?>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div id="blockParams">
    <?php include 'publicform.html.php';?>
  </div>
  <div class="form-group">
    <div class="col-sm-7 col-sm-offset-3">
      <button type="submit" class="btn btn-wide btn-primary"><?php echo $lang->save;?></button>
      <button type="button" class="btn btn-wide" data-dismiss="modal"><?php echo $lang->cancel;?></button>
    </div>
  </div>
</form>
<?php if(isset($pageJS)) js::execute($pageJS);?>
