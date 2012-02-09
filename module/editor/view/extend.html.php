<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<table class='table-1'>
    <caption><?php echo isset($lang->editor->modules[$module])? $lang->editor->modules[$module] : $module;?></caption>
  <tr>
    <td valign='top'><?php echo $tree?></td>
  </tr>
</table>
<script type='text/javascript'>
$(function()
{
    $("#extendTree").treeview();
});
</script>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='hidden'></iframe>
<body>
</html>
