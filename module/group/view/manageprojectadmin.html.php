<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row fade in'>
  <div class="side-col" id="sidebar">
    <div id="sidebarHeader">
      <div class="title">
      <span class='label label-id'><?php echo $group->id;?></span>
      <?php echo $group->name;?>
      </div>
    </div>
    <div class='cell'>
      <div class='panel panel-sm'>
        <div class='panel-heading nobr'><strong><?php echo $lang->dept->common;?></strong></div>
        <?php echo $deptTree;?>
      </div>
    </div>
  </div>
  <div class="main-col">
    <form class='main-table table-members' method='post' target='hiddenwin'>
      <table class='table table-form'>
        <?php if($userPrograms):?>
        <?php $i = 0;?>
        <?php foreach($userPrograms as $account => $program):?>
        <tr>
          <th class='w-100px'><?php echo $i == 0 ? $lang->group->inside : '';?></th>
          <td id='group' class='pv-10px'>
            <div class='group-item'><?php echo html::select('members[]', $allUsers, $account, "class='form-control chosen' onchange=resetProgramName(this)");?></div>
          </td>
          <td class='pv-10px' colspan='2'>
            <div class='input-group'>
              <span class='input-group-addon'> <?php echo $lang->group->manageProject;?></span>
              <?php echo html::select("program[$account][]", $programs, $program, "class='form-control chosen' multiple");?>
            </div>
          </td>
          <td class='w-100px'>
            <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
            <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php else:?>
        <tr>
          <th class='w-100px' rowspan='1'><?php echo $lang->group->inside;?></th>
          <td id='group' class='pv-10px'>
            <div class='group-item'><?php echo html::select('members[]', $allUsers, '', "class='form-control chosen' onchange=resetProgramName(this)");?></div>
          </td>
          <td class='pv-10px' colspan='2'>
            <div class='input-group'>
              <span class='input-group-addon'> <?php echo $lang->group->manageProject;?></span>
              <?php echo html::select('program[0][]', $programs, '', "class='form-control chosen' multiple");?>
            </div>
          </td>
          <td class='w-100px'>
            <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
            <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <td class='text-center form-actions' colspan='5'>
            <?php
            echo html::submitButton('', '', "btn btn-primary");
            echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
            echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
