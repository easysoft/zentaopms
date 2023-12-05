<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('from', $from);?>
<?php $this->app->loadLang('testcase'); unset($this->lang->testcase->resultList['n/a']); ?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo ($from == 'testtask' ? ($lang->testtask->common . $lang->colon) : ''). $lang->testtask->batchRun;?></h2>
  </div>
  <form class='main-form no-stash' method='post' target='hiddenwin'>
    <table class='table table-fixed table-form table-bordered'>
      <thead>
        <tr>
          <th class='w-id'>   <?php  echo $lang->idAB;?></th>
          <th class='w-100px'><?php echo $lang->testcase->module;?></th>
          <th class='w-200px'><?php echo $lang->testcase->title;?></th>
          <th class='precondition w-90px'><?php echo $lang->testcase->precondition;?></th>
          <th class='w-80px'><?php echo $lang->testcase->result?></th>
          <th><?php echo $lang->testcase->stepDesc . '/' . $lang->testcase->stepExpect?></th>
        </tr>
      </thead>
      <?php foreach($cases as $caseID => $case):?>
      <?php if($case->auto == 'auto' and $confirm == 'yes') continue;?>
      <?php if($case->status == 'wait') continue;?>
      <?php if(!$productID) echo html::hidden("caseIDList[$case->id]", $caseID); ?>
      <tr class='text-center'>
        <td><?php echo $caseID . html::hidden("version[$caseID]", $case->version)?></td>
        <td class='text-left'><?php echo "<span title='" . $moduleOptionMenu[$case->module] . "'>" . $moduleOptionMenu[$case->module] . "</span>"?></td>
        <td class='text-left wordwrap'><?php echo "<span title='{$case->title}'>{$case->title}</span>"?></td>
        <td class='text-left precondition wordwrap'><?php echo "<span title='{$case->precondition}'>{$case->precondition}</span>"?></td>
        <td class='text-left'>
          <?php echo html::radio("results[$caseID]", $this->lang->testcase->resultList, 'pass', "onclick='showAction(this.value,\".action$caseID\")'", 'block')?>
        </td>
        <td>
          <?php if(!empty($steps[$caseID])):?>
          <table class='table table-fixed'>
            <?php $stepId = $childId = 0;?>
            <?php foreach($steps[$caseID] as $stepID => $step):?>
            <?php
            if($step->type == 'group' or $step->type == 'step')
            {
                $stepId ++;
                $childId = 0;
            }
            $ID = $step->type == 'item' ? "{$stepId}.{$childId}" : $stepId;
            $stepClass = $step->type == 'item' ? 'step-item' : 'step-group';
            ?>
            <tr>
              <td class='text-left w-p30 wordwrap' <?php if($step->type == 'group') echo "colspan='2'"?>><?php echo "<span title='$step->desc' class='$stepClass'>" . $ID . "、" . $step->desc . '</span>'?></td>
              <?php if($step->type != 'group'):?>
              <td class='text-left w-p30 wordwrap'><?php echo "<span title='$step->expect'>" . $lang->testcase->stepExpect . "：" . $step->expect . '</span>'?></td>
              <td class='w-90px hidden action<?php echo $caseID?>'><?php echo html::select("steps[$caseID][$stepID]", $lang->testcase->resultList, 'pass', "class='form-control'")?></td>
              <td class='hidden action<?php echo $caseID?>'><?php echo html::input("reals[$caseID][$stepID]", '', "class='form-control'");?></td>
              <?php endif;?>
            </tr>
            <?php $childId ++;?>
            <?php endforeach?>
          </table>
          <?php else:?>
          <span class='hidden action<?php echo $caseID?>'><?php echo html::input("reals[$caseID][]", '', "class='form-control'");?></span>
          <?php endif;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr><td colspan='6' class='text-center'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<script>
function showAction(value, obj)
{
    if(value == 'pass')
    {
        $(obj).addClass('hidden');
        $(obj).find('select[id^=steps]').val(value);
        if($(obj).parent().prop('tagName') == 'TR')
        {
            $(obj).closest('tbody').children('tr').each(function(){
                var $td = $(this).children('td:first');
                if($td.attr('colspan') != undefined) $td.attr('colspan', 2);
            });
        }
    }
    else
    {
        $(obj).removeClass('hidden');
        $(obj).find('select[id^=steps]').eq(-1).val(value);
        if($(obj).parent().prop('tagName') == 'TR')
        {
            $(obj).closest('tbody').children('tr').each(function(){
                var $td = $(this).children('td:first');
                if($td.attr('colspan') != undefined) $td.attr('colspan', 4);
            });
        }
    }
}
$(function()
{
    /* Readjust precondition width by cases precondition. */
    preconditionWidth = $('th.precondition').width();
    $precondition     = $('tbody td.precondition');
    length = $precondition.length;
    for(i = 0; i < length; i++)
    {
        width = $precondition.eq(i).find('span:first').width();
        if(width > preconditionWidth)
        {
            preconditionWidth = width;
            if(preconditionWidth > 200) preconditionWidth = 200;
            $('th.precondition').width(preconditionWidth);
        }
    }
    $('tbody td.precondition').addClass('wordwrap');
})
</script>
<?php include '../../common/view/footer.html.php';?>
