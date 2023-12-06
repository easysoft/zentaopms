<?php
/**
 * The link story view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     story
 * @version     $Id: linkstory.html.php 4129 2016-03-09 08:58:13Z chenfei $
 * @link        https://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('linkType', $type);?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $story->id;?></span>
      <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->story->linkStory;?></small>
      <?php endif;?>
    </h2>
  </div>
  <div id='queryBox' data-module='story' class='show divider'></div>
  <?php if(empty($stories2Link)):?>
  <div class='table-empty-tip'>
    <p>
      <span class='text-muted'><?php echo $storyType == 'story' ? $lang->story->noRequirement : $lang->story->noStory;?></span>
    </p>
  </div>
  <?php else:?>
  <form method='post' target='hiddenwin' id='linkStoryForm' class='main-table table-story'>
    <?php if($stories2Link):?>
    <table class='table tablesorter table-bordered' id='storyList'>
      <thead>
      <tr>
        <th class='c-id'>
          <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
            <label></label>
          </div>
          <?php echo $lang->idAB;?>
        </th>
        <th class='c-pri' title=<?php echo $lang->story->pri;?>><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th class='c-status'><?php echo $lang->story->status;?></th>
        <?php if($story->type == 'requirement'):?>
        <th class='c-stage'><?php echo $lang->story->stage;?></th>
        <?php endif;?>
        <th class='w-user'><?php echo $lang->openedByAB;?></th>
        <th class='c-estimate text-right'><?php echo $lang->story->estimateAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php $storyCount = 0;?>
      <?php foreach($stories2Link as $story2Link):?>
      <?php
         $storyLink = $this->createLink('story', 'view', "storyID=$story2Link->id");
         if($app->tab == 'project') $storyLink = $this->createLink('projectstory', 'view', "storyID=$story2Link->id");
      ?>
      <?php $canView   = common::hasPriv($story2Link->type, 'view');?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='stories[]'  value='<?php echo $story2Link->id;?>'/>
            <label></label>
          </div>
          <?php echo $canView ? html::a($storyLink, sprintf('%03d', $story2Link->id)) : sprintf('%03d', $story2Link->id);?>
        </td>
        <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story2Link->pri?>' title='<?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?>'><?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?></span></td>
        <td class='text-left nobr' title="<?php echo $story2Link->title?>"><?php echo $canView ? html::a($storyLink, $story2Link->title) : $story2Link->title;?></td>
        <td><?php echo $this->processStatus('story', $story2Link);?></td>
        <?php if($story->type == 'requirement'):?>
        <td><?php echo zget($lang->story->stageList, $story2Link->stage);?></td>
        <?php endif;?>
        <td><?php echo zget($users, $story2Link->openedBy);?></td>
        <td class='text-right'><?php echo $story2Link->estimate;?></td>
      </tr>
      <?php $storyCount ++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php if($storyCount) echo html::submitButton('', '', 'btn btn-default');?>
      </div>
      <?php echo html::hidden('story', $story->id);?>
    </div>
    <?php endif;?>
  </form>
  <?php endif;?>
</div>
<script>
$(function()
{
    <?php if($stories2Link):?>
    $('#linkStoryForm').table();
    setTimeout(function(){$('#linkStoryForm .table-footer').removeClass('fixed-footer');}, 100);
    <?php endif;?>
});
</script>
<?php include '../../common/view/footer.html.php';?>
