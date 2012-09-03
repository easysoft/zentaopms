<?php
/**
 * The save view file of mail module of ZenTaoPMS.
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
<table class='table-5' align='center'>
  <caption><?php echo $lang->mail->save ?></caption>
  <tr>
    <td>
      <?php 
      echo html::textArea('', $mailConfig, "rows='12' class='area-1'") . '<br /><br />'; 
      if($saved)  printf($lang->mail->successSaved, $configFile);
      if(!$saved) printf($lang->mail->saveManual,   $configFile);
      echo html::linkButton($lang->mail->test, inlink('test'));
      ?>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
