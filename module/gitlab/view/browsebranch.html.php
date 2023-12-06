<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('vars', "keyword=%s&orderBy=id_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID=1")?>
<?php js::set('gitlabID', $gitlabID)?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $this->createLink('gitlab', 'browseProject', "gitlabID=$gitlabID"), 'self', '','btn btn-secondary');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitlab', 'createBranch')) common::printLink('gitlab', 'createBranch', "gitlabID=$gitlabID&projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->gitlab->createBranch, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabBranchList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('gitlab', 'createBranch')):?>
    <?php echo html::a($this->createLink('gitlab', 'createBranch', "gitlabID=$gitlabID&projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->createBranch, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabBranchList' class='table has-sort-head table-fixed'>
      <?php $vars = "gitlabID={$gitlabID}&projectID={$projectID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-name text'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->branch->name);?></th>
          <th class='text-left'><?php echo $lang->gitlab->branch->lastCommitter;?></th>
          <th class='text-left'><?php common::printOrderLink('lastCommittedDate', $orderBy, $vars, $lang->gitlab->branch->lastCommittedDate);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabBranchList as $id => $gitlabBranch): ?>
        <tr class='text'>
          <td class='text-c-name' title='<?php echo $gitlabBranch->name;?>'><?php echo $gitlabBranch->name;?></td>
          <td class='text'><?php echo $gitlabBranch->lastCommitter;?></td>
          <td class='text'><?php echo $gitlabBranch->lastCommittedDate?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gitlabBranchList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
