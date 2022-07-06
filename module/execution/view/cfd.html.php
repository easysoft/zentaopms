<?php
/**
 * The burn view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: cfd.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php js::set('executionID', $executionID); ?>
<?php js::set('chartData', $chartData); ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    if(strpos('wait,doing', $execution->status) !== false)
    {
        common::printLink('execution', 'computeCFD', "reload=yes&executionID=$executionID", '<i class="icon icon-refresh"></i> ' . $lang->execution->computeCFD, 'hiddenwin', "title='{$lang->execution->computeCFD}' class='btn btn-primary' id='computeCFD'");
        echo '<div class="space"></div>';
    }
    ?>
    <div class='input-control w-100px'>
      <?php echo html::select('type', $lang->execution->cfdTypeList, $type, "class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <h2 class='text-center'><?php echo $executionName . ' - ' . zget($lang->execution->cfdTypeList, $type) . $lang->execution->CFD;?></h2>
  <div id="cfdWrapper">
    <div id="cfdChart" style="width: 1200px; height: 600px"></div>
    <div id="cfdYUnit"><?php echo $lang->execution->count;?></div>
    <div id="cfdXUnit"><?php echo $lang->execution->burnXUnit;?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
