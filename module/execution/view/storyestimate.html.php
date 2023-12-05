<?php
/**
 * The create storyestimate view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun  <liyuchun@cnezsoft.com>
 * @package     execution
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#showAverage {margin: 0;}
.chosen-container .chosen-drop {bottom: auto !important;}
.pull-left {margin: 12px 0px;}
.btn-toolbar #roundBox {width:220px;}
</style>
<?php js::set('executionID', $executionID);?>
<?php js::set('storyID', $storyID);?>
<div id='mainContent' class='main-content'>
  <div class="main-header">
    <h2>
      <span class='label label-id'><?php echo $storyID;?></span>
      <span title='<?php echo $story->title;?>'><?php echo $story->title;?></span>
    </h2>
  </div>
  <?php if(empty($team)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->execution->noTeam;?></span></p>
  </div>
  <?php else:?>
  <div class='btn-toolbar pull-left'>
    <?php if(!empty($rounds)):?>
    <div id='roundBox' class='input-group space'>
      <span class='input-group-addon'><?php echo $lang->execution->selectRound?></span>
      <?php echo html::select('round', $rounds, $round, "class='form-control chosen' onchange='selectRound(this.value)'");?>
    </div>
    <div id='reestimate' class='input-group space w-100px'>
      <?php echo html::a("javascript:showNewEstimate()", $lang->execution->reestimate, '', 'class="btn btn-primary"');?>
    </div>
    <?php endif;?>
  </div>
  <form class='main-form form-ajax' method='post' target='hiddenwin' id="estimateForm">
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->execution->team;?></th>
          <th> <?php echo $lang->story->estimate;?></th>
          <th class='th-new-estimate hide'><?php echo $lang->execution->newEstimate;?></th>
          <th class='empty-th'></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($team as $user):?>
        <tr class='text-center'>
          <td><?php echo zget($users, $user->account);?></td>
          <?php echo html::hidden('account[]', $user->account, "class='form-control'");?>
          <?php if(!empty($estimateInfo->estimate)):?>
          <td class='c-name' title="<?php echo isset($estimateInfo->estimate->{$user->account}) ? $estimateInfo->estimate->{$user->account}->estimate : '';?>" style='text-align: center !important;'><?php echo isset($estimateInfo->estimate->{$user->account}) ? $estimateInfo->estimate->{$user->account}->estimate : '';?></td>
          <td class='new-estimate hide'><?php echo html::input('estimate[]', '', "class='form-control'");?></td>
          <?php else:?>
          <td class='new-estimate'><?php echo html::input('estimate[]', '', "class='form-control'");?></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <tr class='text-center'>
          <td><strong><?php echo $lang->execution->average;?></strong></td>
          <?php if(!empty($estimateInfo->estimate)):?>
          <td><?php echo $estimateInfo->average;?></td>
          <td class='new-estimate hide'><p id='showAverage'></p></td>
          <?php else:?>
          <td><p id='showAverage'></p></td>
          <?php endif;?>
          <?php echo html::hidden('average', '', "class='form-control'");?>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='3' class="text-center form-actions <?php if(!empty($rounds)) echo 'hide';?>"><?php echo html::submitButton();?></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
