<?php
/**
 * The test view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
if(isset($error))
{
    include '../../common/view/header.lite.html.php';
    die("<br />" . str_replace('\n', "<br />", join('', $error)));
}
?>

<?php include '../../common/view/header.html.php';?>
<form method='post' target='resultWin'>
<table class='table-4' align='center'>
  <caption>
    <div class='f-left'> <?php echo $lang->mail->test;?></div>
    <div class='f-right'><?php echo $lang->mail->sendmailTips;?></div>
  </caption>
  <tr>
    <td class='a-center'>
      <?php 
      echo html::select('to', $users, $app->user->account);
      echo html::submitButton($lang->mail->test);
      echo html::linkButton($lang->mail->edit, $this->inLink('edit'));
      ?>
    </td>
  </tr>
</table>
</form>
<table class='table-4 bd-none' align='center'><tr><td><iframe id='resultWin'></iframe></td></tr></table>
<?php include '../../common/view/footer.html.php';?>
