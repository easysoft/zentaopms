<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>
body{width:100% !important;padding:0;}
.m-measurement-viewtemplate {background-color: #fff;}
.m-measurement-viewtemplate .panel {margin-bottom: 0;}
</style>
<div class="panel h-600px w-p100">
  <h2 class='text-center'><?php echo $this->post->name;?></h2>
  <div class='panel-body'>
    <?php echo $content;?>
    <?php if(common::hasPriv('measurement', 'saveReport')):?>
    <div class='panel-footer text-center'>
      <form action="<?php echo inlink('saveReport');?>" method="post" class='form-ajax' id="reportForm">
        <?php echo html::hidden('name', $this->post->name);?>
        <?php echo html::hidden('project', (int)$this->post->projectID);?>
        <?php echo html::hidden('template', $template->id);?>
        <?php echo html::textarea('content', $content, "class='hidden'");?>
        <?php echo html::hidden('params',  json_encode($this->post->params));?>
        <?php echo html::submitButton($lang->measurement->saveReport, "class='btn btn-primary'");?>
      </form>
    </div>
    <?php endif;?>
  </div>
</div>
<script>
parent.$('#reportWin').height($('.panel').outerHeight());
</script>
