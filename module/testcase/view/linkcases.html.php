<?php
/**
 * The linkcases view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     testcase
 * @version     $Id: linkcases.html.php 4411 2016-03-09 11:02:04Z Chen Fei $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id="mainContent" class="main-content">
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <?php echo html::a($this->createLink('case', 'view', "caseID={$case->id}"), $case->title, '_blank');?>
      <small class='text-muted'> <?php echo $lang->arrow . $lang->testcase->linkCases;?></small>
    </h2>
  </div>
  <div id='queryBox' data-module='testcase' class='show divider'></div>
  <?php if($cases2Link):?>
  <form class='main-table' method='post' target='hiddenwin' id='linkCasesForm' data-ride='table'>
    <table class='table tablesorter' id='caseList'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->testcase->title;?></th>
          <th class='c-type'><?php echo $lang->testcase->type;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $caseCount = 0;?>
        <?php foreach($cases2Link as $case2Link):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='cases[]' value='<?php echo $case2Link->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $case2Link->id);?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case2Link->pri, $case2Link->pri)?>'><?php echo zget($lang->testcase->priList, $case2Link->pri, $case2Link->pri)?></span></td>
          <td class='c-name' title='<?php echo $case2Link->title;?>'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case2Link->id"), $case2Link->title, '_blank');?></td>
          <td><?php echo $lang->testcase->typeList[$case2Link->type];?></td>
          <td><?php echo zget($users, $case2Link->openedBy);?></td>
          <td class='case-<?php echo $case2Link->status?>'><?php echo $this->processStatus('testcase', $case2Link);?></td>
        </tr>
        <?php $caseCount ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php if($caseCount) echo html::submitButton('', '', 'btn btn-default');?></div>
      <?php echo html::hidden('case', $case->id);?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<script>
$(function()
{
    <?php if($cases2Link):?>
    $('#linkCasesForm').table();
    setTimeout(function(){$('#linkCasesForm .table-footer').removeClass('fixed-footer');}, 100);
    <?php endif;?>

    $('#submit').click(function(){
        var output = '';
        $('#linkCasesForm').find('tr.checked').each(function(){
            var caseID    = $(this).find('td.c-id').find('div.checkbox-primary input').attr('value');
            var caseTitle = "#" + caseID + ' ' + $(this).find('td').eq(2).attr('title');
            var checkbox  = "<li title='" + caseTitle + "'><div class='checkbox-primary'><input type='checkbox' checked='checked' name='linkCase[]' " + "value=" + caseID + " /><label class='linkCaseTitle'>" + caseTitle + "</label></div></li>";

            output += checkbox;
        });
        $.closeModal();
        parent.$('#linkCaseBox').html(output);
        parent.$('#linkCaseBox').closest('tr').removeClass('hide');
        return false;
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
