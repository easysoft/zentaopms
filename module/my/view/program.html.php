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
<div id="mainMenu" class="clearfix hide">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->myProgram;?></span></span>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noProgram;?></span>
      <?php if(common::hasPriv('program', 'createGuide')):?>
      <?php echo html::a($this->createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $lang->my->createProgram, '', "class='btn btn-info' data-toggle=modal");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table has-sort-head table-fixed" id='programList'>
    <thead>
      <tr class='text-center'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-160px text-left'><?php echo $lang->program->code;?></th>
        <th class='c-name text-left'><?php echo $lang->program->name;?></th>
        <th class='c-date'><?php echo $lang->program->begin;?></th>
        <th class='c-date'><?php echo $lang->program->end;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($programs as $program):?>
      <tr class='text-center'>
        <td><?php echo $program->id;?></td>
        <td class='text-left'><?php echo $program->code;?></td>
        <td class='text-left'><?php echo html::a($this->createLink('program', 'index', "programID=$program->id"), $program->name);?></td>
        <td><?php echo $program->begin;?></td>
        <td><?php echo $program->end;?></td>
        <td class="c-status">
          <?php if(isset($program->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->project->delayed;?>'> <?php echo $lang->project->delayed;?></span>
          <?php else:?>
          <?php $statusName = $this->processStatus('program', $program);?>
          <span class="status-project status-<?php echo $program->status?>" title='<?php echo $statusName;?>'> <?php echo $statusName;?></span>
          <?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
