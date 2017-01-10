<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['edit']);?></span>
    <strong><?php echo $lang->file->inputFileName;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' onsubmit='setFileName();' style='padding: 30px 5%'>
  <table class='w-p100'>
    <tr>
      <td>
        <div class='input-group'>
          <?php echo html::input('fileName', $file->title, "class='form-control' autocomplete='off'");?>
          <strong class='input-group-addon'>.<?php echo $file->extension;?></strong>
        </div>
      </td>
      <td><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
