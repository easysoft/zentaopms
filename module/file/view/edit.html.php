<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>
body{padding-bottom:0px}
</style>

<script>
function setFileName()
{
    time = setInterval("closeWindow()", 200);
    return true;
}

function closeWindow()
{
    parent.$.closeModal();
    clearInterval(time);
}
</script>

<form method='post' target='hiddenwin' onsubmit='setFileName();'>
  <table class='table-1'>
    <caption><?php echo $lang->file->inputFileName;?></caption>
    <tr>
      <td class='text-center' style='padding-top:30px;'>
        <?php echo html::input('fileName', $file->title) . "<strong>.{$file->extension}</strong>";?>
        <?php echo html::submitButton();?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
