<?php
/**
 * The browse view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
    <th class='w-100px'><?php echo $lang->extension->code;?></th>
    <th class='w-50px'><?php echo $lang->extension->version;?></th>
    <th><?php echo $lang->extension->abstract;?></th>
    <th class='w-100px'><?php echo $lang->extension->author;?></th>
    <th class='w-200px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($extensions as $extension):?>
  <tr >
    <td><?php echo $extension->name;?></td>
    <td><?php echo $extension->code;?></td>
    <td class='a-center'><?php echo $extension->version;?></td>
    <td><?php echo $extension->desc;?></td>
    <td><?php echo $extension->author;?></td>
    <td class='a-right'>
      <?php

      $deactivateCode = html::a(inlink('deactivate', "extension=$extension->code"), $lang->extension->deactivate, '', "class='button-c iframe'");
      $activateCode   = html::a(inlink('activate',   "extension=$extension->code"), $lang->extension->activate, '',   "class='button-c iframe'");
      $uninstallCode  = html::a(inlink('uninstall',  "extension=$extension->code"), $lang->extension->uninstall, '',  "class='button-c iframe'");
      $installCode    = html::a(inlink('install',    "extension=$extension->code"), $lang->extension->install, '',    "class='button-c iframe'");
      $eraseCode      = html::a(inlink('erase',      "extension=$extension->code"), $lang->extension->erase, '',      "class='button-c iframe'");

      if($extension->status == 'installed' and !empty($extension->upgradeLink))
      {
          echo html::a($extension->upgradeLink, $lang->extension->upgrade, '', "class='button-c iframe'");
      }

      if($extension->type != 'patch')
      {
          if($extension->status == 'installed')   echo $deactivateCode . $uninstallCode;
          if($extension->status == 'deactivated') echo $activateCode   . $uninstallCode;
          if($extension->status == 'available')   echo $installCode    . $eraseCode;
      }
      echo html::a($extension->site, $lang->extension->site, '_blank', 'class=button-c');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
