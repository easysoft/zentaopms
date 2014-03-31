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
<script>$('#burnTab').addClass('active');</script>
<div class='text-center'>
  <?php
  if($interval) echo html::select('interval', $dayList, $interval);
  $weekend = ($type == 'noweekend') ? 'withweekend' : "noweekend";
  echo html::a($this->createLink('project', 'burn', "projectID=$projectID&type=$weekend&interval=$interval"), $lang->project->$weekend, '', "class='btn'");
  common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin', "title='{$lang->project->computeBurn}{$lang->project->burn}' class='btn' id='computeBurn'");
  echo $lang->project->howToUpdateBurn;
  echo $charts; 
  ?>
</div>
<?php include '../../common/view/footer.html.php';?>
