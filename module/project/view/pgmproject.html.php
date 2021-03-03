<?php
/**
 * The pgmproject view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: pgmproject.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
js::set('projectID', $projectID);
js::set('browseType', $browseType);
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->project->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('PGMProject', "projectID=$projectID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('PRJMine', array('1' => $lang->project->mine), '', $this->cookie->PRJMine ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(isset($this->config->maxVersion)):?>
    <?php common::printLink('project', 'createGuide', "projectID=$projectID&from=PGM", '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php elseif($this->config->systemMode == 'new'):?>
    <?php common::printLink('project', 'prjcreate', "mode=scrum&projectID=$projectID&from=PGM", '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary"');?>
    <?php else:?>
    <?php common::printLink('project', 'create', '', '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-primary"');?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="main-col">
    <?php if(empty($projectStats)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->project->noPRJ;?></span>
        <?php common::printLink('project', 'createGuide', "projectID=$projectID", '<i class="icon icon-plus"></i>' . $lang->project->PRJCreate, '', 'class="btn btn-info btn-wide " data-toggle="modal" data-target="#guideDialog"');?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' id='projectsForm' method='post' data-ride="table">
      <?php
        $vars    = "projectID=$projectID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
        $setting = $this->datatable->getSetting('project');
      ?>
      <table class='table has-sort-head'>
      <?php $canBatchEdit = common::hasPriv('project', 'PRJBatchEdit');?>
        <thead>
          <tr>
            <?php
              foreach($setting as $value)
              {
                if($value->id == 'PRJStatus' and $browseType !== 'all') $value->show = false;
                if($value->show) $this->datatable->printHead($value, $orderBy, $vars, $canBatchEdit);
              }
            ?>
          </tr>
        </thead>
        <tbody class="sortable" id='projectTableList'>
          <?php foreach($projectStats as $project):?>
          <tr data-id="<?php echo $project->id;?>">
            <?php $project->from = 'pgmproject';?>
            <?php foreach($setting as $value) $this->project->printCell($value, $project, $users, $projectID);?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('project', 'PRJBatchEdit', "from=pgmproject&projectID=$projectID");
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        ?>
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$('#PRJMine1').click(function()
{
    var PRJMine = $(this).is(':checked') ? 1 : 0;
    $.cookie('PRJMine', PRJMine, {expires:config.cookieLife, path:config.webRoot});
    window.location.reload();
});
</script>
<?php include '../../common/view/footer.html.php';?>
