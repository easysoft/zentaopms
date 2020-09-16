<?php
/**
 * The edit view of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('weekend', $config->project->weekend);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->parent;?></th>
          <td class="col-main"><?php echo html::select('parent', $parents, $program->parent, "class='form-control chosen'");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->program->name;?></th>
          <td class="col-main"><?php echo html::input('name', $program->name, "class='form-control' required");?></td>
          <td>
            <div class="checkbox-primary">
              <input type="checkbox" name="isCat" value="1" id="isCat" <?php if($program->isCat) echo "checked";?>>
              <label for="isCat"><?php echo $lang->program->parent;?></label>
            </div>
          </td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->code;?></th>
          <td><?php echo html::input('code', $program->code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <?php if($program->template == 'waterfall'):?>
        <tr>
          <th><?php echo $lang->program->category;?></th>
          <td><?php echo html::select('category', $lang->program->categoryList, $program->category, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->program->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, $program->PM, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->budget;?></th>
          <td><?php echo html::input('budget', $program->budget, "class='form-control'");?></td>
          <td style='float:left'><?php echo html::select('budgetUnit', $lang->program->unitList, $program->budgetUnit, "class='form-control'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $program->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php echo html::input('end', $program->end == '0000-00-00' ? '' : $program->end, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
              <span class='input-group-addon hidden' id='longTimeBox'>
                <div class="checkbox-primary">
                  <input type="checkbox" name="longTime" value="1" id="longTime" <?php if($program->end == '0000-00-00') echo "checked";?>>
                  <label for="longTime"><?php echo $lang->program->longTime;?></label>
                </div>
              </span>
            </div>
          </td>
          <td colspan='2'></td>
        </tr>
        <?php if($program->template == 'scrum'):?>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', $program->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->program->realBegan;?></th>
          <td><?php echo html::input('realBegan', $program->realBegan, "class='form-control form-date'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->teamname;?></th>
          <td><?php echo html::input('team', $program->team, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=program&link=desc');?>
            <?php echo html::textarea('desc', $program->desc, "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->privway;?></th>
          <td colspan='3'><?php echo html::radio('privway', $lang->program->privwayList, $program->privway, '', 'block');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('acl', $lang->program->aclList, $program->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr id='whitelistBox' <?php if($program->acl != 'custom') echo "class='hidden'";?>>
          <th><?php echo $lang->project->whitelist;?></th>
          <td colspan='3'><?php echo html::checkbox('whitelist', $groups, $program->whitelist, '', '', 'inline');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
      <?php echo html::hidden('products[]') . html::hidden('plans[]');?>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
