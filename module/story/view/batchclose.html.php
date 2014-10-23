<?php
/**
 * The batch close view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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

<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchClose', "from=storyBatchClose")?>">
  <table class='table table-fixed table-form'>
  <thead>
    <tr>
      <th class='w-50px'> <?php echo $lang->idAB;?></th> 
      <th>                <?php echo $lang->story->title;?></th>
      <th class='w-80px'> <?php echo $lang->story->status;?></th>
      <th class='w-120px'><?php echo $lang->story->closedReason;?></th>
      <th class='w-p40 '> <?php echo $lang->story->comment;?></th>
    </tr>
  </thead>
    <?php foreach($storyIDList as $storyID):?>
    <tr class='text-center'>
      <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
      <td class='text-left'><?php echo $stories[$storyID]->title;?></td>
      <td class='story-<?php echo $stories[$storyID]->status;?>'><?php echo $lang->story->statusList[$stories[$storyID]->status];?></td>

      <?php if($stories[$storyID]->status != 'closed'):?>
      <td>
        <?php if($stories[$storyID]->status == 'draft') unset($this->lang->story->reasonList['cancel']);?>
        <table class='w-p100'>
          <tr>
            <td class='pd-0'>
              <?php echo html::select("closedReasons[$storyID]", $lang->story->reasonList, 'done', "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
            </td>
            <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'duplicate') echo "style='display:none'";?>>
            <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class=form-control placeholder='{$lang->idAB}'");?>
            </td>
            <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'subdivided') echo "style='display:none'";?>>
            <?php echo html::input("childStoriesIDList[$storyID]", '', "class=form-control placeholder='{$lang->idAB}'");?>
            </td>
          </tr>
        </table>
      </td>
      <td><?php echo html::input("comments[$storyID]", '', "class='form-control'");?></td>
      <?php else:?>  
      <td>
        <div class='text-left'>
          <?php echo html::select("closedReasons[$storyID]", $lang->story->reasonList, $stories[$storyID]->closedReason, 'disabled="disabled" class="form-control"');?>
        </div>
      </td>
      <td><?php echo html::input("comments[$storyID]", '', "class='form-control' disabled='disabled'");?></td>
      <?php endif;?>

    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='5'><div class='text-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='5' class='text-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
