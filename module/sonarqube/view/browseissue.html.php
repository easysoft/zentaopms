<?php
/**
 * The browse view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@cnezsoft.com>
 * @package     sonarqube
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::a($this->createLink('sonarqube', 'browseProject', "sonarqubeID={$sonarqubeID}"), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
  <div id="sidebarHeader">
    <div class="title"><?php echo $projectKey; ?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <div>
      <form id='sonarqubeIssueForm' method='post'>
      <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->sonarqube->placeholder->searchIssue}' style='display: inline-block;margin:0 10px'");?>
      <a id="issueSearch" class="btn btn-primary"><?php echo $lang->sonarqube->search?></a>
      </form>
    </div>
  </div>
</div>
<?php if(empty($sonarqubeIssueList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='sonarqubeIssueList' class='table has-sort-head table-fixed'>
      <?php $vars = "sonarqubeID={$sonarqubeID}&projectKey={$projectKey}&search={$search}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-message text-left'><?php common::printOrderLink('message', $orderBy, $vars, $lang->sonarqube->issue->message);?></th>
          <th class='c-severity'><?php common::printOrderLink('severity', $orderBy, $vars, $lang->sonarqube->issue->severity);?></th>
          <th><?php common::printOrderLink('type', $orderBy, $vars, $lang->sonarqube->issue->type);?></th>
          <th><?php common::printOrderLink('status', $orderBy, $vars, $lang->sonarqube->issue->status);?></th>
          <th class='c-file text-left'><?php common::printOrderLink('file', $orderBy, $vars, $lang->sonarqube->issue->file);?></th>
          <th><?php common::printOrderLink('line', $orderBy, $vars, $lang->sonarqube->issue->line);?></th>
          <th class='c-effort'><?php common::printOrderLink('effort', $orderBy, $vars, $lang->sonarqube->issue->effort);?></th>
          <th class='c-date'><?php common::printOrderLink('creationDate', $orderBy, $vars, $lang->sonarqube->issue->creationDate);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sonarqubeIssueList as $id => $sonarqubeIssue): ?>
        <tr class='text'>
          <td class='text' title="<?php echo $sonarqubeIssue->message;?>"><?php echo html::a(trim($sonarqube->url, '/') . "/project/issues?id=$projectKey&issues={$sonarqubeIssue->key}&open={$sonarqubeIssue->key}", $sonarqubeIssue->message, '_target');?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->severity;?>'><?php echo $sonarqubeIssue->severity;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->type;?>'><?php echo $sonarqubeIssue->type;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->status;?>'><?php echo $sonarqubeIssue->status;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->file;?>'><?php echo $sonarqubeIssue->file;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->line;?>'><?php echo $sonarqubeIssue->line;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->effort;?>'><?php echo $sonarqubeIssue->effort;?></td>
          <td class='text' title='<?php echo $sonarqubeIssue->creationDate;?>'><?php echo $sonarqubeIssue->creationDate;?></td>
          <td class='c-actions text-left'>
            <?php
            $issueKey = $sonarqubeID . ':' . $sonarqubeIssue->key;
            $attr     = isset($bugs[$issueKey]) ? 'disabled' : '';
            common::printLink('bug', 'create', "productID=$productID&branch=&extra=from=sonarqube,sonarqubeID=$sonarqubeID,issueKey={$sonarqubeIssue->key}", "<i class='icon-testcase-createBug icon-bug'></i> ", '', "title='{$lang->sonarqube->createBug}' class='btn' $attr data-app='qa'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($sonarqubeIssueList):?>
    <div class='table-footer'>
      <?php
      $this->app->rawParams['search'] = $search;
      $pager->show('right', 'pagerjs');
      ?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
