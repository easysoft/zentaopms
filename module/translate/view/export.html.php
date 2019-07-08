<?php include '../../common/view/header.lite.html.php';?>
<script>
function setDownloading()
{
    if($.browser.opera) return true;   // Opera don't support, omit it.

    var $fileName = $('#fileName');
    if($fileName.val() === '') $fileName.val('<?php echo $lang->file->untitled;?>');

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    $('#mainContent').addClass('loading');
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        $('#mainContent').removeClass('loading');
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
</script>
<div id="mainContent" class="main-content load-indicator">
  <div class="main-header">
    <h2><?php echo $lang->export;?></h2>
  </div>
  <form class="main-form" method="post" target="hiddenwin">
    <table class="table table-form">
      <tbody>
        <tr>
          <th><?php echo $lang->file->fileName;?></th>
          <td class="w-300px"><?php echo html::input('fileName', isset($fileName) ? $fileName : '', "class='form-control' autofocus placeholder='{$lang->file->untitled}'");?></td>
          <td><?php echo html::submitButton($lang->export, "onclick='setDownloading();'", 'btn btn-primary');?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
