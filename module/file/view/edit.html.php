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
<style>.main-form {margin: 15px -15px 0}</style>
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
<main id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
      <div class='main-header'>
        <h2><?php echo $lang->file->inputFileName;?></h2>
      </div>
      <form class='main-form' method='post' target='hiddenwin' onsubmit='setFileName();'>
        <table class='table table-form'>
          <tr>
            <td>
              <div class='input-group'>
                <?php echo html::input('fileName', $file->title, "class='form-control' autocomplete='off'");?>
                <strong class='input-group-addon'>.<?php echo $file->extension;?></strong>
              </div>
            </td>
            <td class='w-80px'><?php echo html::submitButton('', '', 'btn btn-primary btn-block');?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
