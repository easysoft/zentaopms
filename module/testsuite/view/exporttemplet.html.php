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
    if($.cookie('downloading') == 1)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
</script>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['export']);?></span>
    <strong><?php echo $lang->testcase->exportTemplet;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' onsubmit='setDownloading();' style='padding: 40px 5%'>
<table class='w-p100'>
  <tr>
    <td>
      <div class='input-group'>
        <span class='input-group-addon'><?php echo $lang->testcase->num;?></span>
        <?php
          echo html::input('num', '10', "class='form-control' autocomplete='off'");
        ?>
      </div>
    </td>
    <td class='w-100px'>
      <?php echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8', "class='form-control'");?>
    </td>
    <td>
      <?php echo html::submitButton();?>
    </td>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
