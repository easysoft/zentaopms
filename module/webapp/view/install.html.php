<?php
/**
 * The install view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <Yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form method='post' target='hiddenwin'>
<div class='box-title'><?php echo $lang->webapp->install?></div>
<div class='box-content'>
  <p><span><?php echo $lang->webapp->selectModule?></span>
  <?php
  echo html::select('module', $modules);
  common::printLink('tree', 'browse', "rootID=0&view=webapp", $lang->tree->manage, '_parent');
  ?>
  </p>
  <p align='center'><?php echo html::submitButton()?></p>
<div>
</form>
<?php include '../../common/view/footer.lite.html.php';?>

