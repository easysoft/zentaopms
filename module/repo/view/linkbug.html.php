<?php
/**
 * The link bug view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     mr
 * @version     $Id: linkbug.html.php$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<style>
#queryBox {padding-top: 25px;}
#queryBox.show {min-height: 66px;}
.search-form #bug-search .form-actions {width: 115px !important;}
</style>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2><?php echo $lang->repo->linkBug;?></h2>
  </div>
  <div id='queryBox' data-module='bug' class='show no-margin'></div>
  <div id='unlinkBugList'>
    <form class='main-table table-bug' data-ride='table' method='post' id='unlinkedBugsForm' target='hiddenwin' action='<?php echo $this->createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy=$orderBy")?>'>
      <div class='table-header hl-primary text-primary strong'>
        <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedBugs;?>
      </div>
      <table class='table tablesorter'>
        <thead>
          <tr class='text-center'>
            <th class='c-id text-left'>
              <?php if($allBugs):?>
              <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php echo $lang->idAB;?>
            </th>
            <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
            <th class='text-left'><?php echo $lang->bug->title;?></th>
            <th class='c-user'><?php echo $lang->openedByAB;?></th>
            <th class='c-user'><?php echo $lang->bug->assignedToAB;?></th>
            <th class='c-status'><?php echo $lang->bug->status;?></th>
          </tr>
        </thead>
        <tbody class='text-center'>
          <?php $unlinkedCount = 0;?>
          <?php foreach($allBugs as $bug):?>
          <tr>
            <td class='c-id text-left'>
              <?php echo html::checkbox('bugs', array($bug->id => sprintf('%03d', $bug->id)));?>
            </td>
            <td><span class='label-pri label-pri-<?php echo $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
            <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
            <td><?php echo zget($users, $bug->openedBy);?></td>
            <td><?php echo zget($users, $bug->assignedTo);?></td>
            <td>
              <span class='status-bug status-<?php echo $bug->status?>'>
                <?php echo $this->processStatus('bug', $bug);?>
              </span>
            </td>
          </tr>
          <?php $unlinkedCount++;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($unlinkedCount):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php echo html::submitButton($lang->productplan->linkBug, '', 'btn');?>
        </div>
        <?php endif;?>
        <div class='table-statistic'></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <iframe frameborder="0" name="hiddenwin" id="hiddenwin" scrolling="no" class="debugwin hidden"></iframe>
  </div>
</div>
<script>
$(function()
{
    $('#unlinkBugList .tablesorter').sortTable();
    setForm();
});
</script>
