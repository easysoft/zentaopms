<?php
/**
 * The install view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <Yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='WEBAPP'><?php echo html::icon($lang->icons['app']);?></span>
    <strong><small class='text-muted'> <i class='icon-cog'></i></small> <?php echo $lang->webapp->install;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' style='padding: 40px 5%'>
  <table class='table table-form'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->webapp->selectModule?></span>
          <?php echo html::select('module', $modules, '', "class='form-control'");?>
        </div>
      </td>
      <td><?php echo html::submitButton();?></td>
      <td class='w-80px'><?php common::printLink('tree', 'browse', "rootID=0&view=webapp", $lang->tree->manage, '_parent');?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>

