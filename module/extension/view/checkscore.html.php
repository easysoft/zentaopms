<?php
/**
 * The install view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table class='table-1'>
  <caption><?php echo $header->title;?></caption>
  <tr>
    <td valign='middle'>
      <?php if($error):?>
        <?php 
        echo "<h3 class='error'>{$lang->extension->needSorce}</h3>"; 
        echo "<p>$error</p>";
        ?>
      <?php endif;?>
    </td>
  </tr>
</table>
</body>
</html>
