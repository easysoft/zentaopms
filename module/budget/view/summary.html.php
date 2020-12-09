<?php
/**
 * The summary view of budget module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: summary.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.summary-active{color: #0c64eb;font-weight: 700;}
.summary td{font-weight: 700;}
.table-title{background: #efefef}
.text-center th{vertical-align: middle;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="pull-left">
    <?php common::printLink('budget', 'summary', '', "<i class='icon-common-report icon-bar-chart muted'></i> " . $lang->budget->summary, '', "class='btn btn-link summary-active'");?>
    <?php common::printLink('budget', 'browse', '', "<i class='icon icon-list-alt muted'></i> " . $lang->budget->list, '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('budget', 'batchcreate', '', "<i class='icon icon-plus'></i>" . $lang->budget->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('budget', 'create', '', "<i class='icon icon-plus'></i>" . $lang->budget->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class="center-block">
    <?php if(!empty($subjects)):?>
    <table class='table table-bordered'>
      <tr class='text-center table-title'>
        <th rowspan="<?php echo $subSubject ? 2 : 1;?>"><?php echo $lang->budget->subject . '/' . $lang->budget->stage;?></th>
        <?php foreach($subjects as $id => $subject):?>
          <th colspan="<?php echo count($subject);?>"><?php echo zget($modules, $id);?></th>
        <?php endforeach;?>
        <th rowspan="<?php echo $subSubject ? 2 : 1;?>"><?php echo $lang->budget->summary;?></th>
      </tr>
      <tr class='text-center table-title'>
        <?php foreach($subjects as $subject):?>
          <?php if($id == $subject[0] && !$subSubject) continue;?>
          <?php foreach($subject as $subjectID):?>
            <th><?php echo zget($modules, $subjectID);?></th>
          <?php endforeach;?>
        <?php endforeach;?>
      </tr>

      <?php foreach($summary['stages'] as $stageID => $subject):?>
      <tr>
        <td><?php echo zget($plans, $stageID, $stageID);?></td>
        <?php foreach($subject as $cost):?>
          <td><?php echo $cost;?></td>
        <?php endforeach;?>
        <td><?php echo array_sum($subject);?></td>
      </tr>
      <?php endforeach;?>

      <tr class='summary'>
        <td><?php echo $lang->budget->summary;?></td>
        <?php foreach($summary['subjects'] as $cost):?>
          <td><?php echo $cost;?></td>
        <?php endforeach;?>
        </td>
        <td><?php echo array_sum($summary['subjects']);?></td>
      </tr>
    </table>
    <div class='alert alert-info mg-0'><?php echo $lang->budget->total . '：' . array_sum($summary['subjects']) . $lang->budget->{$program->budgetUnit};?></div>
    <?php else:?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
