<?php
/**
 * The view file of build module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->build->confirmUnlinkStory)?>
<?php js::set('confirmUnlinkBug', $lang->build->confirmUnlinkBug)?>
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
      echo "<div class='btn-group'>";
      common::printIcon('build', 'linkStory',"buildID=$build->id", '', 'button', $lang->icons['link']);
      common::printIcon('build', 'linkBug',  "buildID=$build->id", '', 'button', $lang->icons['bug']);
      echo '</div>';
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
      <fieldset>
        <legend><?php echo $lang->build->desc;?></legend>
        <div class='article-content'><?php echo $build->desc;?></div>
      </fieldset>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $build->files, 'fieldset' => 'true'));?>
      <div class='tabs'>
      <?php $countStories = count($stories); $countBugs = count($bugs); $countNewBugs = count($generatedBugs);?>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story']) . ' ' . $lang->build->stories;?></a></li>
          <li><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->build->bugs;?></a></li>
          <li><a href='#newBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->build->generatedBugs;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='stories'>
            <?php if(common::hasPriv('build', 'linkStory')):?>
            <div class='action'><?php echo html::a(inlink('linkStory',"buildID=$build->id"), '<i class="icon-link"></i> ' . $lang->build->linkStory, '', "class='btn btn-sm'");?></div>
            <?php endif;?>
            <table class='table table-hover table-condensed table-borderless table-fixed' id='storyList'>
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
              <?php foreach($stories as $storyID => $story):?>
              <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
              <tr class='text-center'>
                <td><?php echo sprintf('%03d', $story->id);?></td>
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td>
                  <?php
                  if(common::hasPriv('build', 'unlinkStory'))
                  {
                      $unlinkURL = inlink('unlinkStory', "buildID=$build->id&story=$story->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->build->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <div class='text'><?php echo sprintf($lang->build->finishStories, $countStories);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class='tab-pane' id='bugs'>
            <?php if(common::hasPriv('build', 'linkBug')):?>
            <div class='action'><?php echo html::a(inlink('linkBug',"buildID=$build->id"), '<i class="icon-bug"></i> ' . $lang->build->linkBug, '', "class='btn btn-sm'");?></div>
            <?php endif;?>
            <table class='table table-hover table-condensed table-borderless table-fixed' id='bugList'>
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
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td><?php echo sprintf('%03d', $bug->id);?></td>
                <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
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
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->build->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <div class='text'><?php echo sprintf($lang->build->resolvedBugs, $countBugs);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class='tab-pane' id='newBugs'>
            <table class='table table-hover table-condensed table-borderless table-fixed'>
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
                <td><span class='severity<?php echo $bug->severity?>'><?php echo $bug->severity;?></span></td>
                <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
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
    </div>
  </div>
  <div class='col-side'>
    <div class='main-side main'>
      <fieldset>
        <legend><?php echo $lang->build->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
          <tr>
            <th class='w-80px'><?php echo $lang->build->product;?></th>
            <td><?php echo $build->productName;?></td>
          </tr>  
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
            <td style='word-break:break-all;'><?php strpos($build->scmPath,  'http') === 0 ? printf(html::a($build->scmPath))  : printf($build->scmPath);?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->build->filePath;?></th>
            <td style='word-break:break-all;'><?php strpos($build->filePath, 'http') === 0 ? printf(html::a($build->filePath)) : printf($build->filePath);?></td>
          </tr>
        </table>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
