<div class='modal-dialog' id='guideDialog'>
  <style>
  #guideDialog {width: 780px}
  #guideDialog h2 {margin: 10px 0 30px 0; font-size: 16px; font-weight: normal}
  #guideDialog h3 {margin: 5px 0; font-size: 20px;}
  #guideDialog .modal-footer {border-top: none; text-align: center; padding-top: 10px; padding-bottom: 40px;}
  #guideDialog .modal-footer .btn + .btn {margin-left: 20px}
  #guideDialog .program-type {padding: 0 40px}
  #guideDialog .program-type-img {width: 280px; border: 1px solid #CBD0DB; border-radius: 2px; margin-bottom: 10px; cursor: pointer; margin-top: 1px}
  #guideDialog .program-type-img:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,0,.25);}
  #guideDialog .program-type.active img {border-color: #006AF1; border-width: 2px; margin-top: 0}
  </style>
  <?php $group = $from == 'PGM' ? "data-group='program'" : "data-group='project'";?>
  <div class='modal-content'>
    <div class='modal-body'>
      <button class="close" data-dismiss="modal">x</button>
      <h2 class='text-center'><?php echo $lang->program->chooseProgramType; ?></h2>
      <div class='row'>
        <div class='col-xs-6'>
          <div class='program-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=scrum&programID=$programID&from=$from"), "<img class='program-type-img' data-type='scrum' src='{$config->webRoot}theme/default/images/main/scrum.png'>", '', $group)?>
            <h3><?php echo $lang->program->scrum; ?></h3>
            <p><?php echo $lang->program->scrumTitle; ?></p>
          </div>
        </div>
        <div class='col-xs-6'>
          <div class='program-type text-center'>
            <?php echo html::a($this->createLink("project", "create", "model=waterfall&programID=$programID&from=$from"), "<img class='program-type-img' data-type='waterfall' src='{$config->webRoot}theme/default/images/main/waterfall.png'>", '', $group)?>
            <h3><?php echo $lang->program->waterfall; ?></h3>
            <p><?php echo $lang->program->waterfallTitle; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
