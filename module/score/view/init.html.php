<?php
/**
 * The init view file of score module of ZenTaoPMS.
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
      <h4><?php echo $lang->score->init; ?></h4>
      <hr>
      <p><?php echo $lang->score->initTips; ?></p>
      <p>
        <button class="btn btn-primary" id="score_init"><?php echo $lang->score->initStart; ?></button>
      </p>
    </div>
  </div>
  <div class="alert alert-info with-icon hidden" id="score_loading">
    <i class="icon-info-sign"></i>
    <h4><?php echo $lang->score->initLoading; ?></h4>
    <hr/>
    <p><?php echo $lang->score->initTips; ?></p>
    <p id="loading_content"></p>
  </div>
  <div class="alert alert-success with-icon hidden" id="score_finish">
    <i class="icon-ok-sign"></i>
    <div class="content">
      <strong><?php echo $lang->score->initFinish; ?></strong>
    </div>
  </div>
  <script>
      $("#score_init").on('click', function()
      {
          $("#score_start").addClass('hidden');
          $("#score_loading").removeClass('hidden');
          score_init(0);
      });

      function score_init(lastID)
      {
          $.getJSON(createLink('score', 'init', 'lastID=' + lastID), function(response)
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
                  $('#loading_content').html("<li class='text-success' style='color:#" + (Math.random() * 0xffffff << 0).toString(16) + "'>" + response.message + "</li>");
                  setTimeout(function(){score_init(response.lastID);}, 500);
              }
          });
      }
  </script>
<?php include '../../common/view/footer.html.php'; ?>