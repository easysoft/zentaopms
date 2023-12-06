<?php
/**
 * The batch close view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     productplan
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2><?php echo $lang->productplan->common . $lang->colon . $lang->productplan->batchClose;?></h2>
  </div>
  <form method='post' action="<?php echo inLink('batchChangeStatus', "status=closed&productID=$productID")?>">
    <table class='table table-fixed table-form with-border'>
    <thead>
      <tr class='text-center'>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='text-left'><?php echo $lang->productplan->title;?></th>
        <th class='c-status'><?php echo $lang->productplan->status;?></th>
        <th class='c-reason'><?php echo $lang->productplan->closedReason;?></th>
        <th class='w-p40'><?php echo $lang->productplan->comment;?></th>
      </tr>
    </thead>
      <?php foreach($plans as $planID => $plan):?>
      <tr class='text-center'>
        <td><?php echo $planID . html::hidden("planIDList[$planID]", $planID);?></td>
        <td class='text-left'><?php echo $plan->title;?></td>
        <td class='plan-<?php echo $plan->status;?>'><?php echo $plan->title;?></td>
        <td>
          <table class='w-p100'>
            <tr>
              <td class='pd-0'>
                <?php echo html::select("closedReasons[$planID]", $reasonList, 'done', "class=form-control style='min-width: 80px'");?>
              </td>
            </tr>
          </table>
        </td>
        <td><?php echo html::input("comments[$planID]", '', "class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td colspan='5' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
