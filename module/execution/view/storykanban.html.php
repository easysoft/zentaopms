<?php
/**
 * The kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     execution
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->execution->confirmUnlinkStory)?>
<?php js::set('canBeChanged', $canBeChanged)?>
<?php
$cols         = array('projected', 'developing', 'developed', 'testing', 'tested', 'verified', 'released');
$account      = $this->app->user->account;
$canLinkStory = $execution->hasProduct or $execution->multiple;
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $total = 0;
    foreach($stories as $col => $colStories)
    {
        if(!in_array($col, $cols)) continue;
        $total += count($colStories);
    }
    ?>
    <?php if(common::hasPriv('execution', 'storykanban')) echo html::a($this->createLink('execution', 'storykanban', "executionID=$execution->id"), "<span class='text'>{$lang->execution->kanban}</span> <span class='label label-light label-badge'>{$total}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('execution', 'story')):?>
    <div class="btn-group panel-actions">
      <?php echo html::a($this->createLink('execution', 'story', "executionID=$execution->id"), "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon switchBtn' title='{$lang->execution->list}' data-type='bylist'");?>
      <?php echo html::a($this->createLink('execution', 'storykanban', "executionID=$execution->id"), "<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon text-primary switchBtn' title='{$lang->execution->kanban}' data-type='bykanban'");?>
    </div>
    <?php endif;?>
    <div class='btn-group'>
      <?php common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export', '', "data-group='execution'");?>
    </div>
    <?php if($canBeChanged and $productID and !$this->loadModel('story')->checkForceReview()) common::printLink('story', 'create', "productID=$productID&branch=&moduleID=0&story=0&execution=$execution->id", "<i class='icon icon-plus'></i> " . $lang->execution->createStory, '', "class='btn btn-secondary' class='btn btn-link export' data-group='execution'");?>
    <?php
    if($canBeChanged)
    {
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("execution=$execution->id");
            echo html::a($this->createLink('tutorial', 'wizard', "module=execution&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->execution->linkStory}", '', "class='btn btn-primary'");
        }
        elseif($execution->hasProduct)
        {
            common::printLink('execution', 'linkStory', "execution=$execution->id", "<i class='icon icon-link'></i> " . $lang->execution->linkStory, '', "class='btn btn-primary'");
        }
    }
    ?>
  </div>
</div>
<div id="kanban" class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php if(empty($stories)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->story->noStory;?></span>
      <?php
      if(common::canModify('execution', $execution))
      {
          if($canLinkStory and $canBeChanged and common::hasPriv('execution', 'linkStory'))
          {
              echo html::a($this->createLink('execution', 'linkStory', "execution=$execution->id"), "<i class='icon icon-link'></i> " . $lang->execution->linkStory, '', "class='btn btn-info'");
          }
          else
          {
              $storyModuleID = (int)$this->cookie->storyModuleParam;
              if(common::hasPriv('story', 'create'))
              {
                  $createStoryLink = $this->createLink('story', 'create', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id");
                  echo html::a($createStoryLink, "<i class='icon icon-plus'></i> " . $lang->execution->createStory, '', "class='btn btn-info' data-app=$app->tab");
              }
          }
      }
      ?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-grouped text-center">
    <thead>
      <tr>
        <?php foreach ($cols as $col):?>
        <?php $storiesCount = empty($stories[$col]) ? 0 : count($stories[$col]);?>
        <th class='c-board s-<?php echo $col?>'><?php echo $lang->story->stageList[$col] . ' (' . $storiesCount . ')';?></th>
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
                  <?php
                      if(common::hasPriv('execution', 'storyView'))
                      {
                          echo html::a($this->createLink('execution', 'storyView', "story=$story->id", '', true), "#{$story->id} {$story->title}", '', 'class="title kanbaniframe" title="' . $story->title . '"');
                      }
                      else
                      {
                          echo "<a title='" . $story->title . "'>" . "#" . $story->id . $story->title . "</a>";
                      }
                  ?>
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
