<?php
/**
 * The link story view of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      yuchun li
 * @package     repo
 * @version     $Id: linkstory.html.php 5096 2022-07-25 07:02:43Z liycuhun@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<style>
.search-form #story-search .form-actions {width: 115px !important;}
#queryBox.show {min-height: 66px;}
</style>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2><?php echo $lang->repo->linkStory;?></h2>
  </div>
  <div id='queryBox' data-module='story' class='show no-margin'></div>
  <form class="main-table table-story" data-ride="table" method="post" target='hiddenwin' id='linkStoryForm' action="<?php echo $this->createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy=$orderBy")?>">
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedStories;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr>
          <th class='c-id text-left'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <th class='c-plan'><?php echo $lang->story->plan;?></th>
          <th class='c-module'><?php echo $lang->story->module;?></th>
          <th class='text-left'><?php echo $lang->story->title;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-user'><?php echo $lang->assignedToAB;?></th>
          <th class='c-number text-right'><?php echo $lang->story->estimateAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
          <th class='c-stage'><?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)));?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
          <td><?php echo $story->planTitle;?></td>
          <td title='<?php echo zget($modules, $story->module, '/')?>' class='text-left'><?php echo zget($modules, $story->module, '/');?></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'>
            <?php
            if($story->parent > 0) echo "<span class='label label-badge label-light' title={$lang->story->children}>{$lang->story->childrenAB}</span>";
            echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");
            ?>
          </td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo zget($users, $story->assignedTo);?></td>
          <td class='text-right'title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
          <td>
            <span class='status-story status-<?php echo $story->status?>'>
              <?php echo $this->processStatus('story', $story);?>
            </span>
          </td>
          <td class='text-center'><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($unlinkedCount):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->productplan->linkStory, '', 'btn');?>
      </div>
      <?php endif;?>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <iframe frameborder="0" name="hiddenwin" id="hiddenwin" scrolling="no" class="debugwin hidden"></iframe>
</div>
<script>
$(function()
{
    $('#unlinkStoryList .tablesorter').sortTable();
    setForm();
});
</script>
