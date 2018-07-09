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
<div id="mainContent" class="main-content">
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <?php echo html::a($this->createLink('case', 'view', "caseID={$case->id}"), $case->title, '_blank');?>
      <small class='text-muted'> <?php echo $lang->arrow . $lang->testcase->linkCases;?></small>
    </h2>
  </div>
  <div id='queryBox' class='show divider'></div>
  <?php if($cases2Link):?>
  <form class='main-table' method='post' target='hiddenwin' id='linkCasesForm' data-ride='table'>
    <table class='table' id='caseList'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->testcase->product;?></th>
          <th><?php echo $lang->testcase->title;?></th>
          <th class='w-type'><?php echo $lang->testcase->type;?></th>
          <th class='w-user'><?php echo $lang->openedByAB;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
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
          <td><?php echo html::a($this->createLink('product', 'browse', "productID={$case2Link->product}&branch={$case2Link->branch}"), $products[$case2Link->product], '_blank');?></td>
          <td class='text-left' title='<?php echo $case2Link->title;?>'><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case2Link->id"), $case2Link->title, '_blank');?></td>
          <td><?php echo $lang->testcase->typeList[$case2Link->type];?></td>
          <td><?php echo $users[$case2Link->openedBy];?></td>
          <td class='case-<?php echo $case2Link->status?>'><?php echo $lang->testcase->statusList[$case2Link->status];?></td>
        </tr>
        <?php $caseCount ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php if($caseCount) echo html::submitButton('', '', 'btn btn-default');?></div>
      <?php echo html::hidden('case', $case->id);?>
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
            var caseTitle = "#" + caseID + ' ' + $(this).find('td').eq(3).attr('title');
            var checkbox  = "<li><div class='checkbox-primary'><input type='checkbox' checked='checked' name='linkCase[]' " + "value=" + caseID + " /><label>" + caseTitle + "</label></div></li>";

            output += checkbox;
        });
        $.closeModal();
        parent.$('#linkCaseBox').html(output);
        return false;
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
