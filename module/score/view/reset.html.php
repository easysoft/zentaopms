<?php
/**
 * The reset view file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div class="alert with-icon" id="scoreStart">
  <i class="icon-cube"></i>
  <div class="content">
    <p><?php echo $lang->score->resetTips; ?></p>
    <p><button class="btn btn-primary" id="scoreReset"><?php echo $lang->score->resetStart; ?></button></p>
  </div>
</div>
<div class="alert with-icon hidden" id="scoreLoading">
  <i class="icon-exclamation-sign"></i>
  <div class="content">
    <p><?php echo $lang->score->resetTips; ?></p>
    <p id="loadingContent"></p>
  </div>
</div>
<div class="alert with-icon hidden" id="scoreFinish">
  <i class="icon-check-circle"></i>
  <div class="content">
    <strong><?php echo $lang->score->resetFinish; ?></strong>
  </div>
</div>
<script>
$("#scoreReset").on('click', function()
{
    $("#scoreStart").addClass('hidden');
    $("#scoreLoading").removeClass('hidden');
    scoreReset(0);
});
var total = 0;
function scoreReset(lastID)
{
    $.getJSON(createLink('score', 'reset', 'lastID=' + lastID), function(response)
    {
        if(response.result == 'finished')
        {
            $("#scoreLoading").addClass('hidden');
            $("#scoreFinish").removeClass('hidden');
            setTimeout(function(){parent.location.reload();}, 2000);
            return false;
        }
        else
        {
            total = total + response.total;
            $('#loadingContent').html("<p>" + response.message + total + "</p>");
            setTimeout(function(){scoreReset(response.lastID);}, 500);
        }
    });
}
</script>
<?php include '../../common/view/footer.html.php'; ?>
