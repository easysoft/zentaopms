<?php $isAjaxRequest = helper::isAjaxRequest();?>
<?php js::set('isAjaxRequest', $isAjaxRequest);?>
<?php if(!$isAjaxRequest): ?>
<?php include "../../common/view/header.html.php"?>
<?php endif;?>
<div class='modal-dialog' id='guideDialog'>
  <style>
  #guideDialog {width: 900px; box-shadow: none;}
  #guideDialog .row {margin-left: 0px;margin-right: 0px;}
  #guideDialog h2 {margin: 0px 0 20px 0; font-size: 16px; font-weight: normal}
  #guideDialog h3 {margin: 5px 0; font-size: 15px;}
  #guideDialog p {font-size: 13px;}
  #guideDialog .modal-footer {border-top: none; text-align: center; padding-top: 10px; padding-bottom: 40px;}
  #guideDialog .modal-footer .btn + .btn {margin-left: 20px}
  #guideDialog .project-type {padding: 0 5px}
  #guideDialog .project-type-img {width: 280px; border: 1px solid #CBD0DB; border-radius: 4px; margin-bottom: 10px; cursor: pointer; margin-top: 1px}
  #guideDialog .project-type-img:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,0,.25);}
  #guideDialog .project-type-img.more-type {height: 165px; vertical-align: middle; display: table-cell; cursor:default; font-size: 12px;}
  #guideDialog .project-type-img.more-type:hover {border-color: #CBD0DB; box-shadow:unset;}
  #guideDialog .project-type.active img {border-color: #006AF1; border-width: 2px; margin-top: 0}
  #guideDialog .col:nth-child(-n+3) {margin-bottom: 15px;}
  @media screen and (max-width: 1366px){#guideDialog {width: 640px} #guideDialog .project-type-img.more-type {height: 108px;}}
  @media screen and (max-width: 1366px){[lang^='en'] #guideDialog .row .col-xs-4 {height: 200px;} [lang^='de'] #guideDialog .row .col-xs-4 {height: 200px;} [lang^='fr'] #guideDialog .row .col-xs-4 {height: 200px;}}
  </style>
  <div class='modal-content'>
    <div class='modal-body'>
      <button class="close" data-dismiss="modal">x</button>
      <h2 class='text-center'><?php echo $lang->project->chooseProgramType; ?></h2>
      <div class='row'>
        <?php
        $tab = $from == 'global' ? 'project' : $app->tab;
        if($tab == 'product') $tab = 'project';

        $hasWaterfall     = helper::hasFeature('waterfall');
        $hasWaterfallPlus = helper::hasFeature('waterfallplus');
        ?>
        <?php if($config->systemMode == 'PLM'):?>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=ipd&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='ipd' src='{$config->webRoot}theme/default/images/main/ipd.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->ipd; ?></h3>
            <p><?php echo $lang->project->ipdTitle; ?></p>
          </div>
        </div>
        <?php endif;?>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=scrum&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='scrum' src='{$config->webRoot}theme/default/images/main/scrum.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->scrum; ?></h3>
            <p><?php echo $lang->project->scrumTitle; ?></p>
          </div>
        </div>
        <?php if($hasWaterfall):?>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=waterfall&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='waterfall' src='{$config->webRoot}theme/default/images/main/waterfall.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->waterfall; ?></h3>
            <p><?php echo $lang->project->waterfallTitle; ?></p>
          </div>
        </div>
        <?php endif;?>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=kanban&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='kanban' src='{$config->webRoot}theme/default/images/main/kanban.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->kanban;?></h3>
            <p><?php echo $lang->project->kanbanTitle;?></p>
          </div>
        </div>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=agileplus&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='agileplus' src='{$config->webRoot}theme/default/images/main/agileplus.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->agileplus;?></h3>
            <p><?php echo $lang->project->agileplusTitle;?></p>
          </div>
        </div>
        <?php if($hasWaterfallPlus):?>
        <div class='col col-xs-4'>
          <div class='project-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=waterfallplus&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID"), "<img class='project-type-img' data-type='waterfallplus' src='{$config->webRoot}theme/default/images/main/waterfallplus.png'>", '', "data-app='{$tab}' class='createButton'")?>
            <h3><?php echo $lang->project->waterfallplus;?></h3>
            <p><?php echo $lang->project->waterfallplusTitle;?></p>
          </div>
        </div>
        <?php endif;?>
	<?php if($config->systemMode != 'PLM'):?>
	<div class='col col-xs-4'>
          <div class='project-type text-center'>
            <div class='project-type-img more-type'><span class='text-muted'><?php echo $lang->project->moreModelTitle;?></span></div>
          </div>
        </div>
	<?php endif;?>
      </div>
    </div>
  </div>
</div>
<script>
$('.createButton').on('click', function()
{
    $.closeModal();
});
</script>
<?php if(!$isAjaxRequest): ?>
<?php include "../../common/view/footer.html.php"?>
<?php endif;?>
