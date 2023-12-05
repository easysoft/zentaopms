<?php
/**
 * The link story view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     build
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='queryBox' data-module='story' class='show'></div>
<div id='unlinkStoryList'>
  <form class='main-table table-story' data-ride='table' method='post' id='unlinkedStoriesForm' target='hiddenwin' action='<?php echo $this->createLink('build', 'linkStory', "buildID={$build->id}&browseType=$browseType&param=$param");?>'>
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedStories;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr class='text-center'>
          <th class='c-id text-left'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <th class="text-left"><?php echo $lang->story->title;?></th>
          <th class='c-user'>   <?php echo $lang->openedByAB;?></th>
          <th class='c-user'>   <?php echo $lang->assignedToAB;?></th>
          <th class='c-id text-right'><?php echo $lang->story->estimateAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
          <th class='c-status'><?php echo $lang->story->stageAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php $unlinkedCount = 0;?>
        <?php $objectID = $this->app->tab == 'execution' ? $build->execution : $build->project;?>
        <?php foreach($allStories as $story):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)), (in_array($story->stage, array('developed', 'closed', 'tested'))) ? $story->id : '');?>
          </td>
          <td><span class='label-pri label-pri-<?php echo $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'>
            <?php
            if($story->parent > 0) echo "<span class='label label-badge label-light'>{$lang->story->childrenAB}</span>";
            echo html::a($this->createLink('story', 'view', "storyID=$story->id&version=0&param=$objectID", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");
            ?>
          </td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo zget($users, $story->assignedTo);?></td>
          <td class='w-right' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
          <td style='padding-right:10px;'>
            <span class='status-story status-<?php echo $story->status?>'>
              <?php echo $this->processStatus('story', $story);?>
            </span>
          </td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($unlinkedCount):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->build->linkStory, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::a($this->createLink(($this->app->tab == 'project' ? 'projectbuild' : 'build'), 'view', "buildID={$build->id}&type=story") . "#app={$app->tab}", $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<script>
$(function()
{
    $('#unlinkStoryList .tablesorter').sortTable();
    setForm();
});
</script>
