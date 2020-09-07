<?php
/**
 * The viewCommit view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: viewcommit.html.php 4903 2020-09-02 09:32:59Z tianshujie@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#mainContent .pull-right{margin-bottom: 10px}
.table{border: 1px solid #ddd;}
</style>
<div id="mainContent">
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $design->id;?></span>
      <span title='<?php echo $design->name?>'><?php echo $design->name?></span>
      <small><?php echo $lang->arrow . $lang->design->submission;?></small>
    </h2>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('design', 'linkCommit', "designID=$design->id", "<i class='icon icon-plus'></i>" . $lang->design->linkCommit, '_blank', "class='btn btn-primary'");?>
  </div>
  <?php if(empty($design->commit)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->design->noCommit;?></span></p>
  </div>
  <?php else:?>
  <table class='table'>
    <thead>
      <tr>
        <th class="w-100px"><?php echo $lang->design->submission;?></th>
        <th class='w-120px'><?php echo $lang->design->commitBy;?></th>
        <th class='w-100px'><?php echo $lang->design->commitDate;?></th>
        <th><?php echo $lang->design->comment;?></th>
        <th class="text-center w-50px"> <?php echo $lang->design->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($design->commit as $commit):?>
      <tr>
        <td title="<?php echo $commit->id;?>"><?php echo html::a(helper::createLink('design', 'revision', "repoID=$commit->id"), "#$commit->id", '_blank');?></td>
        <td><?php echo zget($users, $commit->committer, $commit->committer);?></td>
        <td><?php echo substr($commit->time, 0, 11);?></td>
        <td title="<?php echo $commit->comment;?>"><?php echo $commit->comment;?></td>
        <td class="c-actions text-center">
        <?php common::printIcon('design', 'unlinkCommit', "designID=$design->id&commitID=$commit->id", $design, 'list', 'unlink', 'hiddenwin', 'iframe showinonlybody', true);?>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer table-statistic'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
