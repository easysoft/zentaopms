<?php
/**
 * The batchCreate view of budget module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: batchCreate.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->budget->batchCreate;?></h2>
    </div>
    <form class="main-form form-ajax" method='post' enctype='multipart/form-data'>
      <table class="table table-form">
        <thead>
          <tr>
            <th class='w-40px'><?php echo $lang->idAB;?></th>
            <th class='w-200px required'><?php echo $lang->budget->stage;?> </th>
            <th class='w-200px required'><?php echo $lang->budget->subject;?> </th>
            <th class='required'><?php echo $lang->budget->name;?> </th>
            <th class='w-150px'><?php echo $lang->budget->amount;?> </th>
            <th class='w-180px'><?php echo $lang->budget->desc;?> </th>
          </tr>
        </thead>
        <tbody>
        <?php for($i = 1; $i <= 10; $i++):?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo html::select("stage[$i]", $plans, '', 'class="form-control chosen"');?></td>
            <td><?php echo html::select("subject[$i]", $subjects, '', 'class="form-control chosen"');?></td>
            <td><?php echo html::input("name[$i]", '', 'class="form-control"');?></td>
            <td>
              <div class='input-group'>
              <?php echo html::input("amount[$i]", '', 'class="form-control"');?>
              <span class='input-group-addon'><?php echo $lang->budget->{$program->budgetUnit};?></span>
              </div>
            </td>
            <td><?php echo html::textarea("desc[$i]", '', 'class="form-control" rows=1');?></td>
          </tr>
          <?php endfor;?>
          <tr>
            <td colspan='5' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
