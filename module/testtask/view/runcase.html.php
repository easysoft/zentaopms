<?php
/**
 * The runrun view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: runcase.html.php 4723 2013-05-03 05:19:29Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['usecase']);?> <strong><?php echo $run->case->id;?></strong></span>
    <strong><?php echo $run->case->title;?></strong>
    <small class='text-muted'> <?php echo $lang->testtask->runCase;?> <?php echo html::icon($lang->icons['run']);?></small>
  </div>
</div>
<div class='main'>
  <form class='form-condensed' method='post'>
    <table class='table table-bordered table-form'>
      <thead>
        <tr>
          <td colspan='5'><strong><?php echo $lang->testcase->precondition;?></strong> <?php echo $run->case->precondition;?></td>
        </tr>
        <tr>
          <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
          <th class='w-p40'><?php  echo $lang->testcase->stepDesc;?></th>
          <th class='w-p20'><?php  echo $lang->testcase->stepExpect;?></th>
          <th class='w-100px'><?php echo $lang->testcase->result;?></th>
          <th><?php echo $lang->testcase->real;?></th>
        </tr>
      </thead>
      <?php foreach($run->case->steps as $key => $step):?>
      <?php $defaultResult = $step->expect ? 'pass' : 'n/a';?>
      <tr>
        <th><?php echo $key + 1;?></th>
        <td><?php echo nl2br($step->desc);?></td>
        <td><?php echo nl2br($step->expect);?></td>
        <td class='text-center'><?php echo html::select("steps[$step->id]", $lang->testcase->resultList, $defaultResult, "class='form-control'");?></td>
        <td><?php echo html::textarea("reals[$step->id]", '', "rows=2 class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
      <tr class='text-center'>
        <td colspan='5'>
          <?php
          if($preCase)  echo html::a(inlink('runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}"), $lang->testtask->pre, '', "id='pre' class='btn'");
          if(empty($run->case->steps))
          {
              echo html::submitButton($lang->testtask->pass, "onclick=$('#result').val('pass')", 'btn-success');
              echo html::submitButton($lang->testtask->fail, "onclick=$('#result').val('fail')", 'btn-danger');
          }
          else
          {
              echo html::submitButton();
          }
          if($nextCase)  echo html::a(inlink('runCase', "runID={$nextCase['runID']}&caseID={$nextCase['caseID']}&version={$nextCase['version']}"), $lang->testtask->next, '', "id='next' class='btn'");
          if(!$run->case->steps) echo html::hidden('result', '');
          echo html::hidden('case',    $run->case->id);
          echo html::hidden('version', $run->case->currentVersion);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>
<div class='main' id='resultsContainer'>
</div>
<script>
$(function()
{
    $('#resultsContainer').load("<?php echo $this->createLink('testtask', 'results', "runID=0&caseID=$caseID&version=$version");?> #casesResults", function()
    {
        $('.result-item').click(function()
        {
            var $this = $(this);
            $this.toggleClass('show-detail');
            var show = $this.hasClass('show-detail');
            $this.next('.result-detail').toggleClass('hide', !show);
            $this.find('.collapse-handle').toggleClass('icon-chevron-down', !show).toggleClass('icon-chevron-up', show);;
        });

        $('#casesResults table caption .result-tip').html($('#resultTip').html());
    });
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
