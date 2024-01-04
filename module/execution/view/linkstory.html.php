<?php
/**
 * The link story view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: linkstory.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<style>
.search-form .form-actions {padding-bottom: 10px!important;}
</style>
<?php js::set('storyType', $storyType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->execution->linkStory;?></span></span>
  </div>
  <?php if(!isonlybody()):?>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-primary'");?>
  </div>
  <?php endif;?>
</div>
<div id="mainContent">
  <div class="cell space-sm">
    <div id='queryBox' data-module='story' class='show no-margin'></div>
  </div>
  <form class='main-table table-story' method='post' data-ride='table' id='linkStoryForm'>
    <table class='table table-fixed tablesorter' id='linkStoryList'>
      <thead>
        <tr>
          <th class='c-id'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->execution->pri;?>><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->story->title;?></th>
          <?php if($project->hasProduct):?>
          <th class='c-object'><?php echo $lang->story->product;?></th>
          <?php endif;?>
          <th class='c-module'><?php echo $lang->story->module;?></th>
          <th class='c-plan <?php if((empty($project->hasProduct) && $project->model != 'scrum') || $storyType != 'story') echo 'hide';?>'><?php echo $lang->story->plan;?></th>
          <?php if($storyType == 'requirement'):?>
          <?php if($object->model == 'ipd'):?>
          <th class='c-plan'><?php echo $lang->story->roadmap;?></th>
          <?php endif;?>
          <th class='c-status'><?php echo $lang->story->status;?></th>
          <?php else:?>
          <th class='c-stage'><?php echo $lang->story->stage;?></th>
          <?php endif;?>
          <?php if($project->hasProduct && $productType != 'normal'):?>
          <th class='c-branch'><?php echo $lang->product->branchName[$productType];?></th>
          <?php endif;?>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <?php if($storyType != 'requirement'):?>
          <th class='c-estimate text-right'><?php echo $lang->story->estimateAB;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
      <?php $storyCount = 0;?>
      <?php foreach($allStories as $story):?>
      <?php $storyLink = $this->app->tab == 'execution' ? $this->createLink('execution', 'storyView', "storyID=$story->id", '', true) : $this->createLink('projectstory', 'view', "storyID=$story->id", '', true);?>
      <tr>
        <td class='cell-id'>
          <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)));?>
          <?php echo html::hidden("products[$story->id]", $story->product);?>
        </td>
        <td>
          <?php if($story->pri):?>
          <span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span>
          <?php endif;?>
        </td>
        <td class='text-left nobr' title="<?php echo $story->title?>">
          <?php
          if($story->parent > 0) echo "<span class='label label-badge label-light'>{$lang->story->childrenAB}</span>";
          if(common::hasPriv('execution', 'storyView'))
          {
              echo html::a($storyLink, $story->title, '', "class='iframe' data-width='80%'");
          }
          else
          {
              echo '<a>' . $story->title . '</a>';
          }
          ?>
        </td>
        <?php if($project->hasProduct):?>
        <td class='text-left' title='<?php echo $products[$story->product]->name?>'><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=&param=0&storyType=story&orderBy=&recTotal=0&recPerPage=20&pageID=1&projectID={$project->id}"), $products[$story->product]->name);?></td>
        <?php endif;?>
        <td class='c-module text-left' title='<?php echo zget($modules, $story->module, '')?>'><?php echo zget($modules, $story->module, '')?></td>
        <td class='text-ellipsis <?php if((empty($project->hasProduct) && $project->model != 'scrum') || $storyType != 'story') echo 'hide';?>' title='<?php echo $story->planTitle;?>'><?php echo $story->planTitle;?></td>
        <?php if($storyType == 'requirement'):?>
        <?php if($object->model == 'ipd'):?>
        <td title="<?php echo zget($roadmaps, $story->roadmap, '');?>"><?php echo zget($roadmaps, $story->roadmap, '');?></td>
        <?php endif;?>
        <td><?php echo zget($lang->story->statusList, $story->status);?></td>
        <?php else:?>
        <td><?php echo zget($lang->story->stageList, $story->stage);?></td>
        <?php endif;?>
        <?php if($project->hasProduct && $productType != 'normal'):?>
        <td><?php if(isset($branchGroups[$story->product][$story->branch])) echo $branchGroups[$story->product][$story->branch];?></td>
        <?php endif;?>
        <td class='c-user'><?php echo zget($users, $story->openedBy);?></td>
        <?php if($storyType != 'requirement'):?>
        <td class='text-right c-estimate' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
        <?php endif;?>
      </tr>
      <?php $storyCount++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($storyCount):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton('', '', 'btn');?>
      </div>
      <?php $pager->show('right', 'pagerjs')?>
    </div>
    <?php else:?>
    <div class="table-empty-tip">
      <?php $emptyTips = ($app->rawModule == 'execution' and !$project->hasProduct) ? 'projectNoStories' : 'whyNoStories';?>
      <?php $app->loadLang('projectstory');?>
      <p><span class="text-muted"><?php echo $project->multiple ? $lang->{$app->rawModule}->{$emptyTips} : $lang->projectstory->whyNoStories;?></p>
    </div>
    <?php endif;?>
  </form>
</div>
<?php if(commonModel::isTutorialMode()): ?>
<style>
#linkStoryList .c-user,
#linkStoryList .c-estimate,
#linkStoryList .c-module {display: none;}
</style>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
