<?php
/**
 * The linkcases view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     testcase
 * @version     $Id: linkcases.html.php 4411 2016-03-09 11:02:04Z Chen Fei $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('case', 'view', 'caseID=' . $case->id), $case->title, '_blank');?></strong>
    <small class='text-muted'> <?php echo $lang->testcase->linkCases;?> <?php echo html::icon($lang->icons['link']);?></small>
  </div>
  <div id='querybox' class='show'></div>
</div>
<form method='post' class='form-condensed' target='hiddenwin' id='linkCasesForm'>
  <table class='table table-condensed table-hover table-striped tablesorter' id='caseList'>
    <?php if($allCases):?>
    <thead>
      <tr>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-pri'><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-type'><?php echo $lang->testcase->type;?></th>
        <th class='w-user'><?php echo $lang->openedByAB;?></th>
        <th class='w-status'><?php echo $lang->statusAB;?></th>
      </tr>
    </thead>
    <tbody>
    <?php $caseCount = 0;?>
    <?php foreach($allCases as $caseDetail):?>
    <?php if(in_array($caseDetail->id, explode(',', $case->linkCase))) continue;?>
    <?php if($caseDetail->id == $case->id) continue;?>
    <tr class='text-center'>
      <td class='text-left'>
        <input type='checkbox' name='cases[]' value='<?php echo $caseDetail->id;?>' />
        <?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$caseDetail->id"), sprintf('%03d', $caseDetail->id));?>
      </td>
      <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $caseDetail->pri, $caseDetail->pri)?>'><?php echo zget($lang->testcase->priList, $caseDetail->pri, $caseDetail->pri)?></span></td>
      <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$caseDetail->id"), $caseDetail->title, '_blank');?></td>
      <td><?php echo $lang->testcase->typeList[$caseDetail->type];?></td>
      <td><?php echo $users[$caseDetail->openedBy];?></td>
      <td class='case-<?php echo $caseDetail->status?>'><?php echo $lang->testcase->statusList[$caseDetail->status];?></td>
    </tr>
    <?php $caseCount ++;?>
    <?php endforeach;?>
    </tbody>
    <tfoot> 
    <tr>
      <td colspan='7'>
        <div class='table-actions clearfix'>
        <?php if($caseCount) echo html::selectButton() . html::submitButton();?>
        </div>
      </td>
    </tr>
    </tfoot>
    <?php endif;?>
  </table>
</form>
<script type='text/javascript'>
$(function(){ajaxGetSearchForm();});
</script>
<?php include '../../common/view/footer.html.php';?>
