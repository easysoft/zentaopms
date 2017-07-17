<?php
/**
 * The resutls view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: results.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class="outer">
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['usecase']);?> <strong><?php echo $case->id;?></strong></span>
      <strong><?php echo $case->title;?></strong>
      <small class='text-muted'><?php echo $lang->testtask->results . ' ' . html::icon($lang->icons['result']);?></small>
    </div>
  </div>

  <div class='main pdb-20' style='word-break:break-all'>
    <fieldset>
      <legend><?php echo $lang->testcase->precondition;?></legend>
      <?php echo $case->precondition;?>
    </fieldset>
    <div id='casesResults'>
      <table class='table table-condensed table-hover' style='border: 1px solid #ddd; word-break:break-all'>
        <?php $count = count($results);?>
        <caption class='text-left'>
          <strong><?php echo $lang->testcase->result?> &nbsp;<span> <?php printf($lang->testtask->showResult, $count)?></span> <span class='result-tip'></span></strong>
        </caption>
        <?php $failCount = 0; $trCount=1?>
        <?php foreach($results as $result):?>
        <?php
        $class = ($result->caseResult == 'pass' ? 'success' : ($result->caseResult == 'fail' ? 'danger' : ($result->caseResult == 'blocked' ? 'warning' : '')));
        if($class != 'success') $failCount++;
        $fileCount = '(' . count($result->files) . ')';
        ?>
        <tr class='result-item' id='result-<?php echo $class?>' style='cursor: pointer'>
          <td class='w-120px'> &nbsp; #<?php echo $result->id?></td>
          <td class='w-180px'><?php echo $result->date;?></td>
          <td><?php echo $users[$result->lastRunner] . ' ' . $lang->testtask->runCase;?></td>
          <td class='w-150px'><?php echo zget($builds, $result->build, '');?></td>
          <td class='w-50px text-right'><strong class='text-<?php echo $class;?>'><?php echo $lang->testcase->resultList[$result->caseResult]?></strong></td>
          <td class='w-60px'><?php if(!empty($result->files)) echo html::a("#caseResult{$result->id}", $lang->files . $fileCount, '', "data-toggle='modal' data-type='iframe'")?></td>
          <td class='w-50px text-center'><i class='collapse-handle icon-chevron-down text-muted'></i></td>
        </tr>
        <?php $params = isset($testtask) ? ",testtask=$testtask->id,projectID=$testtask->project,buildID=$testtask->build" : '';?>
        <tr class='result-detail hide' id='tr-detail_<?php echo $trCount++; ?>'>
          <td colspan='7' class='pd-0'>
            <form action='<?php echo $this->createLink('bug', 'create', "product=$case->product&branch=$case->branch&extras=caseID=$case->id,version=$case->version,resultID=$result->id,runID=$runID" . $params)?>' target='_blank' method='post'>
            <table class='table table-condensed borderless mg-0 resultSteps'>
              <thead>
                <tr>
                  <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
                  <th class='w-p30 text-left'><?php echo $lang->testcase->stepDesc;?></th>
                  <th class='w-p25 text-left'><?php echo $lang->testcase->stepExpect;?></th>
                  <th class='w-p5 text-left'><?php echo $lang->testcase->stepVersion;?></th>
                  <th class='text-center'><?php echo $lang->testcase->result;?></th>
                  <th class='w-p20 text-left'><?php echo $lang->testcase->real;?></th>
                  <th class='w-80px'></th>
                </tr>
              </thead>
              <?php 
              $stepId = $childId = 0;
              foreach($result->stepResults as $key => $stepResult):
              ?>
              <?php
              if(empty($stepResult['type']))   $stepResult['type']   = 'step';
              if(empty($stepResult['parent'])) $stepResult['parent'] = 0;
              if($stepResult['type'] == 'group' or $stepResult['type'] == 'step')
              {
                  $stepId++;
                  $childId = 0;
              }
              $stepClass = $stepResult['type'] == 'item' ? 'step-item' : 'step-group';
              $modalID   = $result->id . '-' . $key;
              $fileCount = '(' . count($stepResult['files']) . ')';
              ?>
              <tr class='step <?php echo $stepClass?>'>
                <td class='step-id'>
                  <?php if($result->caseResult == 'fail'):?>
                  <?php $inputName = $stepResult['type'] != 'group' ? 'stepIDList[]' : '';?>
                  <input type='checkbox' name='<?php echo $inputName;?>'  value='<?php echo $key;?>'/>
                  <?php endif;?>
                  <?php echo $stepId;?>
                </td>
                <td class='text-left' <?php if($stepResult['type'] == 'group') echo "colspan='6'"?>>
                  <div class='input-group'>
                  <?php if($stepResult['type'] == 'item') echo "<span class='step-item-id'>{$stepId}.{$childId}</span>";?>
                  <?php if(isset($stepResult['desc'])) echo nl2br($stepResult['desc']);?>
                  </div>
                </td>
                <?php if($stepResult['type'] != 'group'):?>
                <td class='text-left'><?php if(isset($stepResult['expect'])) echo nl2br($stepResult['expect']);?></td>
                <td><?php if(isset($result->version)) echo nl2br($result->version);?></td>
                <?php if(!empty($stepResult['result'])):?>
                <td class='<?php echo $stepResult['result'];?> text-center'><?php echo $lang->testcase->resultList[$stepResult['result']];?></td>
                <td><?php echo $stepResult['real'];?></td>
                <td class='text-center'><?php if(!empty($stepResult['files'])) echo html::a("#stepResult{$modalID}", $lang->files . $fileCount, '', "data-toggle='modal' data-type='iframe'")?></td>
                <?php else:?>
                <td></td>
                <td></td>
                <?php endif; endif; $childId++;?>
              </tr>
              <?php endforeach;?>
              <?php if($result->caseResult == 'fail'):?>
                <tr><td></td><td></td><td></td><td></td><td></td><td></td>
                  <td>
                    <?php echo html::submitButton($lang->testcase->createBug);?>
                  </td>
                </tr>
              <?php endif;?>
            </table>
            </form>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
      <?php foreach($results as $result):?>
      <div class="modal fade" id="caseResult<?php echo $result->id;?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title"><?php echo $lang->files;?></h4>
            </div>
            <div class="modal-body"><?php echo $this->fetch('file', 'printFiles', array('files' => $result->files, 'fieldset' => 'false'));?></div>
          </div>
        </div>
      </div>
        <?php foreach($result->stepResults as $stepID => $stepResult):?>
        <div class="modal fade" id="stepResult<?php echo $result->id . '-' .$stepID;?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo $lang->files;?></h4>
              </div>
              <div class="modal-body"><?php echo $this->fetch('file', 'printFiles', array('files' => $stepResult['files'], 'fieldset' => 'false'));?></div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      <?php endforeach;?>
      <div id='resultTip' class='hide'><?php if($count > 0) echo $failCount > 0 ? "<span>" . sprintf($lang->testtask->showFail, $failCount) . "</span>":"<span class='text-success'>{$lang->testtask->passAll}</span>";?></div>
      <style>.table-hover tr.result-detail:hover td {background: #fff} #casesResults > table > caption {border: 1px solid #ddd; margin-bottom: -1px}</style>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

