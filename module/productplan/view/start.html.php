<?php
/**
 * The start view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      MengYi Liu <liumengyi@easycorp.ltd>
 * @package     productplan
 * @version     $Id: start.html.php 4728 2021-12-28 09:57:34Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->productplan->setDate;?></strong></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->productplan->begin;?></th>
          <td colspan='2'><?php echo html::input('begin', $plan->begin != $config->productplan->future ? formatTime($plan->begin) : '', "class='form-control form-date'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->productplan->end;?></th>
          <td colspan='2'><?php echo html::input('end', $plan->end != $config->productplan->future ? formatTime($plan->end) : '', 'class="form-control form-date"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->productplan->desc;?></th>
          <td colspan='4'><?php echo html::textarea('desc', $plan->desc, 'class="form-control"');?></td>
        </tr>
        <tr>
          <td colspan='5' class='text-center form-actions'>
            <?php echo html::submitButton($lang->productplan->start);?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
