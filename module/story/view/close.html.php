<?php
/**
 * The close view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: close.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->story->close;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->story->closedReason;?></th>
          <td class='w-p25-f'><?php echo html::select('closedReason', $reasonList, '', 'class="form-control" onchange="setStory(this.value)"');?></td>
          <td class='closeSyncBox'>
            <div class="checkbox-primary c-future <?php echo !empty($story->twins) ? '' : 'hidden';?>" style="display: inline-block">
              <input type='checkbox' id='closeSync' name='closeSync' value='1' />
              <label for='closeSync'>
                <?php echo $lang->story->relievedTwinsRelation;?>
              </label>
            </div>
            <div class="<?php echo !empty($story->twins) ? '' : 'hidden';?>" style="display: inline-block"><icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->story->relievedTwinsRelationTips;?>"></icon></div>
          </td>
        </tr>
        <tr id='duplicateStoryBox' style='display:none'>
          <th><?php echo $lang->story->duplicateStory;?></th>
          <td class='required'><?php echo html::select('duplicateStory', array('' => '') + $productStories, '', "class='form-control' placeholder='{$lang->searchAB}'"); ?></td><td></td>
        </tr>
        <tr id='childStoriesBox' style='display:none'>
          <th><?php echo $lang->story->childStories;?></th>
          <td><?php echo html::input('childStories', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', 'closed');?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php if(!isonlybody()) echo html::a($app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"), $lang->goback, '', 'class="btn btn-wide"');?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
