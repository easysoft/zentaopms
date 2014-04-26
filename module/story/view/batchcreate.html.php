<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->story->batchCreate;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table table-fixed table-form'> 
    <thead>
      <tr class='text-center'>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-p15'><?php echo $lang->story->module;?></th>
        <th class='w-p15'><?php echo $lang->story->plan;?></th>
        <th><?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-p20'><?php echo $lang->story->spec;?></th>
        <th class='w-60px'><?php echo $lang->story->pri;?></th>
        <th class='w-60px'><?php echo $lang->story->estimate;?></th>
        <th class='w-70px'><?php echo $lang->story->review;?></th>
      </tr>
    </thead>
    <?php for($i = 0; $i < $config->story->batchCreate; $i++):?>
    <?php $moduleID = $i == 0 ? $moduleID : 'same';?>
    <?php $planID   = $i == 0 ? '' : 'same';?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("module[$i]", $moduleOptionMenu, $moduleID, 'class=form-control');?></td>
      <td><?php echo html::select("plan[$i]", $plans, $planID, 'class=form-control');?></td>
      <td><?php echo html::input("title[$i]", $storyTitle, "class='form-control'");?></td>
      <td>
        <?php echo html::textarea("spec[$i]", $spec, "rows='1' class='form-control'");?>
      </td>
      <td><?php echo html::select("pri[$i]", (array)$lang->story->priList, $pri, 'class=form-control');?></td>
      <td><?php echo html::input("estimate[$i]", $estimate, "class='form-control'");?></td>
      <td><?php echo html::select("needReview[$i]", $lang->story->reviewList, 0, "class='form-control'");?></td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
