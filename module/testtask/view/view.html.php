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
<div id='titlebar'>
  <div id='main' <?php if($task->deleted) echo "class='deleted'";?>>TESTTASK #<?php echo $task->id . ' ' . $task->name;?></div>
  <div>
    <?php
    $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browse', "productID=$task->product");
    $actionLinks = '';
    if(!$task->deleted)
    {
        ob_start();

        common::printIcon('testtask', 'start',    "taskID=$task->id", $task);
        common::printIcon('testtask', 'close',    "taskID=$task->id", $task);
        common::printIcon('testtask', 'cases',    "taskID=$task->id", $task);
        common::printIcon('testtask', 'linkCase', "taskID=$task->id", $task);

        common::printDivider();
        common::printIcon('testtask', 'edit',     "taskID=$task->id");
        common::printIcon('testtask', 'delete',   "taskID=$task->id", '', 'button', '', 'hiddenwin');

        $actionLinks = ob_get_contents();
        ob_clean();
        echo $actionLinks;
    }
    common::printRPN($browseLink);
    ?>
  </div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->testtask->legendDesc;?></legend>
        <div class='content'><?php echo $task->desc;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->testtask->legendReport;?></legend>
        <div class='content'><?php echo $task->report;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center actionlink'><?php echo $actionLinks;?></div>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->testtask->legendBasicInfo;?></legend>
        <table class='table-1 a-left fixed'>
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->project;?></th>
            <td><?php echo html::a($this->createLink('project', 'story', "projectID=$task->project"), $task->projectName);?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->build;?></th>
            <td><?php $task->build == 'trunk' ? print('Trunk') : print(html::a($this->createLink('build', 'view', "buildID=$task->build"), $task->buildName));?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->owner;?></th>
            <td><?php echo $users[$task->owner];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->pri;?></th>
            <td><?php echo $task->pri;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->begin;?></th>
            <td><?php echo $task->begin;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->end;?></th>
            <td><?php echo $task->end;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->status;?></th>
            <td><?php echo $lang->testtask->statusList[$task->status];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->testtask->report;?></th>
            <td class='content'><?php echo $task->report;?></td>
          </tr>  
       </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
