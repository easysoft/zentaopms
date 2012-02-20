<?php
/**
 * The html template file of step4 method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author	  Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package	 ZenTaoPMS
 * @version  $Id: step5.html.php 2568 2012-02-18 16:32:05Z zhujinyong@cnezsoft.com$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<form method="post" target="hiddenwin">
<table align='center' class='table-6'>
<caption><?php echo $lang->admin->login->caption;?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->user->account;?></th>
	<td><?php echo html::input('account', '', "class='text-3'");?></td>
  </tr>
  <tr>
    <th class="rowhead"><?php echo $lang->user->password;?></th>
    <td><?php echo html::password('password', '', "class='text-3'");?></td>
  </tr>  
  <tr>
    <th><td colspan="2" class="a-center"><?php echo html::submitButton() . html::hidden('sn', $sn);?></td></th>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
