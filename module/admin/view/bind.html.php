<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
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
<?php include '../../common/view/footer.html.php';?>
