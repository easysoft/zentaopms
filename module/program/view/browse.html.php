<?php
/**
 * The html template file of PGMBrowse method of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('orderBy', $orderBy);?>
<?php js::set('edit', $lang->edit);?>
<?php js::set('selectAll', $lang->selectAll);?>
<?php js::set('hasProject', $hasProject);?>
<?php js::set('checkedProjects', $lang->program->checkedProjects);?>
<?php js::set('cilentLang', $this->app->getClientLang());?>
<?php if($programType == 'bygrid'):?>
<style>#mainMenu{padding-left: 10px; padding-right: 10px;}</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->program->featureBar['browse'] as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('browse', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php if(common::hasPriv('project', 'batchEdit') and $programType != 'bygrid' and $hasProject === true) echo html::checkbox('editProject', array('1' => $lang->project->edit), '', $this->cookie->editProject ? 'checked=checked' : '');?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->user->search;?></a>
  </div>
  <div class='pull-right'>
    <?php if(common::hasPriv('project', 'create')) common::printLink('project', 'createGuide', "programID=0&from=PGM", '<i class="icon icon-plus"></i> ' . $lang->project->create, '', 'class="btn btn-secondary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php if(isset($lang->pageActions)) echo $lang->pageActions;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($programs)):?>
  <div class="cell<?php if($status == 'bySearch') echo ' show';?>" id="queryBox" data-module='program'></div>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noProgram;?></span>
      <?php if($status == 'all') common::printLink('program', 'create', '', "<i class='icon icon-plus'></i> " . $lang->program->create, '', "class='btn btn-info'");?>
    </p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <div class="cell<?php if($status == 'bySearch') echo ' show';?>" id="queryBox" data-module='program'></div>
    <?php
    if($programType == 'bygrid')
    {
        include 'browsebygrid.html.php';
    }
    else
    {
        include 'browsebylist.html.php';
    }
    ?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
