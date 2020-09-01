<?php
/**
 * The viewCommit view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: commit.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-table">
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $design->id;?></span>
      <span title='<?php echo $design->name?>'><?php echo $design->name?></span>
      <small><?php echo $lang->arrow . $lang->design->submission;?></small>
    </h2>
  </div>
  <?php if(empty($design->commit)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->design->noCommit;?></span></p>
  </div>
  <?php else:?>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('design', 'linkCommit', "designID=$design->id", "<i class='icon icon-plus'></i>" . $lang->design->linkCommit, '_blank', "class='btn btn-primary'");?>
  </div>
  <table class='table table-data'>
    <thead>
      <tr>
        <th class="text-left"><?php echo $lang->design->submission;?></th>
        <th class="text-left"><?php echo $lang->design->commitBy;?></th>
        <th class="text-left"><?php echo $lang->design->commitDate;?></th>
        <th class="text-center w-50px"> <?php echo $lang->design->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($design->commit as $commit):?>
      <tr>
        <td><?php echo html::a(helper::createLink('design', 'revision', "repoID=$commit"), "#$commit", '_blank');?></td>
        <td><?php echo $design->commitBy;?></td>
        <td><?php echo substr($design->commitDate, 0, 11);?></td>
        <td class="c-actions">
        <?php common::printIcon('design', 'unlinkCommit', "designID=$design->id&commitID=$commit", $design, 'list', 'unlink', 'hiddenwin', 'iframe showinonlybody', true);?>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
