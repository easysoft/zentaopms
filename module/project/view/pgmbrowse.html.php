<?php
/**
 * The html template file of PGMBrowse method of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('orderBy', $orderBy);?>
<?php if($projectType == 'bygrid'):?>
<style> #mainMenu{padding-left: 10px; padding-right: 10px;} </style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->project->PGMFeatureBar as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('pgmbrowse', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('showClosed', array('1' => $lang->project->PGMShowClosed), '', $this->cookie->showClosed ? 'checked=checked' : '');?>
  </div>
  <div class='pull-right'>
    <?php if(isset($lang->pageActions)) echo $lang->pageActions;?>
    <?php if(isset($this->config->maxVersion)):?>
    <?php common::printLink('project', 'createGuide', "projectID=0&from=PGM", '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php elseif($this->config->systemMode == 'new'):?>
    <?php common::printLink('project', 'prjcreate', "mode=scrum&projectID=0&from=PGM", '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary"');?>
    <?php else:?>
    <?php common::printLink('project', 'create', '', '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary"');?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($projects)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->noPGM;?></span>
      <?php common::printLink('project', 'pgmcreate', '', "<i class='icon icon-plus'></i> " . $lang->project->PGMCreate, '', "class='btn btn-info'");?>
    </p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <?php 
    if($projectType == 'bygrid')
    {
        include 'pgmbrowsebygrid.html.php';
    }
    else
    {
        include 'pgmbrowsebylist.html.php';
    }
    ?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
