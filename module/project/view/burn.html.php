<?php
/**
 * The burn view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: burn.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<?php include './taskheader.html.php';?>
<?php js::set('projectID', $projectID);?>
<?php js::set('type', $type);?>
<div class='container text-center bd-0'>
  <div class='clearfix'>
    <div class='actions pull-right'>
      <?php if($interval):?>
      <div class='input-group input-group-sm pull-left w-100px'>
        <?php echo html::select('interval', $dayList, $interval, "class='form-control'");?>
      </div>
      <?php endif;?>
      <?php
      $weekend = ($type == 'noweekend') ? 'withweekend' : "noweekend";
      common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin', "title='{$lang->project->computeBurn}{$lang->project->burn}' class='btn btn-primary btn-sm' id='computeBurn'");
      echo html::a($this->createLink('project', 'burn', "projectID=$projectID&type=$weekend&interval=$interval"), $lang->project->$weekend, '', "class='btn btn-sm'");
      common::printLink('project', 'fixFirst', "project=$project->id", $lang->project->fixFirst, '', "class='btn btn-sm iframe' data-width='500'");
      echo $lang->project->howToUpdateBurn;
      ?>
    </div>
  </div>
  <div class='canvas-wrapper'><div class='chart-canvas'><canvas id='burnChart' width='800' height='400' data-bezier-curve='false' data-responsive='true'></canvas></div></div>
  <h1><?php echo $projectName . ' ' . $this->lang->project->burn;?></h1>
</div>
<script>
function initBurnChar()
{
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->project->baseline;?>",
            color: "#CCC",
            showTooltips: false,
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->project->Left?>",
            color: "#0033CC",
            pointStrokeColor: '#0033CC',
            pointHighlightStroke: '0033CC',
            data: <?php echo $chartData['burnLine']?>
        }]
    };

    var burnChart = $("#burnChart").lineChart(data,
    {
        animation: !($.zui.browser && $.zui.browser.ie === 8),
        pointDotStrokeWidth: 0,
        pointDotRadius: 1,
        datasetFill: false,
        datasetStroke: true,
        scaleShowBeyondLine: false,
        multiTooltipTemplate: "<%= value %>h"
    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
