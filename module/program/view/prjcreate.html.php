<?php
/**
 * The prjcreate view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: prjcreate.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($tips)):?>
<?php $defaultURL = $this-> createLink('project', 'task', 'projectID=' . $projectID);?>
<?php include '../../common/view/header.lite.html.php';?>
<body>
  <div class='modal-dialog mw-500px' id='tipsModal'>
    <div class='modal-header'>
      <a href='<?php echo $defaultURL;?>' class='close'><i class="icon icon-close"></i></a>
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->project->tips;?></h4>
    </div>
    <div class='modal-body'>
    <?php echo $tips;?>
    </div>
  </div>
</body>
</html>
<?php exit;?>
<?php endif;?>

<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::set('holders', $lang->project->placeholder);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->PRJCreate;?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->program->PRJCopy;?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PRJTemplate;?></th>
          <td><?php echo zget($lang->program->templateList, $template, '');?></td><td></td><td>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PGMParent;?></th>
          <td><?php echo html::select('parent', $programList, $programID, "class='form-control chosen'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJName;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJCode;?></th>
          <td><?php echo html::input('code', $code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <?php if($template == 'waterfall'):?>
        <tr>
          <th><?php echo $lang->program->PRJCategory;?></th>
          <td><?php echo html::select('category', $lang->program->PRJCategoryList, '', "class='form-control'");?></td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->program->PRJPM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJBudget;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('budget', '', "class='form-control'");?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $lang->program->unitList, empty($parentProgram->budgetUnit) ? 'yuan' : $parentProgram->budgetUnit, "class='form-control'");?>
            </div>
          </td>
          <td class='muted'><?php if($parentProgram) printf($lang->program->parentBudget, $parentProgram->budget . zget($lang->program->unitList, $parentProgram->budgetUnit, ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
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
          <td class='muted'><?php if($parentProgram) printf($lang->program->parentBeginEnd, $parentProgram->begin, $parentProgram->end == '0000-00-00' ? '' : $parentProgram->end);?></td>
        </tr>
        <?php if($template == 'scrum'):?>
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
          <td><?php echo html::input('team', $team, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->project->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <th><?php echo $lang->program->PRJDesc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=project&link=desc');?>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->auth;?></th>
          <td colspan='3'><?php echo html::radio('privway', $lang->program->PRJAuthList, $privway, '', 'block');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('acl', $lang->program->PRJAclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr id='whitelistBox' class='hidden'>
          <th><?php echo $lang->project->whitelist;?></th>
          <td colspan='3'><?php echo html::checkbox('whitelist', $groups, $whitelist, '', '', 'inline');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $template);
              echo html::submitButton();
              echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div class='modal fade modal-scroll-inside' id='copyProjectModal'>
  <div class='modal-dialog mw-900px'>
    <div class='modal-header'>
      <button type='button' class='close' data-dismiss='modal'><i class="icon icon-close"></i></button>
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->project->copyTitle;?></h4>
    </div>
    <div class='modal-body'>
      <?php if(count($programs) == 1):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->project->copyNoProject;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php foreach ($programs as $id => $name):?>
      <?php if(empty($id)):?>
      <?php if($copyProgramID != 0):?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='' class='cancel'><?php echo html::icon($lang->icons['cancel']) . ' ' . $lang->project->cancelCopy;?></a></div>
      <?php endif;?>
      <?php else: ?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo ($copyProgramID == $id) ? ' active' : '';?>'><?php echo html::icon($lang->icons['project'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endif; ?>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php js::set('template', $template);?>
<?php js::set('parentProgramID', isset($parentProgram->id) ? $parentProgram->id : 0);?>
<?php include '../../common/view/footer.html.php';?>
