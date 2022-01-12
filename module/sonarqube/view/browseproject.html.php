<?php
/**
 * The browse view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     sonarqube
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('vars', "keyword=%s&orderBy=id_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID=1")?>
<?php js::set('sonarqubeID', $sonarqubeID)?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::a($this->createLink('sonarqube', 'browse'), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
  <div id="sidebarHeader">
    <div class="title"><?php echo $this->lang->sonarqube->common . ':' . $sonarqube->name; ?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <div>
      <form id='sonarqubeProjectForm' method='post'>
      <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->sonarqube->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
      <a id="projectSearch" class="btn btn-primary"><?php echo $lang->sonarqube->search?></a>
      </form>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('sonarqube', 'createProject')) common::printLink('sonarqube', 'createProject', "sonarqubeID=$sonarqubeID", "<i class='icon icon-plus'></i> " . $lang->sonarqube->createProject, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($sonarqubeProjectList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('sonarqube', 'createProject')):?>
    <?php echo html::a($this->createLink('sonarqube', 'createProject', "sonarqubeID=$sonarqubeID"), "<i class='icon icon-plus'></i> " . $lang->sonarqube->createProject, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='sonarqubeProjectList' class='table has-sort-head table-fixed'>
      <?php $vars = "sonarqubeID={$sonarqubeID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->sonarqube->projectName);?></th>
          <th class='text-left'><?php common::printOrderLink('lastAnalysisDate', $orderBy, $vars, $lang->sonarqube->projectlastAnalysis);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sonarqubeProjectList as $id => $sonarqubeProject): ?>
        <tr class='text'>
          <td class='text-c-name' title='<?php echo $sonarqubeProject->name;?>'><?php echo $sonarqubeProject->name;?></td>
          <td class='text' title='<?php echo substr($sonarqubeProject->lastAnalysisDate, 0, 10);?>'><?php echo substr($sonarqubeProject->lastAnalysisDate, 0, 10);?></td>
          <td class='c-actions text-left'>
            <?php
            if(common::hasPriv('sonarqube', 'deleteProject')) echo html::a($this->createLink('sonarqube', 'deleteProject', "sonarqubeID=$sonarqubeID&project=$sonarqubeProject->key"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->sonarqube->deleteProject}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($sonarqubeProjectList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
