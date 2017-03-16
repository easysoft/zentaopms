<?php
/**
 * The export view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php
unset($lang->exportFileTypeList);
$lang->exportFileTypeList['html'] = 'html';
?>
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
    if($.cookie('downloading') == 1)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
$(function()
{
    parent.$('.chart-canvas canvas').each(function()
    {
        chartImgData = $(this).get(0).toDataURL("image/png");
        chartID = $(this).attr('id');
        $('#submit').after("<input type='hidden' name='" + chartID +"' id='" + chartID + "' />");
        $('#' + chartID).val(chartImgData);
	});
})
</script>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['export']);?></span>
    <strong><?php echo $lang->export;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' style='padding: 40px 1% 50px'>
  <table class='w-p100 table-fixed'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->setFileName;?></span>
          <?php echo html::input('fileName', '', "class='form-control' autocomplete='off'");?>
        </div>
      </td>
      <td class='w-60px'>
        <?php echo html::select('fileType',   $lang->exportFileTypeList, '', 'onchange=switchEncode(this.value) class="form-control"');?>
      </td>
      <td style='width:70px'>
        <div class='input-group'>
          <?php echo html::submitButton($lang->export, "onclick='setDownloading();' ");?>
        </div>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
