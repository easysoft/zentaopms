<?php
/**
 * The link bug view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     release
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='queryBox' data-module='bug' class='show'></div>
<?php
$releaseBugs = $type == 'bug' ? $release->bugs : $release->leftBugs;
$formID      = $type == 'leftBug' ? 'unlinkedLeftBugsForm' : 'unlinkedBugsForm';
?>
<div id='<?php echo $type == 'bug' ? 'unlinkBugList' : 'unlinkLeftBugList';?>'>
  <form class='main-table' method='post' target='hiddenwin' id='<?php echo $formID?>' action='<?php echo $this->createLink('release', 'linkBug', "releaseID=$release->id&browseType=$browseType&param=$param&type=$type")?>' data-ride='table'>
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->productplan->unlinkedBugs;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr class='text-center'>
          <th class='c-id text-left'>
            <?php if($allBugs):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri'><?php echo $lang->priAB;?></th>
          <th class='text-left'><?php echo $lang->bug->title;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-user'><?php echo $lang->bug->resolvedBy;?></th>
          <th class='w-80px'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allBugs as $bug):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('bugs', array($bug->id => sprintf('%03d', $bug->id)), ($type == 'leftBug' or $bug->status == 'resolved' or $bug->status == 'closed') ? $bug->id : '');?>
          </td>
          <td><span class='label-pri label-pri-<?php echo $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <td>
            <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
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
        <?php echo html::submitButton($lang->release->linkBug, '', 'btn btn-secondary');?>
      </div>
      <?php endif;?>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('view', "releaseID=$release->id&type=$type"), $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>
<script>
$(function()
{
    $('#<?php echo $formID;?> .tablesorter').sortTable();
    setForm()
});
</script>
