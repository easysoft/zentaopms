<?php
/**
 * The start view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      MengYi Liu <liumengyi@easycorp.ltd>
 * @package     productplan
 * @version     $Id: start.html.php 4728 2021-12-28 09:57:34Z $
 * @link        https://www.zentao.net
 */
?>
<style>
.table-form {margin-top: 90px;}
.form-actions {height:160px;}
</style>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->productplan->setDate;?></strong></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->productplan->begin;?></th>
          <td colspan='2'><?php echo html::input('begin', $plan->begin != '2030-01-01' ? formatTime($plan->begin) : '', "class='form-control form-date'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->productplan->end;?></th>
          <td colspan='2'><?php echo html::input('end', $plan->end != '2030-01-01' ? formatTime($plan->end) : '', 'class="form-control form-date"');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton($lang->productplan->start);?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
