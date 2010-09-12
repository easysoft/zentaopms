<?php
/**
 * The view file of case module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1'> 
    <caption><?php echo $lang->testtask->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->name;?></th>
      <td class='<?php if($task->deleted) echo 'deleted';?>'><?php echo $task->name;?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->project;?></th>
      <td><?php echo $task->projectName;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->build;?></th>
      <td><?php $task->buildName ? print($task->buildName) : print($task->build);?></td>
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
      <th class='rowhead'><?php echo $lang->testtask->desc;?></th>
      <td class='content'><?php echo $task->desc;?></td>
    </tr>  
  </table>
  <div class='a-center f-16px strong'>
    <?php
    $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browse', "productID=$task->product");
    if(!$task->deleted)
    {
        common::printLink('testtask', 'cases',    "taskID=$task->id", $lang->testtask->cases);
        common::printLink('testtask', 'linkcase', "taskID=$task->id", $lang->testtask->linkCaseAB);
        common::printLink('testtask', 'edit',   "taskID=$task->id", $lang->edit);
        common::printLink('testtask', 'delete', "taskID=$task->id", $lang->delete, 'hiddenwin');
    }
    echo html::a($browseLink, $lang->goback);
    ?>
  </div>
  <?php include '../../common/view/action.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
