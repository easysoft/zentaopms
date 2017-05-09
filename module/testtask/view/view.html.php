<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
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
        common::printIcon('testtask', 'start',    "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('testtask', 'close',    "taskID=$task->id", $task, 'button', '', '', 'iframe', true);
        common::printIcon('testtask', 'block',    "taskID=$task->id", $task, 'button', 'pause', '', 'iframe', true);
        common::printIcon('testtask', 'activate', "taskID=$task->id", $task, 'button', 'magic', '', 'iframe', true);
        common::printIcon('testtask', 'cases',    "taskID=$task->id", $task, 'button', 'sitemap');
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
        ob_end_clean();
        echo $actionLinks;
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->testtask->legendDesc;?></legend>
        <div class='article-content'><?php echo $task->desc;?></div>
      </fieldset>
      <?php if($task->report):?>
      <fieldset>
        <legend><?php echo $lang->testtask->legendReport;?></legend>
        <div class='article-content'><?php echo $task->report;?></div>
      </fieldset>
      <?php endif;?>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testtask->legendBasicInfo;?></legend>
        <table class='table table-data table-condensed table-borderless table-fixed'>
          <?php if($this->config->global->flow != 'onlyTest'):?>
          <tr>
            <th class='w-60px'><?php echo $lang->testtask->project;?></th>
            <td><?php echo html::a($this->createLink('project', 'story', "projectID=$task->project"), $task->projectName);?></td>
          </tr>  
          <?php endif;?>
          <tr>
            <th class='w-60px'><?php echo $lang->testtask->build;?></th>
            <td><?php $task->build == 'trunk' ? print($lang->trunk) : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->owner;?></th>
            <td><?php echo $users[$task->owner];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testtask->mailto;?></th>
            <td><?php $mailto = explode(',', str_replace(' ', '', $task->mailto)); foreach($mailto as $account) echo ' ' . zget($users, $account, $account);?></td> 
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
            <td class='task-<?php echo $task->status?>'><?php echo $lang->testtask->statusList[$task->status];?></td>
          </tr>  
       </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
