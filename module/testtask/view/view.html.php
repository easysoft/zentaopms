<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browse', "productID=$task->product");?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $task->id;?></span>
      <span class='text' title='<?php echo $task->name;?>'><?php echo $task->name;?></span>
      <?php if($task->deleted):?>
      <span class='label label-danger'><?php echo $lang->testtask->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class="col-8 main-col">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->testtask->desc;?></div>
        <div class="detail-content article-content"><?php echo !empty($task->desc) ? $task->desc : $lang->noData;?></div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true', 'object' => $task, 'method' => 'view', 'showDelete' => false));?>
      <?php if($task->report):?>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->testtask->report;?></div>
        <div class="detail-content article-content"><?php echo $task->report;?></div>
      </div>
      <?php endif;?>
      <?php
      $canBeChanged = common::canBeChanged('testtask', $task);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=testtask&objectID=$task->id");
      ?>
    </div>
    <?php $this->printExtendFields($task, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-secondary');?>
        <div class='divider'></div>
        <?php echo $this->testtask->buildOperateMenu($task, 'view');?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->testtask->legendBasicInfo;?></div>
        <div class="detail-content">
          <table class="table table-data table-fixed">
            <?php $isOnlybody = helper::inOnlyBodyMode(); ?>
            <?php if(!empty($execution->multiple)):?>
            <tr>
              <th class='w-90px'><?php echo $lang->testtask->execution;?></th>
              <td><?php echo $isOnlybody ? $task->executionName : html::a($this->createLink('execution', 'story', "executionID=$task->execution"), $task->executionName, '', "title='{$task->executionName}'");?></td>
            </tr>
            <?php endif;?>
            <tr>
              <th class='w-90px'><?php echo $lang->testtask->build;?></th>
              <td>
                <?php
                if($task->build == 'trunk')
                {
                    print($lang->trunk);
                }
                else
                {
                    $isOnlybody ? print($task->buildName) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName, '', "title='{$task->buildName}'"));
                }
                ?>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->type;?></th>
              <?php $testType = '';?>
              <?php foreach(explode(',', $task->type) as $type) $testType .= zget($lang->testtask->typeList, $type) . ' ';?>
              <td class="c-name" title="<?php echo $testType;?>"><?php echo $testType;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->owner;?></th>
              <td><?php echo zget($users, $task->owner);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->mailto;?></th>
              <td><?php $mailto = explode(',', str_replace(' ', '', $task->mailto)); foreach($mailto as $account) echo ' ' . zget($users, $account, $account);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->pri;?></th>
              <td><?php echo zget($lang->testtask->priList, $task->pri);?></td>
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
              <th><?php echo $lang->testtask->realFinishedDate;?></th>
              <td><?php if(!helper::isZeroDate($task->realFinishedDate)) echo $task->realFinishedDate;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->status;?></th>
              <td class='status-testtask status-<?php echo $task->status?>'><?php echo $this->processStatus('testtask', $task);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->testtask->testreport;?></th>
              <td class="c-name" title="<?php echo $testreportTitle;?>"><?php echo empty($task->testreport) ? '' : html::a($this->createLink('testreport', 'view', "reportID=$task->testreport"), $testreportTitle);?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($task, 'div', "position=right&inForm=0&inCell=1");?>
  </div>
</div>

<div id='mainActions' class='main-actions'>
  <?php common::printPreAndNext($browseLink);?>
</div>
<?php include '../../common/view/footer.html.php';?>
