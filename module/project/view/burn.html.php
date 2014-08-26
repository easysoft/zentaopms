<?php
/**
 * The burn view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: burn.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
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
      <div class='input-group input-group-sm pull-left w-100px'>
        <?php
        $weekend = ($type == 'noweekend') ? 'withweekend' : "noweekend";
        echo "<span class='input-group-btn'>";
        echo html::a($this->createLink('project', 'burn', "projectID=$projectID&type=$weekend&interval=$interval"), $lang->project->$weekend, '', "class='btn'");
        echo '</span>';
        echo "<span class='input-group-btn'>";
        common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin', "title='{$lang->project->computeBurn}{$lang->project->burn}' class='btn btn-primary' id='computeBurn'");
        echo '</span>';
        ?>
      </div>
      <div class='text pull-left'><?php echo $lang->project->howToUpdateBurn;?></div>
    </div>
  </div>
  <?php echo $charts;?>
</div>
<?php include '../../common/view/footer.html.php';?>
