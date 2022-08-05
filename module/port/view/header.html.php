<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
    $requiredFields = $datas->requiredFields;
    $allCount       = $datas->allCount;
    $allPager       = $datas->allPager;
    $pagerID        = $datas->pagerID;
    $isEndPage      = $datas->isEndPage;
    $maxImport      = $datas->maxImport;
    $dataInsert     = $datas->dataInsert;
    $fields         = $datas->fields;
    $suhosinInfo    = $datas->suhosinInfo;
    $model          = $datas->model;
    $datas          = $datas->datas;
    $appendFields   = $this->session->appendFields;
    $notEmptyRule   = $this->session->notEmptyRule;
    $insert         = $this->session->insert;
?>
<style>
form{overflow-x: scroll}
.c-pri{width:100px}
.c-estimate, .c-severity, .c-openedBuild, .c-title{width:150px !important;}
.c-story{width:150px;}
.c-team {width:100px; padding:0px 0px 8px 0px !important;}
.c-estimate-1 {width:50px;padding:0px 0px 8px 8px !important;}
</style>
<?php if(!empty($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php elseif(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->task->import;?></h2>
  </div>
  <p><?php echo sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport));?></p>
  <p><?php echo html::commonButton($lang->import, "id='import'", 'btn btn-primary');?></p>
</div>
<script>
$(function()
{
    $('#maxImport').keyup(function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
    });

    $('#import').click(function()
    {
        $.cookie('maxImport', $('#maxImport').val());
        location.href = "<?php echo $this->app->getURI()?>";
    })
});
</script>
<?php die;endif;?>
<?php js::set('requiredFields', $requiredFields);?>
<?php js::set('allCount', $allCount);?>
