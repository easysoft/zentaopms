<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: execute.html.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog result-box'>
    <div class='modal-header'>
      <strong><?php echo $lang->upgrade->result;?></strong>
    </div>
    <div class='modal-body'>
      <div class='row'>
        <div class='col-md-6'>
          <div class='message mgb-10 text-center'>
            <strong><?php echo $lang->upgrade->success?></strong>
            <div><?php echo html::a('index.php', $lang->upgrade->tohome, '', "class='btn btn-primary btn-wide' id='tohome'")?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    sessionStorage.removeItem('TID');
    initHideHome();
    finishedShow();
})
<?php
foreach($needProcess as $processKey => $processType)
{
    if($processType == 'notice') continue;
    echo 'var ' . $processKey . "Finish = false;\n";
}
?>

/**
 * Init hide message.
 *
 * @access public
 * @return void
 */
function initHideHome()
{
    var hide = false;
    <?php
    foreach($needProcess as $processKey => $processType)
    {
        if($processType == 'notice') continue;
        echo "hide = true;\n";
        break;
    }
    ?>
    if(hide)
    {
        $('a#tohome').closest('.message').hide();
        $('.result-box').css('height', 'auto').css('position', 'relative').css('margin-top', '0px');
    }
}

/**
 * Finished show message.
 *
 * @access public
 * @return void
 */
function finishedShow()
{
    var show = true;
    <?php
    foreach($needProcess as $processKey => $processType)
    {
        if($processType == 'notice') continue;
        echo "if({$processKey}Finish == false) show = false;\n";
    }
    ?>
    if(show)
    {
        $.get('<?php echo inlink('afterExec', "fromVersion=$fromVersion&processed=yes&skipMoveFile=yes")?>');
        $('a#tohome').closest('.message').show();
    }
}

<?php if(isset($needProcess['changeEngine'])):?>
$(function()
{
    $('.col-md-6:first').append("<div class='alert alert-info'><p><?php echo $lang->upgrade->needChangeEngine;?></p></div>");
})
<?php endif;?>
<?php if(isset($needProcess['updateFile'])):?>
$(function()
{
    $('.col-md-6:first').append("<div id='resultBox' class='alert alert-info'><p><?php echo $lang->upgrade->updateFile;?></p></div>");
    updateFile('<?php echo inlink('ajaxUpdateFile')?>');
})
function updateFile(link)
{
    $.getJSON(link, function(response)
    {
        if(response == null)
        {
            updateFileFinish = true;
            finishedShow();
        }
        else if(response.result == 'finished')
        {
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            updateFileFinish = true;
            $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            finishedShow();
        }
        else
        {
            if($('#resultBox li span.' + response.type + '-num').size() == 0 || response.type != response.nextType)
            {
                $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            }
            var num = parseInt($('#resultBox li span.' + response.type + '-num').html());
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            updateFile(response.next);
        }
    });
}
<?php endif;?>
<?php if(isset($needProcess['search'])):?>
$(function()
{
    $('.col-md-6:first').append("<div class='alert alert-info'><p><?php echo $lang->upgrade->needBuild4Add;?></p></div>");
})
<?php endif;?>
</script>
<?php include '../../common/view/footer.lite.html.php';?>
