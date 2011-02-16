<?php
/**
 * The html template file of setconfig method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='g'><div class='u-1'>
  <form method='post' action='<?php echo inlink('checkconfig');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->setting . $lang->colon . strtoupper($source);?></caption>
    <?php echo $setting;?>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
    </tr>
  </table>
  <?php echo html::hidden('source', $source) . html::hidden('version', $version);?>
  </form>
</div></div>
<?php include '../../common/view/footer.html.php';?>
