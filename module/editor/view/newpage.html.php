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
<?php include '../../common/view/header.lite.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin'>
<div class='panel panel-sm'>
  <div class='panel-heading'><i class='icon-plus'></i> <strong><?php echo $lang->editor->newPage?></strong></div>
  <div class='panel-body'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->editor->filePath?></th>
        <td><code><?php echo $filePath?></code></td>
      </tr>
      <tr>
        <th><?php echo $lang->editor->pageName?></th>
        <td>
        <?php
        echo html::input('fileName', '', "class=form-control");
        echo "<div class='help-block'>" . $lang->editor->examplePHP . "</div>";
        ?>
        </td>
      </tr>
      <tr><td colspan='2' align='center'><?php echo html::submitButton()?></td></tr>
    </table>
  </div>
</div>


</form>
<?php include '../../common/view/footer.lite.html.php';?>
