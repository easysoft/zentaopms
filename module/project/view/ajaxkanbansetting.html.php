<?php
/**
 * The kanban setting view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
   <div class='heading'>
     <strong><?php echo $lang->project->kanbanSetting;?></strong>
   </div>
 </div>
<form target='hiddenwin' method='post'>
  <table class='table'>
    <?php if(common::hasPriv('project', 'kanbanHideCols')):?>
    <tr>
      <th class='text-right'><?php echo $lang->project->kanbanHideCols?></th>
      <td><?php echo html::radio('allCols', $lang->kanbanSetting->optionList, $allCols)?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th class='w-180px text-right'><?php echo $lang->project->kanbanShowOption?></th>
      <td><?php echo html::radio('showOption', $lang->kanbanSetting->optionList, $showOption)?></td>
    </tr>
    <?php if(common::hasPriv('project', 'kanbanColsColor')):?>
    <tr>
      <th class='text-right'><?php echo $lang->project->kanbanColsColor?></th>
      <td>
        <div class='row-table'>
          <?php foreach($colorList as $status => $color):?>
          <div class='col-table'>
            <input type='hidden' id='color<?php echo $status?>' name="colorList[<?php echo $status?>]" data-provide='colorpicker' data-wrapper='input-group-btn' value='<?php echo $color;?>' data-colors='#333,#2B529C,#E48600,#D2323D,#229F24,#777,#D2691E,#008B8B,#2E8B57,#4169E1,#4B0082,#FA8072,#BA55D3,#2E8B57,#6B8E23'>
            <?php echo $lang->task->statusList[$status];?>
          </div>
          <?php endforeach;?>
        </div>
      </td>
    </tr>
    <?php endif;?>
    <tr>
      <td colspan='2' class='text-center'>
        <?php
        echo html::submitButton();
        echo html::a(inlink('ajaxResetKanban', "projectID=$projectID"), $lang->project->resetKanban, 'hiddenwin', "class='btn'");
        ?>
      </td>
    </tr>
  </table>
</form>
<script>
$(function()
{
    $('.col-table .colorpicker:last .dropdown-menu').addClass('pull-right');
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
