<?php
/**
 * The prjcreateguide view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: prjcreateguide.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<div class="modal fade" id='guideDialog'>
  <div class="modal-dialog">
    <div class='modal-content'>
      <div class='modal-body'>
        <h2 class='text-center'><?php echo $lang->project->chooseProgramType; ?></h2>
        <div class='row'>
          <div class='col-xs-6'>
            <div class='project-type text-center'>
              <img class='project-type-img' data-type='scrum' src='<?php echo $config->webRoot . 'theme/default/images/main/scrum.png'?>'>
              <h3><?php echo $lang->project->scrum; ?></h3>
              <p><?php echo $lang->project->scrumTitle; ?></p>
            </div>
          </div>
          <div class='col-xs-6'>
            <div class='project-type text-center'>
              <img class='project-type-img' data-type='waterfall' src='<?php echo $config->webRoot . 'theme/default/images/main/waterfall.png'?>'>
              <h3><?php echo $lang->project->waterfall; ?></h3>
              <p><?php echo $lang->project->waterfallTitle; ?></p>
            </div>
          </div>
        </div>
        <div class='modal-footer text-center'>
          <a class='btn btn-primary btn-wide disabled' id='guideBtn'><?php echo $lang->project->nextStep; ?></a>
          <button type='button' class='btn btn-default btn-wide' data-dismiss='modal'><?php echo $lang->cancel; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$('#guideDialog').on('click', '.project-type-img', function()
{
    var $this = $(this);
    $('#guideDialog .project-type.active').removeClass('active');
    $this.parent().addClass('active');
    $('#guideBtn').removeClass('disabled').attr('href', createLink('project', 'PRJCreate', 'template=' + $this.data('type') + "&projectID=" + projectID));
});
</script>
