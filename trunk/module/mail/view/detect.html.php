<?php
/**
 * The detect view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<form method='post' target='hiddenwin'>
<table class='table-4' align='center'>
  <caption><?php echo $lang->mail->inputFromEmail; ?></caption>
  <tr><td class='a-center'><?php echo html::input('fromAddress', $fromAddress, 'class=text-3') . html::submitButton($lang->mail->nextStep);?></td></tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
