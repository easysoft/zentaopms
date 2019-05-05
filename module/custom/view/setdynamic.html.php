<?php
/**
 * The score view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<style>
.checkbox-primary{float:left;margin-left:5px;}
</style>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->setDynamic?></strong>
      </div>
    </div>
    <table class='table table-hover table-striped table-bordered'>
      <thead>
        <tr class='text-center'>
          <th class='w-150px'><?php echo $lang->group->module;?></th>
          <th><?php echo $lang->action->action;?></th>
        </tr>
      </thead>
      <?php foreach($objectTypes as $objectKey => $objectTypeName):?>
      <tr>
        <th class='text-middle  w-150px'>
          <div class="text-center check-all">
            <?php echo $objectTypeName?>
          </div>
        </th>
        <td class='pv-10px'>
        <?php if(isset($dynamicAction->$objectKey)):?>
        <div class='group-item'>
            <?php echo html::checkbox("actions[{$objectKey}]", $dynamicAction->$objectKey, isset($setActions[$objectKey]) ? $setActions[$objectKey] : '', '');?>
        </div>
        <?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
        <th></th>
        <td class='form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#mainMenu #setDynamicTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
