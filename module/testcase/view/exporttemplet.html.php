<?php include '../../common/view/header.lite.html.php';?>
<script>
function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

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
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->testcase->exportTemplet;?></h2>
  </div>

  <form method='post' target='hiddenwin' onsubmit='setDownloading();' style='padding: 40px 5%'>
    <table class='w-p100'>
      <tr>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->testcase->num;?></span>
            <?php
            echo html::input('num', '10', "class='form-control'");
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
</div>
<?php include '../../common/view/footer.lite.html.php';?>
