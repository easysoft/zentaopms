<?php
/**
 * The batch close view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
    <strong><?php echo $lang->story->common . $lang->colon . $lang->story->batchClose;?></strong>
    <small class='text-danger'><?php echo html::icon($lang->icons['batchClose']);?></small>
  </div>
</div>
<?php if(isset($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo;?></div>
<?php else:?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchClose', "from=storyBatchClose")?>">
  <table class='table table-fixed table-form with-border'>
  <thead>
    <tr>
      <th class='w-50px'> <?php echo $lang->idAB;?></th> 
      <th>                <?php echo $lang->story->title;?></th>
      <th class='w-80px'> <?php echo $lang->story->status;?></th>
      <th class='w-120px'><?php echo $lang->story->closedReason;?></th>
      <th class='w-p40 '> <?php echo $lang->story->comment;?></th>
    </tr>
  </thead>
    <?php foreach($stories as $storyID => $story):?>
    <tr class='text-center'>
      <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
      <td class='text-left'><?php echo $story->title;?></td>
      <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
      <td>
        <?php if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);?>
        <table class='w-p100'>
          <tr>
            <td class='pd-0'>
              <?php echo html::select("closedReasons[$storyID]", $lang->story->reasonList, 'done', "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
            </td>
            <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($story->closedReason != 'duplicate') echo "style='display:none'";?>>
            <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
            </td>
            <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($story->closedReason != 'subdivided') echo "style='display:none'";?>>
            <?php echo html::input("childStoriesIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
            </td>
          </tr>
        </table>
      </td>
      <td><?php echo html::input("comments[$storyID]", '', "class='form-control' autocomplete='off'");?></td>
    </tr>  
    <?php endforeach;?>
    <tr><td colspan='5' class='text-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
