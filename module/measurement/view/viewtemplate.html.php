<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $disablePgm = $projectID ? 'readonly' : '';?>
<div class="col-md-3">
  <div class="panel">
    <div class="panel-heading">
      <h4><?php echo $lang->measurement->params;?></h4>
    </div>
    <div class="panel-body">
      <form class="load-indicator main-form form-horizontal" id="paramsForm" target='reportWin' method='post'>
        <div class="form-group">
          <label class='col-md-3'><?php echo $this->lang->measurement->report->name?></label>
          <div class="col-md-9">
          <?php echo html::input('name', $template->name, "class='form-control'");?>
          </div>
        </div>
        <div class="form-group">
          <label class='col-md-3'><?php echo $this->lang->measurement->report->program?></label>
          <div class="col-md-9">
            <?php if(!$projectID) echo html::select('projectID', $projectPairs, $projectID, "$disablePgm class='form-control chosen'");?>
            <?php if($projectID)  echo html::hidden('projectID', $projectID);?>
          </div>
        </div>
        <?php echo $this->measurement->buildTemplateForm($components);?>
        <div class="form-group text-center">
        <?php echo html::hidden('parseContent', 'yes') . html::submitButton($lang->measurement->tips->view, "", "btn btn-secondary");?>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="col-md-9">
  <iframe id='reportWin' name='reportWin' frameborder='0' scrolling='yes' class='w-p100' height="700"></iframe>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
