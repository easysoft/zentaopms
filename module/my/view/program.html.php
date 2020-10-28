<?php
/**
 * The program view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: program.html.php 5095 2013-07-11 06:03:40Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->myProgram;?></span></span>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noPRJ;?></span>
      <?php if(common::hasPriv('program', 'createGuide')):?>
      <?php echo html::a($this->createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $lang->my->createProgram, '', "class='btn btn-info' data-toggle=modal");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false'>
      <table class='table table-fixed table-nested' id='programList'>
        <thead>
          <tr>
            <th class='c-id w-80px'>
              <?php echo $lang->idAB;?>
            </th>
            <th class='w-100px'><?php echo $lang->program->PRJCode;?></th>
            <th class='table-nest-title'><?php echo $lang->program->PRJName;?></th>
            <th class='w-80px'><?php  echo $lang->program->PRJStatus;?></th>
            <th class='w-100px'><?php echo $lang->program->begin;?></th>
            <th class='w-100px'><?php echo $lang->program->end;?></th>
            <th class='w-100px'><?php echo $lang->program->PRJBudget;?></th>
            <th class='w-100px'><?php echo $lang->program->PRJPM;?></th>
            <th class='text-center w-240px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='programTableList'>
          <?php foreach($programs as $program):?>
          <tr>
            <td class='c-id'>
              <?php printf('%03d', $program->id);?>
            </td>
            <td class='text-left'><?php echo $program->code;?></td>
            <td class='text-left pgm-title table-nest-title' title='<?php echo $program->name?>'>
              <span class="table-nest-icon"></span>
              <?php echo html::a($this->createLink('program', 'pgmview', "programID=$program->id", '', '', $program->id), $program->name);?>
            </td>
            <td class='c-status'><span class="status-program status-<?php echo $program->status?>"><?php echo zget($lang->project->statusList, $program->status, '');?></span></td>
            <td class='text-center'><?php echo $program->begin;?></td>
            <td class='text-center'><?php echo $program->end == '0000-00-00' ? '' : $program->end;?></td>
            <td class='text-left'><?php echo $program->budget . ' ' . zget($lang->program->unitList, $program->budgetUnit);?></td>
            <td><?php echo zget($users, $program->PM);?></td>
            <td class='text-center c-actions'>
              <?php common::printIcon('program', 'group', "programID=$program->id", $program, 'list', 'group');?>
              <?php common::printIcon('program', 'manageMembers', "programID=$program->id", $program, 'list', 'persons');?>
              <?php common::printIcon('program', 'start', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'activate', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'suspend', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
              <?php common::printIcon('program', 'close', "programID=$program->id", $program, 'list', '', '', 'iframe', true);?>
              <?php if(common::hasPriv('program', 'edit')) echo html::a($this->createLink("program", "edit", "programID=$program->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
              <?php common::printIcon('program', 'create', "template=&programID=$program->id", '', 'list', 'treemap-alt', '', '', '', '', $this->lang->program->PRJChildren);?>
              <?php if(common::hasPriv('program', 'delete')) echo html::a($this->createLink("program", "delete", "programID=$program->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <style>
    .w-240px {width:240px;}
    #programTableList.sortable-sorting > tr {opacity: 0.7}
    #programTableList.sortable-sorting > tr.drag-row {opacity: 1;}
    #programTableList > tr.drop-not-allowed {opacity: 0.1!important}
    </style>
  <?php endif;?>
</div>

<?php include '../../common/view/footer.html.php';?>
