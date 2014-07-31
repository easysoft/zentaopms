<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='TESTTASK'><?php echo html::icon($lang->icons['testtask']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo $task->name;?></strong>
    <?php if($task->deleted):?>
    <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browse', "productID=$task->product");
    $actionLinks = '';
    if(!$task->deleted)
    {
        ob_start();

        echo "<div class='btn-group'>";
        common::printIcon('testtask', 'start',    "taskID=$task->id", $task);
        common::printIcon('testtask', 'close',    "taskID=$task->id", $task, 'button', '', '', 'text-danger');
        common::printIcon('testtask', 'cases',    "taskID=$task->id", $task, 'button', 'smile');
        common::printIcon('testtask', 'linkCase', "taskID=$task->id", $task, 'button', 'link');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printIcon('testtask', 'edit',     "taskID=$task->id");
        common::printIcon('testtask', 'delete',   "taskID=$task->id", '', 'button', '', 'hiddenwin');
        echo '</div>';

        echo "<div class='btn-group'>";
        common::printRPN($browseLink);
        echo '</div>';

        $actionLinks = ob_get_contents();
        ob_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#desc' data-toggle='tab'><?php echo $lang->testtask->legendDesc;?></a></li>
          <li><a href='#testScope' data-toggle='tab'><?php echo $lang->testtask->testScope;?></a></li>
          <li><a href='#cases' data-toggle='tab'><?php echo $lang->testtask->cases;?></a></li>
          <li><a href='#results' data-toggle='tab'><?php echo $lang->testtask->results;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='desc'>
            <div class='article-content'><?php echo $task->desc;?></div>
          </div>
          <div class='tab-pane' id='testScope'>
            <?php $countStories = count($stories); $countBugs = count($bugs); ?>
            <table class='table table-hover table-condensed tablesorter mgb-20'>
              <caption class='text-left'><i class='icon-lightbulb'></i> <strong><?php echo $lang->testtask->stories;?></strong> <span class='text-muted'><?php echo sprintf($lang->build->finishStories, $countStories);?></span></caption>
              <?php if($countStories > 0):?>
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
                <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
                <td class='text-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                <td><?php echo $users[$story->openedBy];?></td>
                <td><?php echo $story->estimate;?></td>
                <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
              </tr>
              <?php endforeach;?>
              <?php endif;?>
            </table>
            <table class='table table-hover table-condensed tablesorter'>
            <caption class='text-left'><i class='icon-bug'></i><strong><?php echo $lang->testtask->bugs;?></strong> <span class='text-muted'><?php echo sprintf($lang->build->resolvedBugs, $countBugs)?></span></caption>
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
            </table>
          </div>
          <div class='tab-pane' id='cases'>
            <table class='table table-condensed table-hover table-striped table-fixed' id='caseList'>
              <thead>
                <tr class='colhead'>
                  <th class='w-id'><nobr><?php echo $lang->idAB;?></nobr></th>
                  <th class='w-pri'>     <?php echo $lang->priAB;?></th>
                  <th>                   <?php echo $lang->testcase->title;?></th>
                  <th class='w-type'>    <?php echo $lang->testcase->type;?></th>
                  <th class='w-user'>    <?php echo $lang->testtask->assignedTo;?></th>
                  <th class='w-user'>    <?php echo $lang->testtask->lastRunAccount;?></th>
                  <th class='w-100px'>   <?php echo $lang->testtask->lastRunTime;?></th>
                  <th class='w-80px'>    <?php echo $lang->testtask->lastRunResult;?></th>
                  <th class='w-status'>  <?php echo $lang->statusAB;?></th>
                  <th class='w-100px {sorter: false}'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <tbody class='loadAjax' id='casesList' data-url='<?php echo $this->createLink('testtask', 'cases', "taskID=$task->id&browseType=all&param=0&orderBy=id_desc&recTotal=0&recPerPage=1000&pageID=1");?> #caseList tbody tr'>
                <tr><td colspan='10' class='text-center'><i class='icon-spin icon-spinner'></i></td></tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='results'>
          </div>
        </div>
      </div>
    </div>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->testtask->legendReport;?></legend>
        <div class='article-content'><?php echo $task->report;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testtask->legendBasicInfo;?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
          <tr>
            <th class='w-70px'><?php echo $lang->testtask->project;?></th>
            <td><?php echo html::a($this->createLink('project', 'story', "projectID=$task->project"), $task->projectName);?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->build;?></th>
            <td><?php $task->build == 'trunk' ? print('Trunk') : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->build->scmPath;?></th>
            <td style='word-break:break-all;'><?php strpos($build->scmPath,  'http') === 0 ? printf(html::a($build->scmPath))  : printf($build->scmPath);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->build->filePath;?></th>
            <td style='word-break:break-all;'><?php strpos($build->filePath, 'http') === 0 ? printf(html::a($build->filePath)) : printf($build->filePath);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testtask->owner;?></th>
            <td><?php echo $users[$task->owner];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->pri;?></th>
            <td><?php echo $task->pri;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->begin;?></th>
            <td><?php echo $task->begin;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->end;?></th>
            <td><?php echo $task->end;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->status;?></th>
            <td><?php echo $lang->testtask->statusList[$task->status];?></td>
          </tr>  
       </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
