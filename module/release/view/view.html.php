<?php
/**
 * The view file of release module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->release->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->release->confirmUnlinkBug)?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['release']);?> <strong><?php echo $release->id;?></strong></span>
    <strong><?php echo $release->name;?></strong>
    <?php if($release->deleted):?>
    <span class='label label-danger'><?php echo $lang->release->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");
    if(!$release->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        if(common::hasPriv('release', 'changeStatus'))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
            echo html::a(inlink('changeStatus', "releaseID=$release->id&type=$changedStatus"), '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ' . $lang->release->changeStatusList[$changedStatus], 'hiddenwin', "class='btn'");
        }
        if(common::hasPriv('release', 'linkStory')) echo html::a(inlink('view', "releaseID=$release->id&type=story&link=true"), '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn'");
        if(common::hasPriv('release', 'linkBug') and $this->config->global->flow != 'onlyStory') echo html::a(inlink('view', "releaseID=$release->id&type=bug&link=true"),   '<i class="icon-bug"></i> '  . $lang->release->linkBug,   '', "class='btn'");
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('release', 'edit',   "releaseID=$release->id");
        common::printIcon('release', 'delete', "releaseID=$release->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        echo common::printRPN($browseLink, $lang->goback);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='tabs'>
        <?php $countStories = count($stories); $countBugs = count($bugs); $countLeftBugs = count($leftBugs);?>
        <ul class='nav nav-tabs'>
          <li <?php if($type == 'story')   echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'green') . ' ' . $lang->release->stories;?></a></li>
          <?php if($this->config->global->flow != 'onlyStory'):?>
          <li <?php if($type == 'bug')     echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'green') . ' ' . $lang->release->bugs;?></a></li>
          <li <?php if($type == 'leftBug') echo "class='active'"?>><a href='#leftBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'red') . ' ' . $lang->release->generatedBugs;?></a></li>
          <?php endif;?>
          <li <?php if($type == 'releaseInfo') echo "class='active'"?>><a href='#releaseInfo' data-toggle='tab'><?php echo html::icon($lang->icons['plan'], 'blue') . ' ' . $lang->release->view;?></a></li>
          <?php if($countStories or ($this->config->global->flow != 'onlyStory' and ($countBugs or $countLeftBugs))):?>
          <li class='pull-right'><div><?php common::printIcon('release', 'export', '', '', 'button', '', '', "export btn-sm");?></div></li>
          <?php endif;?>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
            <?php if(common::hasPriv('release', 'linkStory')):?>
            <div class='action'><?php echo html::a("javascript:showLink({$release->id}, \"story\")", '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn btn-sm btn-primary'");?></div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkStory', "release=$release->id");?>" id='linkedStoriesForm'>
            <table class='table table-hover table-condensed table-striped tablesorter table-fixed table-selectable' id='storyList'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th class='w-pri'><?php echo $lang->priAB;?></th>
                  <th><?php echo $lang->story->title;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-hour'><?php echo $lang->story->estimateAB;?></th>
                  <th class='w-hour'><?php echo $lang->statusAB;?></th>
                  <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
                  <th class='w-50px'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkStory');?>
              <?php foreach($stories as $storyID => $story):?>
              <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
              <tr class='text-center'>
                <td class='cell-id'>
                  <?php if($canBatchUnlink):?>
                  <input type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
                  <?php endif;?>
                  <?php echo sprintf('%03d', $story->id);?>
                </td>
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td>
                  <?php
                  if(common::hasPriv('release', 'unlinkStory'))
                  {
                      $unlinkURL = $this->createLink('release', 'unlinkStory', "releaseID=$release->id&story=$story->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->release->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if($countStories and $canBatchUnlink) echo html::selectButton() . html::submitButton($lang->release->batchUnlink);?>
                      <div class='text'><?php echo sprintf($lang->release->finishStories, $countStories);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'bug') echo 'active'?>' id='bugs'>
            <?php if(common::hasPriv('release', 'linkBug')):?>
            <div class='action'><?php echo html::a("javascript:showLink({$release->id}, \"bug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-sm btn-primary'");?></div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "releaseID=$release->id");?>" id='linkedBugsForm'>
            <table class='table table-hover table-condensed table-striped tablesorter table-fixed table-selectable' id='bugList'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th><?php echo $lang->bug->title;?></th>
                  <th class='w-100px'><?php echo $lang->bug->status;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-date'><?php echo $lang->bug->openedDateAB;?></th>
                  <th class='w-user'><?php echo $lang->bug->resolvedByAB;?></th>
                  <th class='w-100px'><?php echo $lang->bug->resolvedDateAB;?></th>
                  <th class='w-50px'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td class='cell-id'>
                  <?php if($canBatchUnlink):?>
                  <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                  <?php endif;?>
                  <?php echo sprintf('%03d', $bug->id);?>
                </td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
                <td>
                  <?php
                  if(common::hasPriv('release', 'unlinkBug'))
                  {
                      $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->release->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if($countBugs and $canBatchUnlink) echo html::selectButton() . html::submitButton($lang->release->batchUnlink);?>
                      <div class='text'><?php echo sprintf($lang->release->resolvedBugs, $countBugs);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'leftBug') echo 'active'?>' id='leftBugs'>
            <?php if(common::hasPriv('release', 'linkBug')):?>
            <div class='action'><?php echo html::a("javascript:showLink({$release->id}, \"leftBug\")", '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-sm btn-primary'");?></div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "releaseID=$release->id&type=leftBug");?>" id='linkedBugsForm'>
            <table class='table table-hover table-condensed table-striped tablesorter table-fixed table-selectable' id='leftBugList'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
                  <th><?php echo $lang->bug->title;?></th>
                  <th class='w-100px'><?php echo $lang->bug->status;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-150px'><?php echo $lang->bug->openedDateAB;?></th>
                  <th class='w-50px'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <?php $canBatchUnlink = common::hasPriv('release', 'batchUnlinkBug');?>
              <?php foreach($leftBugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td class='cell-id'>
                  <?php if($canBatchUnlink):?>
                  <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                  <?php endif;?>
                  <?php echo sprintf('%03d', $bug->id);?>
                </td>
                <td><span class='severity<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span></td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo $bug->openedDate?></td>
                <td>
                  <?php
                  if(common::hasPriv('release', 'unlinkBug'))
                  {
                      $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id&type=leftBug");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"leftBugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->release->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='7'>
                    <div class='table-actions clearfix'>
                      <div class='text'>
                        <?php if($countLeftBugs and $canBatchUnlink) echo html::selectButton() . html::submitButton($lang->release->batchUnlink);?>
                        <?php echo sprintf($lang->release->createdBugs, $countLeftBugs);?>
                      </div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
            </form>
          </div>

          <div class='tab-pane <?php if($type == 'releaseInfo') echo 'active'?>' id='releaseInfo'>
            <div>
              <fieldset>
                <legend><?php echo $lang->release->desc;?></legend>
                <div class='article-content'><?php echo $release->desc;?></div>
              </fieldset>
              <fieldset>
                <legend><?php echo $lang->release->basicInfo?></legend>
                <table class='table table-data table-condensed table-borderless table-fixed'>
                  <tr>
                    <th class='w-80px'><?php echo $lang->release->product;?></th>
                    <td><?php echo $release->productName;?></td>
                  </tr>  
                  <?php if($release->productType != 'normal'):?>
                  <tr>
                    <th><?php echo $lang->product->branch;?></th>
                    <td><?php echo $branchName;?></td>
                  </tr>
                  <?php endif;?>
                  <tr>
                    <th><?php echo $lang->release->name;?></th>
                    <td><?php echo $release->name;?></td>
                  </tr>  
                  <tr>
                    <th><?php echo $lang->release->build;?></th>
                    <td title='<?php echo $release->buildName?>'>
                    <?php echo ($release->project) ? html::a($this->createLink('build', 'view', "buildID=$release->buildID"), $release->buildName, '_blank') : $release->buildName;?>
                    </td>
                  </tr>  
                  <tr>
                    <th><?php echo $lang->release->status;?></th>
                    <td><?php echo $lang->release->statusList[$release->status];?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->release->date;?></th>
                    <td><?php echo $release->date;?></td>
                  </tr>
                </table>
              </fieldset>
              <fieldset>
                <legend><?php echo $lang->files?></legend>
                <div class='article-content'>
                <?php
                if($release->files)
                {
                    echo $this->fetch('file', 'printFiles', array('files' => $release->files, 'fieldset' => 'false'));
                }
                elseif($release->filePath)
                {
                    echo $lang->release->filePath . html::a($release->filePath, $release->filePath, '_blank');
                }
                elseif($release->scmPath)
                {
                    echo $lang->release->scmPath . html::a($release->scmPath, $release->scmPath, '_blank');
                }
                ?>
                </div>
              </fieldset>
              <?php include '../../common/view/action.html.php';?>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<style>
.tabs .tab-content .tab-pane .action{position: absolute; right: <?php echo ($countStories or $countBugs or $countLeftBugs) ? '110px' : '-1px'?>; top: 0px;}
</style>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('releaseID', $release->id)?>
<?php js::set('type', $type)?>
<?php include '../../common/view/footer.html.php';?>
