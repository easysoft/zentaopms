<?php
/**
 * The browsebycard view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     project
 * @version     $Id: browsebycard.html.php 4769 2021-07-23 11:29:21Z $
 * @link        https://www.zentao.net
 */
?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolBar pull-left">
    <?php if($this->config->systemMode == 'new'):?>
      <div class="input-control space w-150px" id='programBox'><?php echo html::select('programID', $programs, $programID, "onchange=changeProgram(this.value) class='form-control chosen' data-placeholder='{$lang->project->selectProgram}'");?></div>
    <?php endif;?>
    <?php foreach($lang->project->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('involved', array('1' => $lang->project->mine), '', $this->cookie->involved ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group">
      <?php echo html::a('#',"<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon active' title='{$lang->project->bycard}' id='switchButton' data-type='bycard'");?>
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->project->bylist}' id='switchButton' data-type='bylist'");?>
    </div>
    <?php if(isset($this->config->maxVersion)):?>
    <?php common::printLink('project', 'createGuide', "programID=$programID", '<i class="icon icon-plus"></i>' . $lang->project->create, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php elseif($this->config->systemMode == 'new'):?>
    <?php common::printLink('project', 'create', 'mode=scrum', '<i class="icon icon-plus"></i>' . $lang->project->create, '', 'class="btn btn-primary"');?>
    <?php else:?>
    <?php common::printLink('execution', 'create', '', '<i class="icon icon-plus"></i>' . $lang->execution->create, '', 'class="btn btn-primary"');?>
    <?php endif;?>
  </div>
</div>
<div class='row cell' id='cards'>
  <?php foreach ($projectStats as $projectID => $project):?>
  <div class='col' data-id='<?php echo $projectID?>'>
    <div class='panel'>
      <div class='panel-heading'>
        <?php if(isset($config->maxVersion) and $project->model === 'waterfall'):?>
        <span class='project-type-label label label-warning label-outline'><i class='icon icon-waterfall'></i></span>
        <?php elseif(isset($config->maxVersion)):?>
        <span class='project-type-label label label-info label-outline'><i class='icon icon-sprint'></i></span>
        <?php endif;?>
        <strong class='project-name' title='<?php echo $project->name;?>'><?php echo html::a(helper::createLink('project', 'index', "projectID=$projectID"), $project->name);?></strong>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <div class='col-xs-12' id='cardsFooter'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</div>
<style>
#mainMenu {padding-left: 10px; padding-right: 10px; margin-bottom: -10px;}
#cards {margin: 0 0;}
#cards > .col {width: 25%;}
#cards .panel {margin: 10px 0;  border: 1px solid #DCDCDC; border-radius: 2px; box-shadow: none; height: 146px; cursor: pointer;}
#cards .pager .btn {padding-top: 4px;}
#cards .panel:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,100,.25);}
#cards .panel-heading {padding: 12px 24px 10px 16px;}
#cards .panel-body {padding: 0 16px 16px;}
#cards .panel-actions {padding: 7px 0;}
#cards .panel-actions .dropdown-menu > li > a {padding-left: 5px; text-align: left;}
#cards .panel-actions .dropdown-menu > li > a > i {opacity: .5; display: inline-block; margin-right: 4px; width: 18px; text-align: center;}
#cards .panel-actions .dropdown-menu > li > a:hover > i {opacity: 1;}
#cards .project-type-label {padding: 1px 2px;}
#cards .icon {font-size: 8px;}
#cards .project-name {font-size: 14px; display: inline-block; max-width: 80%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;}
#cards .project-infos {font-size: 12px;}
#cards .project-infos > span {display: inline-block; line-height: 12px;}
#cards .program-infos > span > .icon {font-size: 12px; display: inline-block; position: relative; top: -1px}
#cards .program-infos > span + span {margin-left: 15px;}
#cards .program-detail {position: absolute; bottom: 16px; left: 16px; right: 16px; font-size: 12px;}
#cards .program-detail > p {margin-bottom: 8px;}
#cards .program-detail .progress {height: 4px;}
#cards .program-detail .progress-text-left .progress-text {width: 50px; left: -50px;}
#cards .pager {margin: 0; float: right;}
#cards .pager .btn {border: none}
#cards .program-stages-container {margin: 0 -16px -16px -16px; padding: 0 4px; height: 46px; overflow-x: auto; position: relative;}
#cards .program-stages:after {content: ' '; width: 30px; display: block; right: -16px; top: 16px; bottom: -6px; z-index: 1; background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%); position: absolute;}
#cards .program-stages-row {position: relative; height: 30px; z-index: 0;}
#cards .program-stage-item {white-space: nowrap; position: absolute; top: 0; min-width: 48px; padding-top: 13px; color: #838A9D;}
#cards .program-stage-item > div {white-space: nowrap; overflow: visible; text-align: center; text-overflow: ellipsis;}
#cards .program-stage-item:before {content: ' '; display: block; width: 8px; height: 8px; border-radius: 50%; background: #D1D1D1; position: absolute; left: 50%; margin-left: -4px; top: 0; z-index: 1;}
#cards .program-stage-item + .program-stage-item:after {content: ' '; display: block; left: -50%; right: 50%; height: 2px; background-color: #D1D1D1; top: 3px; position: absolute; z-index: 0;}
#cards .program-stage-item.is-going {color: #333;}
#cards .program-stage-item.is-going::before {background-color: #0C64EB;}
.chosen-drop.chosen-auto-max-width.in {width: 150px !important;}
</style>
