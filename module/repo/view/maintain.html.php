<?php
/**
 * The browse view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('repo', 'maintain'), "<span class='text'>{$lang->repo->maintain}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('repo', 'import')) echo html::a(helper::createLink('repo', 'import'), "<i class='icon icon-import'></i> " . $this->lang->repo->importAction, '', "class='btn btn-link'");?>
    <?php if(common::hasPriv('repo', 'create')) echo html::a(helper::createLink('repo', 'create'), "<i class='icon icon-plus'></i> " . $this->lang->repo->createAction, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='repoList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->repo->id); ?></th>
          <th class='c-type'><?php common::printOrderLink('SCM', $orderBy, $vars, $lang->repo->type); ?></th>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->repo->name); ?></th>
          <th class='c-product text-left'><?php common::printOrderLink('product', $orderBy, $vars, $lang->repo->product); ?></th>
          <th class='text-left'><?php echo $lang->repo->path; ?></th>
          <th class='c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($repoList as $repo):?>
        <tr>
          <td class='text'><?php echo $repo->id; ?></td>
          <td class='text'><?php echo zget($lang->repo->scmList, $repo->SCM); ?></td>
          <td class='text' title='<?php echo $repo->name; ?>'><?php echo html::a($this->createLink('repo', 'browse', "repoID={$repo->id}&branchID=&objectID=$objectID"), $repo->name);?></td>
          <?php
          $productNames = '';
          $productList  = explode(',', str_replace(' ', '', $repo->product));
          if(isset($productList) and $productList[0])
          {
              foreach($productList as $productID)
              {
                  if(!isset($products[$productID])) continue;
                  $productNames .= ' ' . zget($products, $productID, $productID);
              }
          }
          ?>
          <td class='text' title='<?php echo $productNames;?>'><?php echo $productNames;?></td>
          <td class='text' title='<?php echo $repo->codePath;?>'><?php echo $repo->codePath;?></td>
          <td class='text-left c-actions'>
            <?php
            common::printIcon('repo', 'edit', "repoID=$repo->id&objectID=$objectID", '', 'list', 'edit');

            $jobID       = 0;
            $execClass   = 'disabled';
            $reportClass = 'disabled';
            if(isset($sonarRepoList[$repo->id]))
            {
                $execClass = '';
                $jobID = $sonarRepoList[$repo->id]->id;
                if(in_array($jobID, $successJobs)) $reportClass = '';
            }
            common::printIcon('sonarqube', 'execJob', "jobID=$jobID", '', 'list', 'sonarqube', 'hiddenwin', $execClass);
            common::printIcon('sonarqube', 'reportView', "jobID=$jobID", '', 'list', 'audit', '', "iframe $reportClass", true);
            common::printIcon('repo', 'delete', "repoID=$repo->id&objectID=$objectID", '', 'list', 'trash', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($repoList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php'; ?>
