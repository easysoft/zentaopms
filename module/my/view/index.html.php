<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 1947 2011-06-29 11:58:03Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/jquerytools.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='cont' id='row1'>
  <tr valign='top'>
    <td width='33%' style='padding-right:20px'><?php include './blockprojects.html.php';?></td>
    <td width='33%' style='padding-right:20px'><?php include './blockproducts.html.php';?></td>
    <td width='33%'><?php include './blockdynamic.html.php';?></td>
  </tr>
</table>
<table class='cont' id='row2'>
  <tr valign='top'>
    <td width='33%' style='padding-right:20px'><?php include './blocktodoes.html.php';?></td>
    <td width='33%' style='padding-right:20px'><?php include './blocktasks.html.php';?></td>
    <td width='33%'><?php include './blockbugs.html.php';?></td>
  </tr>
</table>
<script language='Javascript'>
var projectCounts = <?php echo count($projectStats['charts']);?>;
var productCounts = <?php echo count($productStats['charts']);?>;
<?php for($i = 1;  $i <= count($projectStats['charts']); $i ++) echo "createChart$i();"; ?>
<?php for($j = $i; $j <  count($productStats['charts']) + $i; $j ++) echo "createChart$j();"; ?>
</script>
<?php include '../../common/view/footer.html.php';?>
