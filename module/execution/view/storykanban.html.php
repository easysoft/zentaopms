<?php
/**
 * The kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     execution
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->execution->confirmUnlinkStory)?>
<?php js::set('canBeChanged', $canBeChanged)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $total = 0;?>
    <?php foreach($stories as $colStories) $total += count($colStories);?>
    <?php if(common::hasPriv('execution', 'story')) echo html::a($this->createLink('execution', 'story', "executionID=$execution->id"), "<span class='text'>{$lang->story->allStories}</span>", '', "class='btn btn-link'");?>
    <?php if(common::hasPriv('execution', 'storykanban')) echo html::a($this->createLink('execution', 'storykanban', "executionID=$execution->id"), "<span class='text'>{$lang->execution->kanban}</span> <span class='label label-light label-badge'>{$total}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
    <?php
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export', '', "data-group='execution'");

    if($canBeChanged)
    {
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("execution=$execution->id");
            echo html::a($this->createLink('tutorial', 'wizard', "module=execution&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->execution->linkStory}",'', "class='btn btn-link link-story-btn'");
        }
        else
        {
            common::printIcon('execution', 'linkStory', "execution=$execution->id", '', 'button', 'link', '', 'btn-link link-story-btn');
        }
    }
    ?>
    </div>
    <?php if($canBeChanged and $productID and !$this->loadModel('story')->checkForceReview()) common::printLink('story', 'create', "productID=$productID&branch=&moduleID=0&story=0&execution=$execution->id", "<i class='icon icon-plus'></i> " . $lang->execution->createStory, '', "class='btn btn-primary' class='btn btn-link export' data-group='execution'");?>
  </div>
</div>
<?php
$cols    = array('projected', 'developing', 'developed', 'testing', 'tested', 'verified', 'released');
$account = $this->app->user->account;
?>
<div id="kanban" class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php if(empty($stories)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->story->noStory;?></span>
      <?php if($canBeChanged and common::hasPriv('execution', 'linkStory')):?>
      <?php echo html::a($this->createLink('execution', 'linkStory', "execution=$execution->id"), "<i class='icon icon-link'></i> " . $lang->execution->linkStory, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-grouped text-center">
    <thead>
      <tr>
        <?php foreach ($cols as $col):?>
        <th class='c-board s-<?php echo $col?>'><?php echo $lang->story->stageList[$col];?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="c-boards no-padding text-left" colspan='<?php echo count($cols);?>'>
          <div class="boards-wrapper">
            <div class="boards">
              <?php foreach($cols as $col):?>
              <div class="board" data-type="<?php echo $col;?>">
                <?php if(!empty($stories[$col])):?>
                <?php foreach($stories[$col] as $story):?>
                <div class='board-item' data-id='<?php echo $story->id?>' id='story-<?php echo $story->id?>' data-type='story'>
                  <?php echo html::a($this->createLink('story', 'view', "story=$story->id", '', true), "#{$story->id} {$story->title}", '', 'class="title kanbaniframe" title="' . $story->title . '"');?>
                  <div class='info'>
                    <span class='label-pri label-pri-<?php echo $story->pri?>' title='<?php echo $lang->story->pri?>'><?php echo zget($lang->story->priList, $story->pri);?></span>
                    <span class='status status-story status-<?php echo $story->status;?>' title='<?php echo $lang->story->status?>'><span class="label label-dot"></span> <?php echo $lang->story->statusList[$story->status];?></span>
                    <?php if($canBeChanged and common::hasPriv('execution', 'unlinkStory')):?>
                    <div class='pull-right'><?php echo html::a($this->createLink('execution', 'unlinkStory', "executionID=$executionID&story=$story->id"), "<i class='icon icon-unlink icon-sm'></i>", 'hiddenwin', "title='{$lang->execution->unlinkStory}'");?></div>
                    <?php endif;?>
                    <div class='pull-right text-muted story-estimate' title='<?php echo $lang->story->estimate?>'><?php echo $story->estimate . "$config->hourUnit ";?></div>
                  </div>
                </div>
                <?php endforeach?>
                <?php endif?>
              </div>
              <?php endforeach;?>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php js::set('executionID', $executionID);?>
<?php include '../../common/view/footer.html.php';?>
