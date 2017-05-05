<?php
/**
 * The view file of build module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->build->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->build->confirmUnlinkBug)?>
<?php if(isonlybody()):?>
<style>
#stories .action{display:none;}
#bugs .action{display:none;}
tbody tr td:last-child a{display:none;}
tbody tr td:first-child input{display:none;}
tfoot tr td .table-actions .btn{display:none;}
#titlebar .actions{display:none}
.row-table .col-side{display:none;}
</style>
<?php endif;?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['build']);?> <strong><?php echo $build->id;?></strong></span>
    <strong><?php echo $build->name;?></strong>
    <?php if($build->deleted):?>
    <span class='label label-danger'><?php echo $lang->build->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
  <?php
  $browseLink = $this->session->buildList ? $this->session->buildList : $this->createLink('project', 'build', "projectID=$build->project");
  if(!$build->deleted)
  {
      if($this->config->global->flow != 'onlyTest')
      {
          echo "<div class='btn-group'>";
          if(common::hasPriv('build', 'linkStory')) echo html::a(inlink('view', "buildID=$build->id&type=story&link=true"), '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn'");
          if(common::hasPriv('build', 'linkBug'))   echo html::a(inlink('view', "buildID=$build->id&type=bug&link=true"), '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn'");
          echo '</div>';
      }
      echo "<div class='btn-group'>";
      common::printIcon('build', 'edit',   "buildID=$build->id");
      common::printIcon('build', 'delete', "buildID=$build->id", '', 'button', '', 'hiddenwin');
      echo '</div>';
  }
  echo common::printRPN($browseLink);
  ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <?php if($this->config->global->flow == 'onlyTest'):?>
      <fieldset>
        <legend><?php echo $lang->build->desc;?></legend>
        <div class='article-content'><?php echo $build->desc;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'>
        <?php
        $browseLink = $this->session->buildList ? $this->session->buildList : $this->createLink('product', 'build', "productID=$build->product");
        if(!$build->deleted)
        { 
          common::printIcon('build', 'edit',   "buildID=$build->id");
          common::printIcon('build', 'delete', "buildID=$build->id", '', 'button', '', 'hiddenwin');
        }
        echo common::printRPN($browseLink);
        ?>
      </div>
      <?php else:?>
      <div class='tabs'>
      <?php $countStories = count($stories); $countBugs = count($bugs); $countNewBugs = count($generatedBugs);?>
        <ul class='nav nav-tabs'>
          <li <?php if($type == 'story')  echo "class='active'"?>><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story'], 'green') . ' ' . $lang->build->stories;?></a></li>
          <li <?php if($type == 'bug')    echo "class='active'"?>><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'green') . ' ' . $lang->build->bugs;?></a></li>
          <li <?php if($type == 'newbug') echo "class='active'"?>><a href='#newBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug'], 'red') . ' ' . $lang->build->generatedBugs;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane <?php if($type == 'story') echo 'active'?>' id='stories'>
            <?php if(common::hasPriv('build', 'linkStory')):?>
            <div class='action'><?php echo html::a("javascript:showLink($build->id, \"story\")", '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn btn-sm btn-primary'");?></div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action='<?php echo inlink('batchUnlinkStory', "buildID={$build->id}")?>' id='linkedStoriesForm'>
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
              <?php $canBatchUnlink = common::hasPriv('build', 'batchUnlinkStory');?>
              <?php foreach($stories as $storyID => $story):?>
              <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
              <tr class='text-center'>
                <td class='cell-id'>
                  <?php if($canBatchUnlink):?>
                  <input type='checkbox' name='unlinkStories[]'  value='<?php echo $story->id;?>'/> 
                  <?php endif;?>
                  <?php echo sprintf('%03d', $story->id);?>
                </td>
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $story->title?>'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td>
                  <?php
                  if(common::hasPriv('build', 'unlinkStory'))
                  {
                      $unlinkURL = inlink('unlinkStory', "buildID=$build->id&story=$story->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->build->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if($countStories and $canBatchUnlink) echo html::selectButton() . html::submitButton($lang->build->batchUnlink);?>
                      <div class='text'><?php echo sprintf($lang->build->finishStories, $countStories);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'bug') echo 'active'?>' id='bugs'>
            <?php if(common::hasPriv('build', 'linkBug')):?>
            <div class='action'><?php echo html::a("javascript:showLink($build->id, \"bug\")", '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn btn-sm btn-primary'");?></div>
            <div class='linkBox'></div>
            <?php endif;?>
            <form method='post' target='hiddenwin' action="<?php echo inLink('batchUnlinkBug', "build=$build->id");?>" id='linkedBugsForm'>
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
              <?php $canBatchUnlink = common::hasPriv('build', 'batchUnlinkBug');?>
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td class='cell-id'>
                  <?php if($canBatchUnlink):?>
                  <input type='checkbox' name='unlinkBugs[]'  value='<?php echo $bug->id;?>'/> 
                  <?php endif;?>
                  <?php echo sprintf('%03d', $bug->id);?>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
                <td>
                  <?php
                  if(common::hasPriv('build', 'unlinkBug'))
                  {
                      $unlinkURL = inlink('unlinkBug', "buildID=$build->id&bug=$bug->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-unlink"></i>', '', "class='btn-icon' title='{$lang->build->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if($countBugs and $canBatchUnlink) echo html::selectButton() . html::submitButton($lang->build->batchUnlink);?>
                      <div class='text'><?php echo sprintf($lang->build->resolvedBugs, $countBugs);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
            </form>
          </div>
          <div class='tab-pane <?php if($type == 'newbug') echo 'active'?>' id='newBugs'>
            <table class='table table-hover table-condensed table-striped tablesorter table-fixed'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th class='w-severity'><?php echo $lang->bug->severityAB;?></th>
                  <th><?php echo $lang->bug->title;?></th>
                  <th class='w-100px'><?php echo $lang->bug->status;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-date'><?php echo $lang->bug->openedDateAB;?></th>
                  <th class='w-user'><?php echo $lang->bug->resolvedByAB;?></th>
                  <th class='w-100px'><?php echo $lang->bug->resolvedDateAB;?></th>
                </tr>
              </thead>
              <?php foreach($generatedBugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td><?php echo sprintf('%03d', $bug->id);?></td>
                <td><span class='severity<?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span></td>
                <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <div class='text'><?php echo sprintf($lang->build->createdBugs, $countNewBugs);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main-side main'>
      <?php if($this->config->global->flow != 'onlyTest'):?>
      <fieldset>
        <legend><?php echo $lang->build->desc;?></legend>
        <div class='article-content'><?php echo $build->desc;?></div>
      </fieldset>
      <?php endif;?>
      <fieldset>
        <legend><?php echo $lang->build->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
          <tr>
            <th class='w-80px'><?php echo $lang->build->product;?></th>
            <td><?php echo $build->productName;?></td>
          </tr>  
          <?php if($build->productType != 'normal'):?>
          <tr>
            <th><?php echo $lang->product->branch;?></th>
            <td><?php echo $branchName;?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->build->name;?></th>
            <td><?php echo $build->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->build->builder;?></th>
            <td><?php echo $users[$build->builder];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->build->date;?></th>
            <td><?php echo $build->date;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->build->scmPath;?></th>
            <td style='word-break:break-all;'><?php echo html::a($build->scmPath, $build->scmPath, '_blank')?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->build->filePath;?></th>
            <td style='word-break:break-all;'><?php echo html::a($build->filePath, $build->filePath, '_blank');?></td>
          </tr>
        </table>
      </fieldset>
      <?php if($this->config->global->flow != 'onlyTest'):?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
      <?php include '../../common/view/action.html.php';?>
      <?php endif;?>
    </div>
  </div>
</div>
<?php if($this->config->global->flow != 'onlyTest'):?>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('buildID', $build->id)?>
<?php js::set('type', $type)?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
