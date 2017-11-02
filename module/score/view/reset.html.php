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
  <div class="alert with-icon" id="score_start">
    <i class="icon-inbox"></i>
    <div class="content">
      <p><?php echo $lang->score->resetTips; ?></p>
      <p>
        <button class="btn btn-primary" id="score_reset"><?php echo $lang->score->resetStart; ?></button>
      </p>
    </div>
  </div>
  <div class="alert alert-info with-icon hidden" id="score_loading">
    <i class="icon-info-sign"></i>
    <div class="content">
      <p><?php echo $lang->score->resetTips; ?></p>
      <p id="loading_content"></p>
    </div>
  </div>
  <div class="alert alert-success with-icon hidden" id="score_finish">
    <i class="icon-ok-sign"></i>
    <div class="content">
      <strong><?php echo $lang->score->resetFinish; ?></strong>
    </div>
  </div>
  <script>
      $("#score_reset").on('click', function()
      {
          $("#score_start").addClass('hidden');
          $("#score_loading").removeClass('hidden');
          score_reset(0);
      });
      var total = 0;
      function score_reset(lastID)
      {
          $.getJSON(createLink('score', 'reset', 'lastID=' + lastID), function(response)
          {
              if(response.result == 'finished')
              {
                  $("#score_loading").addClass('hidden');
                  $("#score_finish").removeClass('hidden');
                  setTimeout(function(){parent.location.reload();}, 2000);
                  return false;
              }
              else
              {
                  total = total + response.total;
                  $('#loading_content').html("<li class='text-success'>" + response.message + total + "</li>");
                  setTimeout(function(){score_reset(response.lastID);}, 500);
              }
          });
      }
  </script>
<?php include '../../common/view/footer.html.php'; ?>