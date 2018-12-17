<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 5000 2013-07-03 08:20:57Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink  = $app->session->caseList != false ? $app->session->caseList : $this->createLink('testcase', 'browse', "productID=$case->product");?>
<style>
body{padding:0px;}
.xuanxuan-card{padding-bottom:55px;}
</style>
<div class='xuanxuan-card'>
  <div id="mainContent" class="main-row">
    <div class='panel-heading strong'>
      <span class='label label-id'><?php echo $case->id;?></span>
      <span class='text' title='<?php echo $case->title;?>' style='color: <?php echo $case->color; ?>'><?php echo $case->title;?></span>
      <?php if($case->fromCaseID):?>
      <small><?php echo html::icon($lang->icons['testcase']) . " {$lang->testcase->fromCase}$lang->colon$case->fromCaseID";?></small>
      <?php endif;?>
    </div>
  </div>
  <div class='main-col'>
    <div class='cell' style='word-break:break-all'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->precondition;?></div>
        <div class="detail-content article-content"><?php echo nl2br($case->precondition);?></div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testcase->steps;?></div>
        <div class="detail-content">
          <table class='table table-condensed table-hover table-striped table-bordered' id='steps'>
            <thead>
              <tr>
                <th class='w-50px'><?php echo $lang->testcase->stepID;?></th>
                <th class='w-p70 text-left'><?php echo $lang->testcase->stepDesc;?></th>
                <th class='text-left'><?php echo $lang->testcase->stepExpect;?></th>
              </tr>
            </thead>
            <?php
            $stepId = $childId = 0;
            foreach($case->steps as $stepID => $step)
            {
                $stepClass = "step-{$step->type}";
                if($step->type == 'group' or $step->type == 'step')
                {
                    $stepId++;
                    $childId = 0;
                }
                if($step->type == 'step') $stepClass = 'step-group';
                echo "<tr class='step {$stepClass}'>";
                echo "<th class='step-id'>$stepId</th>";
                echo "<td class='text-left'><div class='input-group'>";
                if($step->type == 'item') echo "<span class='step-item-id'>{$stepId}.{$childId}</span>";
                echo nl2br(str_replace(' ', '&nbsp;', $step->desc)) . "</td>";
                echo "<td class='text-left'>" . nl2br(str_replace(' ', '&nbsp;', $step->expect)) . "</div></td>";
                echo "</tr>";
                $childId ++;
            }
            ?>
          </table>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $case->files, 'fieldset' => 'true'));?>
        </div>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='xuancard-actions fixed'>
  <?php
  common::printIcon('testtask', 'results', "runID=$runID&caseID=$case->id&version=$case->version", $case, 'button', '', '', 'results', false, "data-width='90%'");
  common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=$runID", $case, 'button', 'bug', '', 'iframe', '', "data-width='90%'");

  $url  = common::getSysURL() . $this->createLink('testcase', 'edit', "case=$case->id");
  $url .= strpos($url, '?') === false ? '?' : '&';
  $url .= 'width=100%&height=100%';
  echo html::a('xxc:openUrlInDialog/' . urlencode($url), "<i class='icon-edit'></i>", '_blank', "class='btn btn-link'");
  ?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
