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
<?php $aclList = $program->parent ? $lang->program->subPGMAclList : $lang->program->PGMAclList;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->PGMEdit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PGMParent;?></th>
          <td class="col-main"><?php echo html::select('parent', $parents, $program->parent, "class='form-control chosen'  onchange=setAclList(this.value)");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PGMName;?></th>
          <td class="col-main"><?php echo html::input('name', $program->name, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMCode;?></th>
          <td><?php echo html::input('code', $program->code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMPM;?></th>
          <td><?php echo html::select('PM', $pmUsers, $program->PM, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PO;?></th>
          <td><?php echo html::select('PO', $poUsers, $program->PO, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMBudget;?></th>
          <td><?php echo html::input('budget', $program->budget, "class='form-control'");?></td>
          <td style='float:left'><?php echo html::select('budgetUnit', $lang->program->unitList, $program->budgetUnit, "class='form-control'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $program->begin, "class='form-control form-date' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php $disabledEnd = $program->end == '2059-00-00' ? 'disabled' : '';?>
              <?php echo html::input('end', $program->end == '2059-00-00' ? '' : $program->end, "class='form-control form-date' $disabledEnd placeholder='" . $lang->program->end . "' required");?>
              <span class='input-group-addon' id='longTimeBox'>
                <div class="checkbox-primary">
                  <input type="checkbox" name="longTime" value="1" id="longTime" <?php echo $program->end == '2059-00-00' ? 'checked' : '';?>>
                  <label for="longTime"><?php echo $lang->program->PRJLongTime;?></label>
                </div>
              </span>
            </div>
          </td>
          <td colspan='2'></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->realBegan;?></th>
          <td><?php echo html::input('realBegan', $program->realBegan, "class='form-control form-date'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMDesc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=program&link=desc');?>
            <?php echo html::textarea('desc', $program->desc, "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $program->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($program->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, $program->whitelist, 'class="form-control chosen" multiple');?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div id='PGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->PGMAclList, $program->acl == 'program' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='subPGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->subPGMAclList, $program->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
