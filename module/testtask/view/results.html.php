<?php
/**
 * The resutls view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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

  <div class='main pdb-20'>
    <fieldset>
      <legend><?php echo $lang->testcase->precondition;?></legend>
      <?php echo $case->precondition;?>
    </fieldset>
    <div id='casesResults'>
      <table class='table table-condensed table-hover' style='border: 1px solid #ddd'>
        <?php $count = count($results);?>
        <caption class='text-left' style='border: 1px solid #ddd; border-bottom: none;'>
          <strong><?php echo $lang->testcase->result?> &nbsp;<span> <?php printf($lang->testtask->showResult, $count)?></span> <span class='result-tip'></span></strong>
        </caption>
        <?php $failCount = 0; ?>
        <?php foreach($results as $result):?>
        <?php
        $class = ($result->caseResult == 'pass' ? 'success' : ($result->caseResult == 'fail' ? 'danger' : ($result->caseResult == 'blocked' ? 'warning' : '')));
        if($class != 'success') $failCount++;
        ?>
        <tr class='result-item' style='cursor: pointer'>
          <td class='w-120px'> &nbsp; #<?php echo $result->id?></td>
          <td class='w-180px'><?php echo $result->date;?></td>
          <td><?php echo $users[$result->lastRunner] . ' ' . $lang->testtask->runCase;?></td>
          <td class='w-150px'><?php echo zget($builds, $result->build);?></td>
          <td class='w-50px text-right'><strong class='text-<?php echo $class;?>'><?php echo $lang->testcase->resultList[$result->caseResult]?></strong></td>
          <td class='w-50px text-center'><i class='collapse-handle icon-chevron-down text-muted'></i></td>
        </tr>
        <tr class='result-detail hide'>
          <td colspan='6' class='pd-0'>
            <table class='table table-condensed borderless mg-0'>
              <thead>
                <tr>
                  <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
                  <th class='w-p40'><?php echo $lang->testcase->stepDesc;?></th>
                  <th class='w-p20'><?php echo $lang->testcase->stepExpect;?></th>
                  <th><?php echo $lang->testcase->result;?></th>
                  <th class='w-p20'><?php echo $lang->testcase->real;?></th>
                </tr>
              </thead>
              <?php 
              $i = 1;
              foreach($result->stepResults as $key => $stepResult):
              ?>
              <tr>
                <td class='w-30px text-center'><?php echo $i;?></td>
                <td><?php if(isset($stepResult['desc'])) echo nl2br($stepResult['desc']);?></td>
                <td><?php if(isset($stepResult['expect'])) echo nl2br($stepResult['expect']);?></td>
                <?php if(!empty($stepResult['result'])):?>
                <td class='<?php echo $stepResult['result'];?> text-center'><?php echo $lang->testcase->resultList[$stepResult['result']];?></td>
                <td><?php echo $stepResult['real'];?></td>
              </tr>
                <?php else:?>
                <td></td>
                <td></td>
              </tr>
                <?php endif; $i++;?>
              <?php endforeach;?>
            </table>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
      <div id='resultTip' class='hide'><?php if($count > 0) echo $failCount > 0 ? "<span>" . sprintf($lang->testtask->showFail, $failCount) . "</span>":"<span class='text-success'>{$lang->testtask->passAll}</span>";?></div>
      <style>.table-hover tr.result-detail:hover td {background: #fff}</style>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

