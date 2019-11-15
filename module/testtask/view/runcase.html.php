<?php
/**
 * The runrun view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: runcase.html.php 4723 2013-05-03 05:19:29Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/form.html.php';?>
<?php js::set('caseResultSave', $lang->save);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $run->case->id;?></span>
      <span title='<?php echo $run->case->title?>'><?php echo $run->case->title;?></span>
    </h2>
  </div>
  <form method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-bordered' style='word-break:break-all' id='steps'>
      <thead>
        <tr>
          <td colspan='5' style='word-break: break-all;'><strong><?php echo $lang->testcase->precondition;?></strong><br/><?php echo nl2br($run->case->precondition);?></td>
        </tr>
        <tr>
          <th class='w-50px'><?php echo $lang->testcase->stepID;?></th>
          <th class='w-p30'><?php  echo $lang->testcase->stepDesc;?></th>
          <th class='w-p30'><?php  echo $lang->testcase->stepExpect;?></th>
          <th class='w-100px'><?php echo $lang->testcase->result;?></th>
          <th><?php echo $lang->testcase->real;?></th>
        </tr>
      </thead>
      <?php
      if(empty($run->case->steps))
      {
          $step = new stdclass();
          $step->id     = 0;
          $step->parent = 0;
          $step->case   = $run->case->id;
          $step->type   = 'step';
          $step->desc   = '';
          $step->expect = '';
          $run->case->steps[] = $step;
      }
      $stepId = $childId = 0;
      ?>
      <?php foreach($run->case->steps as $key => $step):?>
      <?php
      $stepClass = "step-{$step->type}";
      if($step->type == 'group' or $step->type == 'step')
      {
          $stepId++;
          $childId = 0;
      }
      ?>
      <tr class='step <?php echo $stepClass?>'>
        <th class='step-id'><?php echo $stepId;?></th>
        <td class='text-left' <?php if($step->type == 'group') echo "colspan='4'"?>>
          <div class='input-group'>
          <?php if($step->type == 'item') echo "<span class='step-item-id'>{$stepId}.{$childId}</span>";?>
          <?php echo nl2br($step->desc);?>
          </div>
        </td>
        <?php if($step->type != 'group'):?>
        <td class='text-left'><?php echo nl2br($step->expect);?></td>
        <td class='text-center'><?php echo html::select("steps[$step->id]", $lang->testcase->resultList, 'pass', "class='form-control' onchange='checkStepValue(this.value)'");?></td>
        <td>
          <table class='w-p100'>
            <tr>
              <td class='no-padding bd-0'><?php echo html::textarea("reals[$step->id]", '', "rows=1 class='form-control autosize'");?></td>
              <td class='no-padding bd-0 w-50px text-right'><button type='button' title='<?php echo $lang->testtask->files?>' class='btn' data-toggle='modal' data-target='#fileModal<?php echo $step->id?>'><i class='icon icon-paper-clip'></i></button></td>
            </tr>
          </table>
        </td>
        <?php endif;?>
      </tr>
      <?php $childId ++;?>
      <?php endforeach;?>
      <tr class='text-center'>
        <td colspan='5' class='form-actions'>
          <?php
          if($preCase)  echo html::a(inlink('runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}"), $lang->testtask->pre, '', "id='pre' class='btn btn-wide'");
          if($run->case->status != 'wait') echo html::submitButton();
          if($nextCase)  echo '&nbsp;' . html::a(inlink('runCase', "runID={$nextCase['runID']}&caseID={$nextCase['caseID']}&version={$nextCase['version']}"), $lang->testtask->next, '', "id='next' class='btn btn-wide'");
          echo html::hidden('case',    $run->case->id);
          echo html::hidden('version', $run->case->currentVersion);
          ?>
          <ul id='filesName' class='nav'></ul>
        </td>
      </tr>
    </table>
    <?php foreach($run->case->steps as $key => $step):?>
    <div class="modal fade" id="fileModal<?php echo $step->id;?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
            <h4 class="modal-title"><?php echo $lang->testtask->files;?></h4>
          </div>
          <div class="modal-body">
            <?php echo $this->fetch('file', 'buildform', array('fileCount' => 1, 'percent' => 0.9, 'filesName' => "files{$step->id}", 'labelsName' => "labels{$step->id}"));?>
            <div class="text-center"><button type="button" class="btn btn-wide btn-primary" onclick='loadFilesName()' data-dismiss="modal"><?php echo $lang->save;?></button></div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </form>
  <div class='main' id='resultsContainer'></div>
</div>
<script>
$(function()
{
    $('#resultsContainer').load("<?php echo $this->createLink('testtask', 'results', "runID={$runID}&caseID=$caseID&version=$version");?> #casesResults", function()
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
<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
var sessionString = '<?php echo $sessionString;?>';
</script>
<?php include '../../common/view/footer.lite.html.php';?>
