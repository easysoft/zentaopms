<?php
/**
 * The batchEdit view file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->productplan->batchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr class='text-center'>
          <th class='w-60px'><?php echo $lang->productplan->id?></th>
          <th><?php echo $lang->productplan->title?></th>
          <th><?php echo $lang->productplan->desc?></th>
          <th class='w-150px'><?php echo $lang->productplan->begin?></th>
          <th class='w-150px'><?php echo $lang->productplan->end?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($plans as $plan):?>
        <tr>
          <td class='text-center'><?php echo $plan->id . html::hidden("id[$plan->id]", $plan->id);?></td>
          <td title='<?php echo $plan->title?>'><?php echo html::input("title[$plan->id]", $plan->title, "class='form-control' autocomplete='off'")?></td>
          <td><?php echo html::textarea("desc[$plan->id]", $plan->desc, "class='form-control' rows='1'")?></td>
          <td><?php echo html::input("begin[$plan->id]", $plan->begin, "class='form-control form-date'")?></td>
          <td><?php echo html::input("end[$plan->id]", $plan->end, "class='form-control form-date'")?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='5' class="text-center form-actions">
            <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
            <?php echo html::backButton('', '', "btn btn-wide");?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
