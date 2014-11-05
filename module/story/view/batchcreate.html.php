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
    <div class='actions'>
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&moduleID=$moduleID")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn' data-width='600px'")?>
      <?php echo html::commonButton($lang->pasteText, "data-toggle='myModal'")?>
    </div>
  </div>
</div>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table table-form table-fixed'> 
    <thead>
      <tr class='text-center'>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-p15'><?php echo $lang->story->module;?></th>
        <th class='w-p15'><?php echo $lang->story->plan;?></th>
        <th><?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-p20'><?php echo $lang->story->spec;?></th>
        <th class='w-80px'><?php echo $lang->story->pri;?></th>
        <th class='w-80px'><?php echo $lang->story->estimate;?></th>
        <th class='w-70px'><?php echo $lang->story->review;?></th>
      </tr>
    </thead>
    <?php $i = 0; ?>
    <?php if(!empty($titles)):?>
    <?php foreach($titles as $fileName => $storyTitle):?>
    <?php $moduleID = $i == 0 ? $moduleID : 'same';?>
    <?php $planID   = $i == 0 ? '' : 'same';?>
    <?php $pri      = $i == 0 ? '' : 'same';?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("plan[$i]", $plans, $planID, "class='form-control chosen'");?></td>
      <td><?php echo html::input("title[$i]", $storyTitle, "class='form-control'");?></td>
      <td><?php echo html::textarea("spec[$i]", $spec, "rows='1' class='form-control autosize'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("pri[$i]", $priList, $pri, "class='form-control'");?></td>
      <td><?php echo html::input("estimate[$i]", $estimate, "class='form-control'");?></td>
      <td>
        <?php
        echo html::select("needReview[$i]", $lang->story->reviewList, 0, "class='form-control'");
        echo html::hidden("uploadImage[$i]", $fileName);
        ?>
      </td>
    </tr>
    <?php $i++;?>
    <?php endforeach;?>
    <?php endif;?>
    <?php $nextStart = $i;?>
    <?php for($i = $nextStart; $i < $config->story->batchCreate; $i++):?>
    <?php $moduleID = $i - $nextStart == 0 ? $moduleID : 'same';?>
    <?php $planID   = $i - $nextStart == 0 ? '' : 'same';?>
    <?php $pri      = $i - $nextStart == 0 ? '' : 'same';?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("plan[$i]", $plans, $planID, "class='form-control chosen'");?></td>
      <td><?php echo html::input("title[$i]", $storyTitle, "class='form-control'");?></td>
      <td><?php echo html::textarea("spec[$i]", $spec, "rows='1' class='form-control autosize'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("pri[$i]", $priList, $pri, "class='form-control'");?></td>
      <td><?php echo html::input("estimate[$i]", $estimate, "class='form-control'");?></td>
      <td><?php echo html::select("needReview[$i]", $lang->story->reviewList, 0, "class='form-control'");?></td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<table class='hide' id='trTemp'>
  <tbody>
    <tr class='text-center'>
      <td>%s</td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $moduleID, "class='form-control'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("plan[%s]", $plans, $planID, "class='form-control'");?></td>
      <td><?php echo html::input("title[%s]", $storyTitle, "class='form-control'");?></td>
      <td>
        <?php echo html::textarea("spec[%s]", $spec, "rows='1' class='form-control autosize'");?>
      </td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("pri[%s]", $priList, $pri, "class='form-control'");?></td>
      <td><?php echo html::input("estimate[%s]", $estimate, "class='form-control'");?></td>
      <td><?php echo html::select("needReview[%s]", $lang->story->reviewList, 0, "class='form-control'");?></td>
    </tr>
  </tbody>
</table>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
