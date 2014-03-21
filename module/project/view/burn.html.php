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
<table width='950' align='center' class='bd-none' id='burn'>
  <tr>
    <td class='a-right pr-25px' style='vertical-align:middle'>
      <?php
      $checked = ($type == 'noweekend') ? '' : "weekend";
      echo html::checkbox('', array('weekend' => $lang->project->weekend), $checked);

      if($interval)
      {
          echo $lang->project->interval;
          echo html::select('interval', $dayList, $interval);
      }

      common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin', "title='{$lang->project->computeBurn}{$lang->project->burn}' class='btn' id='computeBurn'");
      echo $lang->project->howToUpdateBurn;
      ?>
    </td>
  </tr>
  <tr><td class='a-center'><?php echo $charts; ?></td></tr>
</table>
<?php
js::set('projectID', $projectID);
js::set('type', $type);
js::set('interval', $interval);
?>
<?php include '../../common/view/footer.html.php';?>
