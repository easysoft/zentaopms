<?php
/**
 * The edit view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::set('longTime', $lang->project->PRJLongTime);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol);?>
<?php js::set('PGMParentBudget', $lang->project->PGMParentBudget);?>
<?php js::set('future', $lang->project->future);?>
<?php js::set('PGMList', $PGMList);?>
<?php $aclList = $project->parent ? $lang->project->subPGMAclList : $lang->project->PGMAclList;?>
<?php $requiredFields = $config->project->PGMEdit->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->PGMEdit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->project->PGMParent;?></th>
          <td class="col-main"><?php echo html::select('parent', $parents, $project->parent, "class='form-control chosen' onchange=setBudgetTipsAndAclList(this.value)");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->project->PGMName;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PGMPM;?></th>
          <td><?php echo html::select('PM', $pmUsers, $project->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PGMBudget;?></th>
          <td>
            <div class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder=' . $lang->project->PGMParentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget : '';?>
              <?php echo html::input('budget', $project->budget != 0 ? $project->budget : '', "class='form-control' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . ($project->budget == 0 ? 'disabled ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $parentProgram->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $project->budgetUnit, "class='form-control'");?>
              <?php endif;?>
            </div>
          </td>
          <td>
            <div class='checkbox-primary future w-70px'>
              <input type='checkbox' id='future' name='future' value='1' <?php if($project->budget == 0) echo 'checked';?> />
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php
                $disabledEnd = $project->end == LONG_TIME ? 'disabled' : '';
                $end         = $project->end == LONG_TIME ? $lang->project->PRJLongTime : $project->end;
                echo html::input('end', $end, "class='form-control form-date' $disabledEnd placeholder='" . $lang->project->end . "' required");
              ?>
            </div>
          </td>
          <?php $endValue = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;?>
          <td colspan='2'><?php echo html::radio('delta', $lang->project->endList , $endValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->realBegan;?></th>
          <td><?php echo html::input('realBegan', $project->realBegan, "class='form-control form-date'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PGMDesc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', $project->desc, "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($project->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, $project->whitelist, 'class="form-control chosen" multiple');?></td>
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
  <?php echo nl2br(html::radio('acl', $lang->project->PGMAclList, $project->acl == 'project' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='subPGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->subPGMAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
