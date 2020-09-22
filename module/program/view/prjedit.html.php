<?php
/**
 * The prjedit view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->project->weekend);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->PRJEdit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PRJTemplate;?></th>
          <td><?php echo zget($lang->program->templateList, $project->model, '');?></td><td></td><td>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMParent;?></th>
          <td><?php echo html::select('parent', $parents, $project->parent, "class='form-control chosen'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJName;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJCode;?></th>
          <td><?php echo html::input('code', $project->code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <?php if($project->model == 'waterfall'):?>
        <tr>
          <th><?php echo $lang->program->PRJCategory;?></th>
          <td><?php echo html::select('product', $lang->program->PRJCategoryList, $project->product, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->program->PRJPM;?></th>
          <td><?php echo html::select('PM', $pmUsers, $project->PM, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJBudget;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('budget', $project->budget, "class='form-control'");?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $lang->program->unitList, $project->budgetUnit, "class='form-control'");?>
            </div>
          </td>
          <td class='muted'></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php echo html::input('end', $project->end, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
              <?php if(empty($parentProgram) or $parentProgram->end == '0000-00-00'):?>
              <span class='input-group-addon hidden' id='longTimeBox'>
                <div class="checkbox-primary">
                  <input type="checkbox" name="longTime" value="1" id="longTime">
                  <label for="longTime"><?php echo $lang->program->longTime;?></label>
                </div>
              </span>
              <?php endif;?>
            </div>
          </td>
          <td class='muted'></td>
        </tr>
        <?php if($project->model == 'scrum'):?>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', '', "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->teamname;?></th>
          <td><?php echo html::input('team', $project->team, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJDesc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=project&link=desc');?>
            <?php echo html::textarea('desc', $project->desc, "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->program->PRJAuthList, $project->auth, '', 'block');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('acl', $lang->program->PRJAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr id='whitelistBox' <?php if($project->acl != 'custom') echo "class='hidden'";?>>
          <th><?php echo $lang->project->whitelist;?></th>
          <td colspan='3'><?php echo html::checkbox('whitelist', $groups, $project->whitelist, '', '', 'inline');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $project->model);
              echo html::submitButton();
              echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
