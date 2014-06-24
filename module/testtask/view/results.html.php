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
      <small class='text-info'><?php echo $lang->testtask->results . ' ' . html::icon($lang->icons['result']);?></small>
    </div>
  </div>

  <div class='main pdb-20'>
    <fieldset>
      <legend><?php echo $lang->testcase->precondition;?></legend>
      <?php echo $case->precondition;?>
    </fieldset>
    <?php foreach($results as $result):?>
    <table class='table table-condensed table-hover table-striped'>
      <caption class='text-left bd-0'>
        <?php if(isset($build)):?><div class='pull-right'><?php echo $lang->testtask->build . $lang->colon . $build;?></div>
        <?php endif; ?>
        RESULT#<?php echo $result->id . ' ' . $result->date . ' ' . $users[$result->lastRunner] . ' ' . $lang->testtask->runCase . ':'. " <span class='$result->caseResult'>" . $lang->testcase->resultList[$result->caseResult] . '</span>';?>
      </caption>
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
    <?php endforeach;?>
  </div>
</div>

