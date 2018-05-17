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
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchActivate;?></h2>
  </div>
  <form class='main-form' method='post' target='hiddenwin'>
    <table class='table table-form table-fixed'>
      <thead>
        <tr>
          <th class='w-40px'><?php echo $lang->idAB;?></th>
          <th class='w-160px'><?php echo $lang->bug->title;?></th>
          <th class='w-120px'><?php echo $lang->bug->assignedTo;?></th>
          <th class='w-200px'><?php echo $lang->bug->openedBuild;?></th>
          <th><?php echo $lang->bug->legendComment;?></th>
        </tr>
      </thead>
      <tbody class='text-left'>
        <?php foreach($bugs as $bug):?>
        <tr>
          <td class='text-center'><?php echo $bug->id . html::hidden("bugIDList[$bug->id]", $bug->id);?></td>
          <td title='<?php echo $bug->title;?>'><?php echo $bug->title . html::hidden("statusList[$bug->id]", $bug->status);?></td>
          <td style='overflow:visible'><?php echo html::select("assignedToList[$bug->id]", $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
          <td style='overflow:visible'><?php echo html::select("openedBuildList[$bug->id]", $builds, $bug->openedBuild, 'size=4 multiple=multiple class="form-control chosen"');?></td>
          <td><?php echo html::input("commentList[$bug->id]", '', "class='form-control'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr><td colspan='5' class='text-center'><?php echo html::submitButton('', '', 'btn btn-primary btn-wide');?></td></tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
