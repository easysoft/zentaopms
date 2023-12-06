<?php
/**
 * The resutls view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: results.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('tab', $app->tab);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <span title='<?php echo $case->title;?>'><?php echo $case->title;?></span>
    </h2>
  </div>

  <div class='main' style='word-break:break-all'>
    <?php if($case->auto != 'unit'):?>
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->testcase->precondition;?></div>
      <div class='detail-content'><?php echo $case->precondition;?></div>
    </div>
    <?php endif;?>
    <div class='detail' id='casesResults'>
      <table class='table table-condensed table-hover' style='border: 1px solid #ddd; word-break:break-all'>
        <?php if($case->auto != 'unit'):?>
        <?php $count = count($results);?>
        <caption class='text-left'>
          <strong><?php echo $lang->testcase->result?> &nbsp;<span> <?php printf($lang->testtask->showResult, $count)?></span> <span class='result-tip'></span></strong>
        </caption>
        <?php endif;?>
        <?php $failCount = 0; $trCount=1?>
        <?php foreach($results as $i => $result):?>
        <?php
        $class = ($result->caseResult == 'pass' ? 'success' : ($result->caseResult == 'fail' ? 'danger' : ($result->caseResult == 'blocked' ? 'warning' : '')));
        if($class != 'success') $failCount++;
        $fileCount = '(' . count($result->files) . ')';
        ?>
        <tr class='result-item' data-id='<?php echo $result->id?>' data-status='<?php echo $result->node > 0 && empty($result->ZTFResult) ? 'running': 'ready';?>' id='result-<?php echo $class?>' style='cursor: pointer'>
          <td class='w-120px'> &nbsp; #<?php echo $result->id?></td>
          <td class='w-180px'><?php echo $result->date;?></td>
          <?php if($result->node > 0):?>
          <td><?php echo sprintf($lang->testtask->runNode, zget($users, $result->lastRunner), $result->nodeName, $lang->testtask->runCase) . "&nbsp;&nbsp;<span class=\"label label-badge\">{$lang->testtask->auto}</span>";?></td>
          <?php else:?>
          <td><?php echo zget($users, $result->lastRunner) . ' ' . $lang->testtask->runCase;?></td>
          <?php endif;?>
          <td class='w-150px'><?php echo zget($builds, $result->build, '');?></td>
          <td class='w-50px text-right'>
            <?php if($result->node == 0 || !empty($result->ZTFResult)):?>
            <strong class='result-testcase <?php echo $result->caseResult;?>'><?php echo $lang->testcase->resultList[$result->caseResult];?></strong>
            <?php endif;?>
            <?php if($result->node > 0 and empty($result->ZTFResult)):?>
            <strong class='span-warning'><?php echo $lang->testtask->running;?></strong>
            <?php endif;?>
        </td>
          <td class='w-60px'><?php if(!empty($result->files)) echo html::a("#caseResult{$result->id}", $lang->files . $fileCount, '', "data-toggle='modal' data-type='iframe'")?></td>
          <td class='w-50px text-center'><i class='collapse-handle icon-angle-down text-muted'></i></td>
        </tr>
        <?php $executionParam = $this->app->tab == 'execution' ? "executionID={$this->session->execution}" : "";?>
        <?php $executionParam = isset($testtask) ? "executionID=$testtask->execution" : $executionParam;?>
        <?php $params = isset($testtask) ? ",testtask=$testtask->id" : "";?>
        <?php $params = $params . ",buildID=" . (isset($testtask->build) ? $testtask->build : $result->build);?>
        <?php if($executionParam) $params .= ',' . $executionParam;?>
        <tr class='result-detail hide' id='tr-detail_<?php echo $trCount++; ?>'>
          <td colspan='7' class='pd-0'>
            <?php $projectParam = $this->app->tab == 'project' ? "projectID={$this->session->project}," : ''?>
            <form data-params='<?php echo "product=$case->product&branch=$case->branch&extras={$projectParam}caseID=$case->id,version=$case->version,resultID=$result->id,runID=$result->run" . $params?>' method='post'>
              <table class='table table-condensed resultSteps'>
                <thead>
                  <tr>
                    <th class='w-60px'><?php echo $lang->testcase->stepID;?></th>
                    <th class='text-left'><?php echo $lang->testcase->stepDesc;?></th>
                    <th class='w-p25 text-left'><?php echo $lang->testcase->stepExpect;?></th>
                    <th class='w-p5 text-left'><?php echo $lang->testcase->stepVersion;?></th>
                    <th class='w-80px text-center'><?php echo $lang->testcase->result;?></th>
                    <th class='w-p20 text-left'><?php echo $lang->testcase->real;?></th>
                    <th class='w-80px'></th>
                  </tr>
                </thead>
                <tbody>
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
                      <?php $inputName = $stepResult['type'] != 'group' ? 'stepIdList[]' : '';?>
                      <div class='checkbox-primary'>
                        <input type='checkbox' id='<?php echo $inputName;?>' name='<?php echo $inputName;?>'  value='<?php echo $key;?>'/>
                        <label><?php if($stepClass == 'step-group') echo $stepId;?></label>
                      </div>
                      <?php else:?>
                      <?php echo $stepId;?>
                      <?php endif;?>
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
                    <td><?php echo nl2br($stepResult['real']);?></td>
                    <td class='text-center'><?php if(!empty($stepResult['files'])) echo html::a("#stepResult{$modalID}", $lang->files . $fileCount, '', "data-toggle='modal' data-type='iframe'")?></td>
                    <?php else:?>
                    <td></td>
                    <td></td>
                    <?php endif; endif; $childId++;?>
                  </tr>
                  <?php endforeach;?>
                  <?php if($result->caseResult == 'fail' and common::hasPriv('testcase', 'createBug')):?>
                  <tr>
                    <td>
                      <div class="checkbox-primary">
                      <input type='checkbox' id='<?php echo "checkAll[$i]";?>' name='checkAll' />
                        <label><?php echo $lang->selectAll?></label>
                      </div>
                    </td>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td><?php echo html::commonButton($lang->testcase->createBug, "onclick='createBug(this)'", "btn btn-primary createBtn");?></td>
                  </tr>
                  <?php endif;?>
                  <?php if(!empty($result->ZTFResult) && $result->node>0):?>
                  <tr>
                    <td colspan="6">
                      <p></p>
                      <p><?php echo $lang->testtask->runningLog;?></p>
                      <p><?php echo $result->ZTFResult;?></p>
                    </td>
                  </tr>
                  <?php endif;?>
                </tbody>
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
              <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
              <h4 class="modal-title"><?php echo $lang->files;?></h4>
            </div>
            <div class="modal-body"><?php echo $this->fetch('file', 'printFiles', array('files' => $result->files, 'fieldset' => 'false'));?></div>
          </div>
        </div>
      </div>
      <?php if(!empty($result->stepResults)):?>
      <?php foreach($result->stepResults as $stepID => $stepResult):?>
      <div class="modal fade" id="stepResult<?php echo $result->id . '-' .$stepID;?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
              <h4 class="modal-title"><?php echo $lang->files;?></h4>
            </div>
            <div class="modal-body"><?php echo $this->fetch('file', 'printFiles', array('files' => $stepResult['files'], 'fieldset' => 'false'));?></div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <?php endif;?>
      <?php endforeach;?>
      <div id='resultTip' class='hide'><?php if($count > 0) echo $failCount > 0 ? "<span>" . sprintf($lang->testtask->showFail, $failCount) . "</span>":"<span class='text-success'>{$lang->testtask->passAll}</span>";?></div>
      <style>
        .table-hover tr.result-detail:hover td {background: #fff}
        #casesResults > table > caption {border: 1px solid #ddd; margin-bottom: -1px}
        #casesResults .table{margin-bottom:0px !important;}
      </style>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
