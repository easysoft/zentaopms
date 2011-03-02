<?php
/**
 * The html template file of index method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table align='center' class='table-5 f-14px'>
  <caption><?php echo $lang->upgrade->warnning;?></caption>
  <tr>
    <td><?php echo $lang->upgrade->warnningContent;?></td>
  </tr>
  <tr>
    <td colspan='2' class='a-center'><?php echo html::linkButton($lang->upgrade->common, inlink('selectVersion'));?></td>
  </tr>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
