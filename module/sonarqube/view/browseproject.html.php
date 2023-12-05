<?php
/**
 * The browse view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <liugang@cnezsoft.com>
 * @package     sonarqube
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::a($this->createLink('instance', 'view', "id={$sonarqube->instanceID}&type={$sonarqube->type}"), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
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
    <?php common::printLink('instance', 'manage', "sonarqubeID=$sonarqubeID", "<i class='icon icon-plus'></i> " . $lang->sonarqube->createProject, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($sonarqubeProjectList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and commonModel::hasPriv('space', 'browse')):?>
    <?php echo html::a($this->createLink('instance', 'manage', "sonarqubeID=$sonarqubeID"), "<i class='icon icon-plus'></i> " . $lang->sonarqube->createProject, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='sonarqubeProjectList' class='table has-sort-head table-fixed'>
      <?php $vars = "sonarqubeID={$sonarqubeID}&orderBy=%s&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-key text-left'><?php common::printOrderLink('key', $orderBy, $vars, $lang->sonarqube->projectKey);?></th>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->sonarqube->projectName);?></th>
          <th class='text-left'><?php common::printOrderLink('lastAnalysisDate', $orderBy, $vars, $lang->sonarqube->projectlastAnalysis);?></th>
          <th class='c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sonarqubeProjectList as $id => $sonarqubeProject): ?>
        <tr class='text'>
          <td class='text' title='<?php echo $sonarqubeProject->key;?>'><?php echo $sonarqubeProject->key;?></td>
          <td class='text-c-name' title='<?php echo $sonarqubeProject->name;?>'><?php echo $sonarqubeProject->name;?></td>
          <td class='text' title='<?php echo substr($sonarqubeProject->lastAnalysisDate, 0, 10);?>'><?php echo substr($sonarqubeProject->lastAnalysisDate, 0, 10);?></td>
          <td class='c-actions text-left'>
            <?php
            /* Fix error when request type is PATH_INFO and the project key contains '-'.*/
            $projectKey = str_replace('-', '*', $sonarqubeProject->key);
            common::printLink('sonarqube', 'deleteProject', "sonarqubeID=$sonarqubeID&project=$projectKey", "<i class='icon icon-trash'></i> ", 'hiddenwin', "title='{$lang->sonarqube->deleteProject}' class='btn btn-primary'");
            if(isset($projectJobPairs[$sonarqubeProject->key]))
            {
                $jobID = $projectJobPairs[$sonarqubeProject->key];
                common::printIcon('sonarqube', 'execJob', "jobID=$jobID", '', 'list', 'sonarqube', 'hiddenwin');
                if(in_array($jobID, $successJobs)) common::printIcon('sonarqube', 'reportView', "jobID=$jobID", '', 'list', 'audit', '', 'iframe', true);
            }
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
