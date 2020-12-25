<?php
/**
 * The pgmproject view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: pgmproject.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
js::set('programID', $programID);
js::set('browseType', $browseType);
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('PGMProject', "programID=$programID&browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('PRJMine', array('1' => $lang->program->mine), '', $this->cookie->PRJMine ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('program', 'createGuide', "programID=$programID&from=PGM", '<i class="icon icon-plus"></i>' . $lang->program->PRJCreate, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="main-col">
    <form class='main-table' id='projectsForm' method='post' data-ride="table">
      <?php
        include '../../common/view/datatable.html.php';
        $vars    = "programID=$programID&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
        $setting = $this->datatable->getSetting('program');
        $widths  = $this->datatable->setFixedFieldWidth($setting);
      ?>
      <table class='table has-sort-head datatable' data-fixed-left-width='<?php echo $widths['leftWidth'];?>' data-fixed-right-width='<?php echo $widths['rightWidth'];?>'>
        <thead>
          <tr>
            <?php
              foreach($setting as $value)
              {
                if($value->show)
                {
                  $this->datatable->printHead($value, $orderBy, $vars);
                }
              }
            ?>
          </tr>
        </thead>
        <tbody class="sortable" id='projectTableList'>
          <?php foreach($projectStats as $project):?>
          <tr data-id="<?php echo $project->id;?>">
            <?php foreach($setting as $value) $this->program->printCell($value, $project, $users, $programID);?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($projectStats):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
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
