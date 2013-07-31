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
<div class='a-center'>
  <?php
  echo $charts; 
  common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin');
  echo html::a(inlink('burn', "projectID=$projectID&type=" . ($type == 'noweekend' ? 'withweekend' : 'noweekend')), ($type == 'noweekend' ? $lang->project->withWeekend : $lang->project->noWeekend));
  echo $lang->project->howToUpdateBurn;
  ?>
</div>
<?php include '../../common/view/footer.html.php';?>
