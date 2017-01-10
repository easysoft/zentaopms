<?php
/**
 * The export stories or bugs to html view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<script>
function setDownloading()
{
    if($.browser.opera) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1 || i >= 30)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
    i ++;
}
</script>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['export']);?></span>
    <strong><?php echo $lang->export;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' onsubmit='setDownloading();' style='padding: 40px 5%'>
  <table class='w-p100'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->setFileName;?></span>
          <?php echo html::input('fileName', '', "class='form-control' autocomplete='off'");?>
          <span class='input-group-addon'>.html</span>
          <?php echo html::select('type', $lang->release->exportTypeList, 'all', "class='form-control'")?>
        </div>
      </td>
      <td><?php echo html::submitButton($lang->export);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
