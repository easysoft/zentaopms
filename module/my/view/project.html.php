<?php
/**
 * The project view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->my->projectMenu as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('project', "type=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($projects)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noPRJ;?></span>
      <?php if(common::hasPriv('program', 'createGuide')):?>
      <?php echo html::a($this->createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $lang->my->createProgram, '', "class='btn btn-info' data-toggle=modal");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-checkable='false'>
    <table class='table table-fixed' id='programList'>
      <thead>
        <tr>
          <th class='c-id w-50px'>
            <?php echo $lang->idAB;?>
          </th>
          <th><?php echo $lang->program->PRJName;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJCode;?></th>
          <th class='w-80px'><?php  echo $lang->program->PRJStatus;?></th>
          <th class='w-100px'><?php echo $lang->program->begin;?></th>
          <th class='w-100px'><?php echo $lang->program->end;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJBudget;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJPM;?></th>
          <th class='text-center w-180px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='programTableList'>
        <?php foreach($projects as $project):?>
        <tr>
          <td class='c-id'><?php printf('%03d', $project->id);?></td>
          <td class='c-name text-left' title='<?php echo $project->name?>'>
            <?php echo html::a($this->createLink('program', 'index', "projectID=$project->id", '', '', $project->id), $project->name);?>
          </td>
          <td class='text-left'><?php echo $project->code;?></td>
          <td class='c-status'><span class="status-program status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
          <td class='text-left'><?php echo $project->begin;?></td>
          <td class='text-left'><?php echo $project->end == '0000-00-00' ? '' : $project->end;?></td>
          <td class='text-left'><?php echo $project->budget . ' ' . zget($lang->program->unitList, $project->budgetUnit);?></td>
          <td><?php echo zget($users, $project->PM);?></td>
          <td class='c-actions'>
            <?php
            if($project->status == 'wait')
            {
                $method = 'PRJStart';
                $icon   = 'start';
            }
            if($project->status == 'doing')
            {
                $method = 'PRJClose';
                $icon   = 'off';
            }
            if($project->status == 'suspended')
            {
                $method = 'PRJStart';
                $icon   = 'start';
            }
            if($project->status == 'closed')
            {
                $method = 'PRJActivate';
                $icon   = 'magic';
            }
            ?>
            <?php common::printIcon('program', $method, "projectID=$project->id", $project, 'list', $icon, '', 'iframe', true);?>
            <div class='btn-group'>
              <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"></button>
              <ul class='dropdown-menu pull-right' role='menu'>
                <?php common::printIcon('program', 'PRJStart',    "projectID=$project->id", $project, 'list', 'start', '', $project->status == 'wait' ||  $project->status == 'suspended' ? 'hidden' : 'iframe', true);?>
                <?php common::printicon('program', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', $project->status == 'closed' ? 'hidden' : 'iframe', true);?>
                <?php common::printicon('program', 'PRJSuspend',  "projectID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
                <?php common::printIcon('program', 'PRJClose',    "projectID=$project->id", $project, 'list', 'off',   '', $project->status == 'doing' ? 'hidden' : 'iframe', true);?>
              </ul>
            </div>
            <?php if(common::hasPriv('program', 'PRJEdit')) echo html::a($this->createLink("program", "prjedit", "projectID=$project->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$lang->edit}'");?>
            <?php common::printIcon('program', 'PRJManageMembers', "projectID=$project->id", $project, 'list', 'persons');?>
            <?php common::printIcon('program', 'PRJGroup',         "projectID=$project->id", $project, 'list', 'lock');?>
            <div class='btn-group'>
              <button type='button' class='btn icon-more-circle dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"></button>
              <ul class='dropdown-menu pull-right' role='menu'>
                <?php common::printIcon('program', 'PRJManageProducts', "projectID=$project->id", $project, 'list', 'icon icon-menu-project');?>
                <?php common::printIcon('program', 'PRJWhitelist',      "projectID=$project->id", $project, 'list', 'group');?>
                <?php if(common::hasPriv('program','PRJDelete')) echo html::a($this->createLink("program", "PRJDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$lang->delete}'");?>
              </ul>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
