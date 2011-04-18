<?php
/**
 * The browse view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<table class='table-1 tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->extension->name;?></th>
    <th><?php echo $lang->extension->version;?></th>
    <th><?php echo $lang->extension->author;?></th>
    <th><?php echo $lang->extension->desc;?></th>
    <th>操作</th>
  </tr>
  </thead>
  <tbody>
  <tr >
    <td><?php echo $lang->extension->name;?></td>
    <td><?php echo $lang->extension->version;?></td>
    <td><?php echo $lang->extension->author;?></td>
    <td><?php echo $lang->extension->desc;?></td>
  </tr>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
