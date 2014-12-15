<?php
/**
 * The view file of release module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
        common::printIcon('release', 'linkStory',"releaseID=$release->id", '', 'button', $lang->icons['link']);
        common::printIcon('release', 'linkBug',  "releaseID=$release->id", '', 'button', $lang->icons['bug']);
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('release', 'edit',   "releaseID=$release->id");
        common::printIcon('release', 'delete', "releaseID=$release->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        echo common::printRPN($browseLink, $lang->goback);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_clean();
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
      <fieldset>
        <legend><?php echo $lang->release->desc;?></legend>
        <div class='article-content'><?php echo $release->desc;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->files?></legend>
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
      </fieldset>
      <div class='tabs'>
        <?php $countStories = count($stories); $countBugs = count($bugs); $countNewBugs = count($generatedBugs);?>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story']) . ' ' . $lang->release->stories;?></a></li>
          <li><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->release->bugs;?></a></li>
          <li><a href='#newBugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->release->generatedBugs;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='stories'>
            <?php if(common::hasPriv('release', 'linkStory')):?>
            <div class='action'><?php echo html::a(inlink('linkStory',"releaseID=$release->id"), '<i class="icon-link"></i> ' . $lang->release->linkStory, '', "class='btn btn-sm'");?></div>
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
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
                <td>
                  <?php
                  if(common::hasPriv('release', 'unlinkStory'))
                  {
                      $unlinkURL = $this->createLink('release', 'unlinkStory', "releaseID=$release->id&story=$story->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->release->unlinkStory}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if($countStories) common::printIcon('release', 'export', 'type=story', '', 'button', '', '', "export");?>
                      <div class='text'><?php echo sprintf($lang->release->finishStories, $countStories);?></div>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class='tab-pane' id='bugs'>
            <?php if(common::hasPriv('release', 'linkBug')):?>
            <div class='action'><?php echo html::a(inlink('linkBug',"releaseID=$release->id"), '<i class="icon-bug"></i> ' . $lang->release->linkBug, '', "class='btn btn-sm'");?></div>
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
                  if(common::hasPriv('release', 'unlinkBug'))
                  {
                      $unlinkURL = $this->createLink('release', 'unlinkBug', "releaseID=$release->id&bug=$bug->id");
                      echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"bugList\",confirmUnlinkBug)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->release->unlinkBug}'");
                  }
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='8'>
                    <div class='table-actions clearfix'>
                      <?php if(count($bugs)) common::printIcon('release', 'export', 'type=bug', '', 'button', '', '', 'export');?>
                      <div class='text'><?php echo sprintf($lang->release->resolvedBugs, $countBugs);?></div>
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
                  <th class='w-150px'><?php echo $lang->bug->openedDateAB;?></th>
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
                <td><?php echo $bug->openedDate?></td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='6'>
                    <div class='table-actions clearfix'>
                      <?php if(count($generatedBugs)) common::printIcon('release', 'export', 'type=newBugs', '', 'button', '', '', 'export');?>
                      <div class='text'><?php echo sprintf($lang->release->createdBugs, $countNewBugs);?></div>
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
        <legend><?php echo $lang->release->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless  table-fixed'>
          <tr>
            <th class='w-80px'><?php echo $lang->release->product;?></th>
            <td><?php echo $release->productName;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->release->name;?></th>
            <td><?php echo $release->name;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->release->build;?></th>
            <td><?php echo $release->buildName;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->release->date;?></th>
            <td><?php echo $release->date;?></td>
          </tr>
        </table>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
