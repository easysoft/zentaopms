<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../extension/view/header.html.php';?>
  <table class='w-p100'>
    <tr>
      <td class='w-200px'>
        <div class='panel panel-sm with-list'>
          <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->editor->moduleList?></strong></div>
          <?php echo $moduleList?>
        </div>
      </td>
      <td class='w-300px'><iframe frameborder='0' name='extendWin' id='extendWin' width='100%'></iframe></td>
      <td><iframe frameborder='0' name='editWin' id='editWin' width='100%'></iframe></td>
    </tr>
  </table> 

<?php include '../../common/view/footer.html.php';?>
