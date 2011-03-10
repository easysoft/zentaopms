<?php
/**
 * The filed view file of help module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     help
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table class='table-1' height=180>
  <tr valign='middle' class='f-14px'>
    <td><?php echo '<strong>' . $fieldName . '</strong>' . ($fieldName ? $lang->arrow : '') .  $fieldNote?></td>
  </tr>
</table>
<?php include '../../common/view/footer.lite.html.php';
