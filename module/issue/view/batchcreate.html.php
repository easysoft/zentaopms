<?php
/**
 * The batch create view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id = "mainContent" class="main-content fade in">
  <div class="main-header">
    <h2><?php echo $lang->issue->batchCreate;?></h2>
  </div>
  <form id="batchCreateForm" target="hiddenwin" method="post">
    <table class="table table-form">
      <thead>
        <tr class="text-center">
          <th class="w-50px"><?php echo $lang->issue->id;?></th>
          <th class="required"><?php echo $lang->issue->title;?></th>
          <th class="w-140px required"><?php echo $lang->issue->type;?></th>
          <th class="w-120px required"><?php echo $lang->issue->severity;?></th>
          <th class="w-150px"><?php echo $lang->issue->desc;?></th>
          <th class="w-100px"><?php echo $lang->issue->pri;?></th>
          <th class="w-120px"><?php echo $lang->issue->deadline;?></th>
          <th class="w-120px"><?php echo $lang->issue->assignedTo;?></th>
        </tr> 
      </thead>
      <tbody>
        <?php for($i = 1;$i <= 10;$i++):?>
        <tr data-key="<?php echo $i;?>">
          <td><?php echo $i;?></td>
          <td><?php echo html::input("dataList[$i][title]", '', 'id="dataList' . $i . 'title" class="form-control" autocomplete="off"')?></td>
          <td><?php echo html::select("dataList[$i][type]", $lang->issue->typeList, '', 'class="form-control chosen" id="dataList' . $i . 'type"')?></td>
          <td><?php echo html::select("dataList[$i][severity]", $lang->issue->severityList, '', 'class="form-control chosen" id="dataList' . $i . 'severity"')?></td>
          <td><?php echo html::textarea("dataList[$i][desc]", '', 'class="form-control" id="dataList' . $i . 'desc" rows="1"');?></td>
          <td><?php echo html::select("dataList[$i][pri]", $lang->issue->priList, '', 'class="form-control chosen" id="dataList' . $i . 'pri"')?></td>
          <td><?php echo html::input("dataList[$i][deadline]", '', 'class="form-control form-date" id="dataList' . $i . 'deadline"');?></td>
          <td><?php echo html::select("dataList[$i][assignedTo]", $users, '', 'class="form-control chosen" id="dataList' . $i . 'assignedTo"')?></td>
       </tr>
        <?php endfor;?>
      </tbody>
    </table>
    <div class="form-actions text-center">
      <?php echo html::submitButton() . html::backButton();?>
    </div>
  </form> 
</div>
<?php include '../../common/view/footer.html.php';?>
