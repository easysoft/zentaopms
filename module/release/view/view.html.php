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
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'>
      <?php
      $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");
      if(!$release->deleted) echo $actionLinks; else echo common::printRPN($browseLink, $lang->goback);
      ?>
      </div>
      <div class='tabs'>
        <?php $countStories = count($stories); $countBugs = count($bugs); ?>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#stories' data-toggle='tab'><?php echo html::icon($lang->icons['story']) . ' ' . $lang->release->stories; if($countStories > 0) echo "<span class='label label-danger label-badge label-circle'>" . $countStories . "</span>";?></a></li>
          <li><a href='#bugs' data-toggle='tab'><?php echo html::icon($lang->icons['bug']) . ' ' . $lang->release->bugs; if($countBugs > 0) echo "<span class='label label-danger label-badge label-circle'>" . $countBugs . "</span>";?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='stories'>
            <table class='table table-hover table-condensed table-borderless'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th class='w-pri'><?php echo $lang->priAB;?></th>
                  <th><?php echo $lang->story->title;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-hour'><?php echo $lang->story->estimateAB;?></th>
                  <th class='w-hour'><?php echo $lang->statusAB;?></th>
                  <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
                </tr>
              </thead>
              <?php foreach($stories as $storyID => $story):?>
              <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id", '', true);?>
              <tr class='text-center'>
                <td><?php echo sprintf('%03d', $story->id);?></td>
                <td><span class='story-<?php echo 'pri' . $lang->story->priList[$story->pri]?>'><?php echo $lang->story->priList[$story->pri];?></span></td>
                <td class='text-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='7'>
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
            <table class='table table-hover table-condensed table-borderless'>
              <thead>
                <tr>
                  <th class='w-id'><?php echo $lang->idAB;?></th>
                  <th><?php echo $lang->bug->title;?></th>
                  <th class='w-100px'><?php echo $lang->bug->status;?></th>
                  <th class='w-user'><?php echo $lang->openedByAB;?></th>
                  <th class='w-date'><?php echo $lang->bug->openedDateAB;?></th>
                  <th class='w-user'><?php echo $lang->bug->resolvedByAB;?></th>
                  <th class='w-100px'><?php echo $lang->bug->resolvedDateAB;?></th>
                </tr>
              </thead>
              <?php foreach($bugs as $bug):?>
              <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id", '', true);?>
              <tr class='text-center'>
                <td><?php echo sprintf('%03d', $bug->id);?></td>
                <td class='text-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                <td><?php echo $lang->bug->statusList[$bug->status];?></td>
                <td><?php echo $users[$bug->openedBy];?></td>
                <td><?php echo substr($bug->openedDate, 5, 11)?></td>
                <td><?php echo $users[$bug->resolvedBy];?></td>
                <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
              </tr>
              <?php endforeach;?>
              <tfoot>
                <tr>
                  <td colspan='7'>
                    <div class='table-actions clearfix'>
                      <?php if(count($bugs)) common::printIcon('release', 'export', 'type=bug', '', 'button', '', '', 'export');?>
                      <div class='text'><?php echo sprintf($lang->release->resolvedBugs, $countBugs);?></div>
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
        <table class='table table-data table-condensed table-borderless'>
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
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
