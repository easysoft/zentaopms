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
        parent.$.fn.colorbox.close();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
</script>
<form method='post' target='hiddenwin' onsubmit='setDownloading();'>
<table class='table-1 mt-10px'>
  <caption><?php echo $lang->testcase->exportTemplet?></caption>
  <tr>
    <td align='center'>
    <?php
    echo $lang->testcase->num;
    echo html::input('num', '10');
    echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8');
    echo html::submitButton();
    ?>
    </td>
  <tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
