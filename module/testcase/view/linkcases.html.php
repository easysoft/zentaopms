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
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('case', 'view', 'caseID=' . $case->id), $case->title, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->testcase->linkCases;?> <?php echo html::icon($lang->icons['link']);?></small>
    </div>
    <div id='querybox' class='show'></div>
  </div>
  <form method='post' class='form-condensed' target='hiddenwin' id='linkCasesForm'>
    <table class='table table-condensed table-hover table-striped tablesorter table-selectable' id='caseList'>
      <?php if($cases2Link):?>
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
      <?php foreach($cases2Link as $case2Link):?>
      <tr class='text-center'>
        <td class='cell-id'>
          <input type='checkbox' name='cases[]' value='<?php echo $case2Link->id;?>' />
          <?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case2Link->id"), sprintf('%03d', $case2Link->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case2Link->pri, $case2Link->pri)?>'><?php echo zget($lang->testcase->priList, $case2Link->pri, $case2Link->pri)?></span></td>
        <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case2Link->id"), $case2Link->title, '_blank');?></td>
        <td><?php echo $lang->testcase->typeList[$case2Link->type];?></td>
        <td><?php echo $users[$case2Link->openedBy];?></td>
        <td class='case-<?php echo $case2Link->status?>'><?php echo $lang->testcase->statusList[$case2Link->status];?></td>
      </tr>
      <?php $caseCount ++;?>
      <?php endforeach;?>
      </tbody>
      <tfoot>
      <tr>
        <td colspan='6'>
          <div class='table-actions clearfix'>
          <?php if($caseCount) echo html::selectButton() . html::submitButton();?>
          </div>
        </td>
      </tr>
      <tr>
        <td class='hidden'><?php echo html::input('case', $case->id);?></td>
      </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
</div>
<script type='text/javascript'>
$(function(){ajaxGetSearchForm();});
</script>
<?php include '../../common/view/footer.html.php';?>
