<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $this->app->loadLang('testcase'); unset($this->lang->testcase->resultList['n/a']); ?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testtask']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchRun']);?></small> <?php echo $lang->testtask->common . $lang->colon . $lang->testtask->batchRun;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-fixed table-form table-bordered'>
    <thead>
      <tr>
        <th class='w-id'>   <?php  echo $lang->idAB;?></th> 
        <th class='w-pri'>  <?php  echo $lang->priAB;?></th>
        <th class='w-100px'><?php echo $lang->testcase->module;?></th> 
        <th width='300'>    <?php echo $lang->testcase->title;?></th>
        <th width='180'>    <?php echo $lang->testcase->result?></th>
        <th>                <?php echo $lang->testcase->stepDesc . '/' . $lang->testcase->stepExpect?></th>
      </tr>
    </thead>
    <?php foreach($caseIDList as $caseID):?>
    <?php if(!$productID) $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($cases[$caseID]->product, $viewType = 'case', $startModuleID = 0);?>
    <tr class='text-center'>
      <td><?php echo $caseID . html::hidden("version[$caseID]", $cases[$caseID]->version)?></td>
      <td><?php echo $lang->testcase->priList[$cases[$caseID]->pri]?></td>
      <td class='text-left'><?php echo "<span title='" . $moduleOptionMenu[$cases[$caseID]->module] . "'>" . $moduleOptionMenu[$cases[$caseID]->module] . "</span>"?></td>
      <td class='text-left'><?php echo "<span title='{$cases[$caseID]->title}'>{$cases[$caseID]->title}</span>"?></td>
      <td><?php echo html::radio("results[$caseID]", $this->lang->testcase->resultList, 'pass', "onclick='showAction(this.value,\".action$caseID\")'")?></td>
      <td>
        <?php if(!empty($steps[$caseID])):?>
        <table class='table fixed'>
          <?php $i = 1;?>
          <?php foreach($steps[$caseID] as $stepID => $step):?>
          <tr>
            <td class='text-left w-p30'><?php echo "<span title='$step->desc'>" . $i . "、" . $step->desc . '</span>'?></td>
            <td class='text-left w-p30'><?php echo "<span title='$step->expect'>" . $lang->testcase->stepExpect . "：" . $step->expect . '</span>'?></td>
            <td class='w-80px hidden action<?php echo $caseID?>'><?php echo html::select("steps[$caseID][$stepID]", $lang->testcase->resultList, 'pass', "class='form-control'")?></td>
            <td class='hidden action<?php echo $caseID?>'><?php echo html::input("reals[$caseID][$stepID]", '', "class='form-control'");?></td>
          </tr>
          <?php $i++?>
          <?php endforeach?>
        </table>
        <?php endif?>
      </td>
    </tr>  
    <?php endforeach;?>
    <tr><td colspan='6' class='text-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<script type='text/javascript'>
function showAction(value, obj)
{
    if(value == 'pass')
    {
        $(obj).addClass('hidden');
    }
    else
    {
        $(obj).removeClass('hidden');
    }
}
</script>
<?php include '../../common/view/footer.html.php';?>
