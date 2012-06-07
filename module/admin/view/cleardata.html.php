<?php
/**
 * The view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-5'>
    <caption><?php echo $lang->admin->clearData;?></caption>
    <tr><td><?php echo nl2br($lang->admin->clearDataDesc);?></td></tr>
    <tr><td class='a-center'><?php echo html::submitButton($lang->admin->clearData) . html::hidden('confirm', 'no');?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
