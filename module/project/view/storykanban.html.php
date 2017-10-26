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
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['story']);?> <?php echo $lang->project->story;?></div>
  <div class='actions'>
    <div class='btn-group'>
    <?php 
    common::printIcon('project', 'story', "project=$project->id", '', 'button', 'list', '', '', '', '', $lang->project->story);
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export');

    $this->lang->story->create = $this->lang->project->createStory;
    if($productID and !$this->loadModel('story')->checkForceReview()) common::printIcon('story', 'create', "productID=$productID&branch=&moduleID=0&story=0&project=$project->id");

    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->project->linkStory}",'', "class='btn link-story-btn'");
    }
    else
    {
        common::printIcon('project', 'linkStory', "project=$project->id", '', 'button', 'link', '', 'link-story-btn');
    }
    ?>
    </div>
  </div>
  <div id='querybox' class='show'></div>
</div>
<?php
$cols = array('projected', 'developing', 'developed', 'testing', 'tested', 'verified', 'released');
$account = $this->app->user->account
?>
<div id='kanban'>
  <table class='boards-layout table' id='kanbanHeader'>
    <thead>
      <tr>
        <?php $endCol = array_pop($cols);?>
        <?php foreach ($cols as $col):?>
        <th class='col-<?php echo $col?>'><?php echo $lang->story->stageList[$col];?></th>
        <?php endforeach;?>
        <th class='col-<?php echo $endCol?>'><?php echo $lang->story->stageList[$endCol];?></th>
      </tr>
    </thead>
  </table>
  <?php $cols[] = $endCol;?>
  <table class='boards-layout table active-disabled table-bordered' id='kanbanWrapper'>
    <thead>
      <tr>
        <?php foreach($cols as $col):?><th class='col-<?php echo $col?>'></th><?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach($cols as $col):?>
        <td class='col-droppable col-<?php echo $col?>' data-id='<?php echo $col?>'>
        <?php if(!empty($stories[$col])):?>
          <?php foreach($stories[$col] as $story):?>
          <div class='board board-story board-story-<?php echo $col ?>;?>' data-id='<?php echo $story->id?>' id='task-<?php echo $story->id?>'>
            <div class='board-title'>
              <?php echo html::a($this->createLink('story', 'view', "story=$story->id", '', true), $story->title, '', 'class="kanbanFrame" title="' . $story->title . '"');?>
              <div class='board-actions'>
                <button type='button' class='btn btn-mini btn-link btn-info-toggle'><i class='icon-angle-down'></i></button>
                <div class='dropdown'>
                  <button type='button' class='btn btn-mini btn-link dropdown-toggle' data-toggle='dropdown'>
                    <span class='icon-ellipsis-v'></span>
                  </button>
                  <div class='dropdown-menu' style='left:-20px'>
                    <?php echo (common::hasPriv('project', 'unlinkStory')) ? html::a($this->createLink('project', 'unlinkStory', "story=$story->id"), $lang->project->unlinkStory, 'hiddenwin') : '';?>
                  </div>
                </div>
              </div>
            </div>
            <div class='board-footer clearfix'>
              <span class='task-id board-id' title='<?php echo $lang->story->id?>'><?php echo $story->id?></span> 
              <span class='task-pri pri-<?php echo $story->pri?>' title='<?php echo $lang->story->pri?>'></span>
              <div class='pull-right'>
                <span class='task-left' title='<?php echo $lang->story->status?>'><?php echo zget($this->lang->story->statusList, $story->status, '');?></span>
              </div>
            </div>
          </div>
          <?php endforeach?>
        <?php endif?>
        </td>
        <?php endforeach;?>
      </tr>
    </tbody>
  </table>
</div>
<script>
var projectID = <?php echo $projectID?>;
</script>
<?php include '../../common/view/footer.html.php';?>
