<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row fade in'>
  <div class="side-col" id="sidebar">
    <div class='cell'>
      <div class='panel panel-sm'>
        <div class='panel-heading nobr'><strong><?php echo $lang->dept->common;?></strong></div>
        <?php echo $deptTree;?>
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class='cell'>
      <form class='table-members' method='post' target='hiddenwin'>
        <table class='table table-bordered'>
          <tr>
            <th class='text-center w-300px'><?php echo $lang->group->inside;?></th>
            <th class='text-center'><?php echo $lang->group->object;?></th>
            <th class='w-100px'><?php echo $lang->group->allCheck;?></th>
            <th class='w-100px'></th>
          </tr>
          <?php if(false):?>
          <?php foreach($userPrograms as $account => $project):?>
          <tr>
            <td id='group'>
              <div class='group-item'><?php echo html::select('members[]', $allUsers, $account, "class='form-control picker-select' multiple onchange=resetProgramName(this)");?></div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'> <?php echo $lang->group->manageProgram;?></span>
                <?php echo html::select("program[$account][]", $programs, '', "class='form-control picker-select' multiple");?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon'> <?php echo $lang->group->manageProject;?></span>
                <?php echo html::select("project[$account][]", $projects, $project, "class='form-control picker-select' multiple");?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select("product[$account][]", $products, '', "class='form-control picker-select' multiple");?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select("execution[$account][]", $executions, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr class='line1'>
            <td rowspan='4'>
              <div class='group-item'><?php echo html::select('members[1][]', $allUsers, '', "class='form-control picker-select' multiple");?></div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'> <?php echo $lang->group->manageProgram;?></span>
                <?php echo html::select('program[1][]', $programs, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('programAll[1]', array(1 => ''));?>
            </td>
            <td rowspan='4'>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-align'> <?php echo $lang->group->manageProject;?></span>
                <?php echo html::select('project[1][]', $projects, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('projectAll[1]', array(1 => ''));?>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-align'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select('product[1][]', $products, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('productAll[1]', array(1 => ''));?>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-align'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select('execution[1][]', $executions, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('executionAll[1]', array(1 => ''));?>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <td class='text-center form-actions' colspan='4'>
              <?php
              echo html::submitButton();
              echo html::linkButton($lang->goback, $this->createLink('group', 'browse'), 'self', '', 'btn btn-wide');
              echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
              ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php js::set('deptID', $deptID);?>
<script>
$(function(){ $('#dept' + deptID).closest('li').addClass('active');})
</script>
<?php include '../../common/view/footer.html.php';?>
