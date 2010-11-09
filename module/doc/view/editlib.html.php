<?php
/**
 * The editlib file of doc module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: editlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:white; margin-top:20px; padding-bottom:0}</style>
<div id='yui-d0'>
  <?php if($libID == 'product' or $libID == 'project'):?>
    <h3><?php echo $lang->doc->errorEditSystemDoc;?></h3>
  <?php else:?>
  <form method='post'>
    <table class='table-1'> 
      <caption><?php echo $lang->doc->editLib;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->doc->libName;?></th>
        <td><?php echo html::input('name', $libName, "class='text-1'");?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
  <?php endif;?>
</div>  
</body>
</html>
