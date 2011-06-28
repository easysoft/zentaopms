<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/jquerytools.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='cont' id='row1'>
  <tr valign='top'>
    <td width='33%' style='padding-right:10px'><?php include './projects.html.php';?></td>
    <td width='33%' style='padding-right:10px'><?php include './products.html.php';?></td>
    <td width='33%'><?php include './dynamic.html.php';?></td>
  </tr>
</table>
<table class='cont' id='row2'>
  <tr valign='top'>
    <td width='33%' style='padding-right:10px'><?php include './mytodoes.html.php';?></td>
    <td width='33%' style='padding-right:10px'><?php include './mytasks.html.php';?></td>
    <td width='33%'><?php include './mybugs.html.php';?></td>
  </tr>
</table>
<script language='Javascript'>
var projectCounts = <?php echo count($projectStats['projects']);?>;
var productCounts = <?php echo count($productStats['products']);?>;
<?php for($i = 1;  $i <= count($projectStats['projects']); $i ++) echo "createChart$i();"; ?>
<?php for($j = $i; $j <  count($productStats['products']) + $i; $j ++) echo "createChart$j();"; ?>
</script>
<?php include '../../common/view/footer.html.php';?>
