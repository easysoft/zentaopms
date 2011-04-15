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
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='featurebar'>
  <?php 
  echo '<span id="installed">'  . html::a(inlink('browse', "type=installed"),   $lang->extension->installed)   . '</span>';
  echo '<span id="deactivated">'. html::a(inlink('browse', "type=deactivated"), $lang->extension->deactivated) . '</span>';
  echo '<span id="available">'  . html::a(inlink('browse', "type=available"),   $lang->extension->available )  . '</span>';
  echo '<span id="download">'   . html::a(inlink('download'), $lang->extension->download) . '</span>';
  echo '<span id="upload" >'    . html::a(inlink('upload'),   $lang->extension->upload) . '</span>';
  ?>
  <script>$('#<?php echo $type;?>').addClass('active')</script>
</div>
<table class='table-1 tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->extension->name;?></th>
    <th><?php echo $lang->extension->version;?></th>
    <th><?php echo $lang->extension->author;?></th>
    <th><?php echo $lang->extension->desc;?></th>
  </tr>
  </thead>
</table>
<?php include '../../common/view/footer.html.php';?>
