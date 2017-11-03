<?php
/**
 * The batch activate view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <strong><small class='text-muted'></small> <?php echo $lang->bug->common . $lang->colon . $lang->bug->batchActivate;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-fixed with-border'>
    <thead>
      <tr>
        <th class='w-20px'><?php echo $lang->idAB;?></th>
        <th class='w-50px'><?php echo $lang->bug->title;?></th>
        <th class='w-70px'><?php echo $lang->bug->assignedTo;?></th>
        <th class='w-70px'><?php echo $lang->bug->openedBuild;?></th>
        <th class='w-150px'><?php echo $lang->bug->legendComment;?></th>
      </tr>
    </thead>
    <tbody class='text-left'>
      <?php foreach($bugs as $bug):?>
      <tr>
        <td class='text-center'><?php echo $bug->id . html::hidden("bugIDList[$bug->id]", $bug->id);?></td>
        <td><?php echo $bug->title . html::hidden("statusList[$bug->id]", $bug->status);?></td>
        <td style='overflow:visible'><?php echo html::select("assignedToList[$bug->id]", $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
        <td style='overflow:visible'><?php echo html::select("openedBuildList[$bug->id]", $builds, $bug->openedBuild, 'size=4 multiple=multiple class="form-control chosen"');?></td>
        <td><?php echo html::input("commentList[$bug->id]", '', "class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><td colspan='5' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
