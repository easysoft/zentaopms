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
    <th class='w-150px'><?php echo $lang->extension->name;?></th>
    <th class='w-50px'><?php echo $lang->extension->version;?></th>
    <th><?php echo $lang->extension->desc;?></th>
    <th class='w-100px'><?php echo $lang->extension->author;?></th>
    <th class='w-100px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($extensions as $extension):?>
  <tr >
    <td><?php echo $extension->name;?></td>
    <td class='a-center'><?php echo $extension->version;?></td>
    <td><?php echo $extension->desc;?></td>
    <td><?php echo $extension->author;?></td>
    <td class='a-center'>
      <?
      echo html::a(inlink('uninstall',  "extension=$extension->code"), $lang->extension->uninstall, '', 'class=button-c');
      echo html::a(inlink('deactivate', "extension=$extension->code"), $lang->extension->deactivate, '', 'class=button-c');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
