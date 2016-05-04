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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['usecase']);?> <strong><?php echo $run->case->id;?></strong></span>
    <strong><?php echo $run->case->title;?></strong>
    <small class='text-muted'> <?php echo $lang->testtask->runCase;?> <?php echo html::icon($lang->icons['run']);?></small>
  </div>
</div>
<div class='main'>
  <form class='form-condensed' method='post' enctype='multipart/form-data'>
    <table class='table table-bordered table-form' style='word-break:break-all'>
      <thead>
        <tr>
          <td colspan='5' style='word-break: break-all;'><strong><?php echo $lang->testcase->precondition;?></strong> <?php echo $run->case->precondition;?></td>
        </tr>
        <tr>
          <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
          <th class='w-p30'><?php  echo $lang->testcase->stepDesc;?></th>
          <th class='w-p30'><?php  echo $lang->testcase->stepExpect;?></th>
          <th class='w-100px'><?php echo $lang->testcase->result;?></th>
          <th>
            <?php echo $lang->testcase->real;?>
            <?php if(empty($run->case->steps)):?>
            <button type='button' class='btn btn-danger btn-file' data-toggle='modal' data-target='#fileModal'><?php echo $lang->testtask->files;?></button>
            <?php endif;?>
          </th>
        </tr>
      </thead>
      <?php foreach($run->case->steps as $key => $step):?>
      <?php $defaultResult = $step->expect ? 'pass' : 'n/a';?>
      <tr>
        <th><?php echo $key + 1;?></th>
        <td><?php echo nl2br($step->desc);?></td>
        <td><?php echo nl2br($step->expect);?></td>
        <td class='text-center'><?php echo html::select("steps[$step->id]", $lang->testcase->resultList, $defaultResult, "class='form-control'");?></td>
        <td>
          <table class='fix-border fix-position'>
            <tr>
              <td><?php echo html::textarea("reals[$step->id]", '', "rows=1 class='form-control autosize'");?></td>
              <td><button type='button' title='<?php echo $lang->testtask->files?>' class='btn' data-toggle='modal' data-target='#fileModal<?php echo $step->id?>'><i class='icon icon-paper-clip'></i></button></td>
            </tr>
          </table>
        </td>
      </tr>
      <?php endforeach;?>
      <tr class='text-center'>
        <td colspan='5'>
          <?php
          if($preCase)  echo html::a(inlink('runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}"), $lang->testtask->pre, '', "id='pre' class='btn'");
          if(empty($run->case->steps))
          {
              echo html::submitButton($lang->testtask->pass, "onclick=$('#result').val('pass')", 'btn btn-success');
              echo html::submitButton($lang->testtask->fail, "onclick=$('#result').val('fail')", 'btn btn-danger');
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
          <ul id='filesName' class='nav'></ul>
        </td>
      </tr>
    </table>
    <?php if(empty($run->case->steps)):?>
    <div class="modal fade" id="fileModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><?php echo $lang->testtask->files;?></h4>
          </div>
          <div class="modal-body">
            <table class='table table-form'>
              <tr>
                <td><?php echo $this->fetch('file', 'buildform');?></td>
              </tr>
              <tr>
                <td class='text-center'><button type="button" class="btn btn-default" onclick='loadFilesName()' data-dismiss="modal" aria-hidden="true"><?php echo $lang->save;?></button></td>
              <tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php else:?>
    <?php foreach($run->case->steps as $key => $step):?>
    <div class="modal fade" id="fileModal<?php echo $step->id;?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><?php echo $lang->testtask->files;?></h4>
          </div>
          <div class="modal-body">
            <table class='table table-form'>
              <tr>
                <td><?php echo $this->fetch('file', 'buildform', array('fileCount' => 1, 'percent' => 0.9, 'filesName' => "files{$step->id}", 'labelsName' => "labels{$step->id}"));?></td>
              </tr>
              <tr>
                <td class='text-center'><button type="button" class="btn btn-default" onclick='loadFilesName()' data-dismiss="modal" aria-hidden="true"><?php echo $lang->save;?></button></td>
              <tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
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
