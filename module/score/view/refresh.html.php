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
  <div class="alert with-icon" id="refresh_start">
    <i class="icon-inbox"></i>
    <div class="content">
      <h4><?php echo $lang->score->refresh; ?></h4>
      <hr>
      <p><?php echo $lang->score->refreshTips; ?></p>
      <p>
        <button class="btn btn-primary" id="refresh_init"><?php echo $lang->score->refreshStart; ?></button>
      </p>
    </div>
  </div>
  <div class="alert alert-info with-icon hidden" id="refresh_loading">
    <i class="icon-info-sign"></i>
    <h4><?php echo $lang->score->refreshLoading; ?></h4>
    <hr/>
    <p><?php echo $lang->score->refreshTips; ?></p>
    <p id="loading_content"></p>
  </div>
  <div class="alert alert-success with-icon hidden" id="refresh_finish">
    <i class="icon-ok-sign"></i>
    <div class="content">
      <strong><?php echo $lang->score->refreshFinish; ?></strong>
    </div>
  </div>
  <script>
      $("#refresh_init").on('click', function()
      {
          $("#refresh_start").addClass('hidden');
          $("#refresh_loading").removeClass('hidden');
          refresh_init(0);
      });

      function refresh_init(lastID)
      {
          $.getJSON(createLink('score', 'refresh', 'lastID=' + lastID), function(response)
          {
              if(response.result == 'finished')
              {
                  $("#refresh_loading").addClass('hidden');
                  $("#refresh_finish").removeClass('hidden');
                  setTimeout(function(){parent.location.reload();}, 2000);
                  return false;
              }
              else
              {
                  $('#loading_content').html("<li class='text-success' style='color:#" + (Math.random() * 0xffffff << 0).toString(16) + "'>" + response.message + "</li>");
                  setTimeout(function(){refresh_init(response.lastID);}, 2000);
              }
          });
      }
  </script>
<?php include '../../common/view/footer.html.php'; ?>