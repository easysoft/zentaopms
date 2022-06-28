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
<?php if($config->systemMode == 'classic' and $this->app->getClientLang() == 'en'):?>
<style>
.addon-execution {padding: 0px 20px}
</style>
<?php endif;?>
<div id='mainContent' class='main-row fade in'>
  <div class="side-col" id="sidebar">
    <div id="sidebarHeader">
      <div class="title">
      <?php echo $deptName ? $deptName : $lang->dept->common;?>
      <?php if($deptName) echo html::a(inlink('manageProjectadmin', "groupID=$groupID"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
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
    <div class='cell'>
      <form class='table-members' method='post' target='hiddenwin'>
        <table class='table table-bordered'>
          <tr>
            <th class='text-center w-300px'><?php echo $lang->group->inside;?></th>
            <th class='text-center'><?php echo $lang->group->object;?></th>
            <th class='w-70px'><?php echo $lang->group->allCheck . " <span data-toggle='tooltip' class='text-help' title='{$lang->group->allTips}' ><i class='icon-help'></i></sapn>";?></th>
            <th class='w-100px text-center'><?php echo $lang->actions;?></th>
          </tr>
          <?php if($config->systemMode == 'new'):?>
          <?php if($projectAdmins):?>
          <?php foreach($projectAdmins as $account => $group):?>
          <tr class="line<?php echo $group->group;?>">
            <td rowspan='4'>
              <div class='group-item'><?php echo html::select("members[$group->group][]", $allUsers, $account, "class='form-control picker-select' multiple");?></div>
            </td>
            <td>
              <div class='input-group'>
                <?php $disabled = $group->programs == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-program'> <?php echo $lang->group->manageProgram;?></span>
                <?php echo html::select("program[$group->group][]", $programs, $group->programs == 'all' ? '' : $group->programs, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("programAll[$group->group]", array(1 => ''), $group->programs == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
            </td>
            <td rowspan='4' class='text-center'>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <?php $hidden = count($projectAdmins) == 1 ? 'hidden' : ''?>
              <button type="button" class="btn btn-link btn-icon btn-delete <?php echo $hidden;?>" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <tr class="line<?php echo $group->group;?>">
            <td>
              <div class='input-group'>
                <?php $disabled = $group->projects == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-project'> <?php echo $lang->group->manageProject;?></span>
                <?php echo html::select("project[$group->group][]", $projects, $group->projects == 'all' ? '' : $group->projects, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("projectAll[$group->group]", array(1 => ''), $group->projects == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <tr class="line<?php echo $group->group;?>">
            <td>
              <div class='input-group'>
                <?php $disabled = $group->products == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-product'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select("product[$group->group][]", $products, $group->products == 'all' ? '' : $group->products, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("productAll[$group->group]", array(1 => ''), $group->products == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <tr class="line<?php echo $group->group;?>">
            <td>
              <div class='input-group'>
                <?php $disabled = $group->executions == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-execution'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select("execution[$group->group][]", $executions, $group->executions == 'all' ? '' : $group->executions, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("executionAll[$group->group]", array(1 => ''), $group->executions == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
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
                <span class='input-group-addon addon-program'> <?php echo $lang->group->manageProgram;?></span>
                <?php echo html::select('program[1][]', $programs, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('programAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
            <td rowspan='4' class='text-center'>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <button type="button" class="btn btn-link btn-icon btn-delete hidden" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-project'> <?php echo $lang->group->manageProject;?></span>
                <?php echo html::select('project[1][]', $projects, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('projectAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-product'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select('product[1][]', $products, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('productAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-execution'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select('execution[1][]', $executions, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('executionAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <?php endif;?>
          <?php else:?>
          <?php if($projectAdmins):?>
          <?php foreach($projectAdmins as $account => $group):?>
          <tr class="line<?php echo $group->group;?>">
            <td rowspan='2'>
              <div class='group-item'><?php echo html::select("members[$group->group][]", $allUsers, $account, "class='form-control picker-select' multiple");?></div>
            </td>
            <td>
              <div class='input-group'>
                <?php $disabled = $group->products == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-product'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select("product[$group->group][]", $products, $group->products == 'all' ? '' : $group->products, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("productAll[$group->group]", array(1 => ''), $group->products == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
            </td>
            <td rowspan='2' class='text-center'>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <?php $hidden = count($projectAdmins) == 1 ? 'hidden' : '';?>
              <button type="button" class="btn btn-link btn-icon btn-delete <?php echo $hidden;?>" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <tr class="line<?php echo $group->group;?>">
            <td>
              <div class='input-group'>
                <?php $disabled = $group->executions == 'all' ? "disabled='disabled'" : '';?>
                <span class='input-group-addon addon-execution'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select("execution[$group->group][]", $executions, $group->executions == 'all' ? '' : $group->executions, "class='form-control picker-select' multiple $disabled");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox("executionAll[$group->group]", array(1 => ''), $group->executions == 'all' ? 1 : '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr class='line1'>
            <td rowspan='2'>
              <div class='group-item'><?php echo html::select('members[1][]', $allUsers, '', "class='form-control picker-select' multiple");?></div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-product'> <?php echo $lang->group->manageProduct;?></span>
                <?php echo html::select('product[1][]', $products, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('productAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
            <td rowspan='2' class='text-center'>
              <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
              <button type="button" class="btn btn-link btn-icon btn-delete hidden" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
            </td>
          </tr>
          <tr class='line1'>
            <td>
              <div class='input-group'>
                <span class='input-group-addon addon-execution'> <?php echo $lang->group->manageExecution;?></span>
                <?php echo html::select('execution[1][]', $executions, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
            <td>
              <?php echo html::checkbox('executionAll[1]', array(1 => ''), '', "onchange=toggleDisabled(this);");?>
            </td>
          </tr>
          <?php endif;?>
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
$(function()
{
    $('#dept' + deptID).closest('li').addClass('active');
    $('[data-toggle="tooltip"]').tooltip
    ({
        placement: 'right'
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
