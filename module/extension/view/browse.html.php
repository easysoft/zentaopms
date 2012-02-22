<?php
/**
 * The browse view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php foreach($extensions as $extension):?>
<table class='table-1 exttable'>
  <caption><?php echo "$extension->name";?></caption> 
  <tr valign='middle'>
    <td>
      <div class='mb-10px'><?php echo $extension->desc;?></div>
      <div>
        <?php
        echo "{$lang->extension->version}:    <i>{$extension->version}</i> ";
        echo "{$lang->extension->author}:     <i>{$extension->author}</i> ";
        ?>
      </div>
    </td>
    <td class='w-220px a-right'>
    <?php
    $structureCode  = html::a(inlink('structure',  "extension=$extension->code"), $lang->extension->structure, '',  "class='button-c iframe'");
    $deactivateCode = html::a(inlink('deactivate', "extension=$extension->code"), $lang->extension->deactivate, '', "class='button-c iframe'");
    $activateCode   = html::a(inlink('activate',   "extension=$extension->code"), $lang->extension->activate, '',   "class='button-c iframe'");
    $uninstallCode  = html::a(inlink('uninstall',  "extension=$extension->code"), $lang->extension->uninstall, '',  "class='button-c iframe'");
    $installCode    = html::a(inlink('install',    "extension=$extension->code"), $lang->extension->install, '',    "class='button-c iframe'");
    $eraseCode      = html::a(inlink('erase',      "extension=$extension->code"), $lang->extension->erase, '',      "class='button-c iframe'");
    
    if(isset($extension->viewLink))
    {
        echo html::a($extension->viewLink, $lang->extension->view, '', "class='button-c extension'");
    }
    if($extension->status == 'installed')
    {
        echo $structureCode;
    }
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
</table>
<?php endforeach;?>
<?php include '../../common/view/footer.html.php';?>
