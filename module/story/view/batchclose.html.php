<?php
/**
 * The batch close view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('storyType', $storyType);?>
<?php js::set('app', $app->tab);?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2><?php echo ($storyType == 'story' ? $lang->SRCommon : $lang->URCommon) . $lang->colon . $lang->story->batchClose;?></h2>
  </div>
  <?php if(isset($suhosinInfo)):?>
  <div class='alert alert-info'><?php echo $suhosinInfo;?></div>
  <?php else:?>
  <form method='post' target='hiddenwin' action="<?php echo inLink('batchClose', "from=storyBatchClose")?>">
    <table class='table table-fixed table-form with-border'>
    <thead>
      <tr class='text-center'>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='text-left'><?php echo $lang->story->title;?></th>
        <th class='c-status'><?php echo $lang->story->status;?></th>
        <th class='c-reason'><?php echo $lang->story->closedReason;?></th>
        <th class='w-p30'><?php echo $lang->story->comment;?></th>
      </tr>
    </thead>
      <?php foreach($stories as $storyID => $story):?>
      <tr class='text-center'>
        <td><?php echo $storyID . html::hidden("storyIdList[$storyID]", $storyID);?></td>
        <td title='<?php echo $story->title;?>' class='text-left'>
          <?php echo $story->title;?>
          <?php if(!empty($story->twins)):?>
          <span class='label label-outline label-badge'><?php echo "{$lang->story->twins}:"?> <span class='text-blue'><?php echo $twinsCount[$story->id]?></span></span>
          <?php endif;?>
        </td>
        <td class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></td>
        <td class='reasons-td'>
          <table class='w-p100 table-form'>
            <tr>
              <td class='pd-0'>
                <?php
                $closedReasonList = $story->status == 'draft' ? array_diff_key($reasonList, array('cancel' => '')) : $reasonList;
                echo html::select("closedReasons[$storyID]", $closedReasonList, 'done', "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 80px'");
                ?>
              </td>
              <td class='pd-0 w-p60 text-left' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($story->closedReason != 'duplicate') echo "style='display:none'";?>>
                <?php echo html::select("duplicateStoryIDList[$storyID]", '', '', "class='form-control' placeholder='{$lang->bug->duplicateTip}'");?>
              </td>
              <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($story->closedReason != 'subdivided') echo "style='display:none'";?>>
              <?php echo html::input("childStoriesIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}'");?>
              </td>
            </tr>
          </table>
        </td>
        <td><?php echo html::input("comments[$storyID]", '', "class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td colspan='5' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo $this->session->storyList ? html::a($this->session->storyList, $lang->goback, '', "class='btn btn-back btn-wide'") : html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
