<?php
/**
 * The kanban view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     project
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->project->confirmUnlinkStory)?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title" title='<?php echo $project->name?>'><?php echo $project->name;?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php $total = 0;?>
    <?php foreach($stories as $colStories) $total += count($colStories);?>
    <?php if(common::hasPriv('project', 'story')) echo html::a($this->createLink('project', 'story', "projectID=$project->id"), "<span class='text'>{$lang->story->allStories}</span>", '', "class='btn btn-link'");?>
    <?php if(common::hasPriv('project', 'storykanban')) echo html::a($this->createLink('project', 'storykanban', "projectID=$project->id"), "<span class='text'>{$lang->project->kanban}</span><span class='label label-light label-badge'>{$total}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
    <?php 
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export');

    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->project->linkStory}",'', "class='btn btn-link link-story-btn'");
    }
    else
    {
        common::printIcon('project', 'linkStory', "project=$project->id", '', 'button', 'link', '', 'btn-link link-story-btn');
    }
    ?>
    </div>
    <?php if($productID and !$this->loadModel('story')->checkForceReview()) common::printLink('story', 'create', "productID=$productID&branch=&moduleID=0&story=0&project=$project->id", "<i class='icon icon-plus'></i> " . $lang->project->createStory, '', "class='btn btn-primary'");?>
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
      <?php if(common::hasPriv('project', 'linkStory')):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('project', 'linkStory', "project=$project->id"), "<i class='icon icon-link'></i> " . $lang->project->linkStory, '', "class='btn btn-info'");?>
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
                    <span class='status status-<?php echo $story->status;?>' title='<?php echo $lang->story->status?>'><span class="label label-dot"></span> <?php echo $lang->story->statusList[$story->status];?></span>
                    <?php if(common::hasPriv('project', 'unlinkStory')):?>
                    <div class='pull-right'><?php echo html::a($this->createLink('project', 'unlinkStory', "projectID=$projectID&story=$story->id"), "<i class='icon icon-unlink icon-sm'></i>", 'hiddenwin', "title='{$lang->project->unlinkStory}'");?></div>
                    <?php endif;?>
                    <div class='pull-right text-muted story-estimate' title='<?php echo $lang->story->estimate?>'><?php echo $story->estimate . 'h ';?></div>
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
<?php js::set('projectID', $projectID);?>
<?php include '../../common/view/footer.html.php';?>
