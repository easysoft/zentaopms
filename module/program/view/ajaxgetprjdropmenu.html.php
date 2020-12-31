<?php js::set('programID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
    <?php echo $normalProjectsHtml;?>
    </div>
    <div class="col-footer">
      <?php echo html::a(helper::createLink('program', 'PRJBrowse', 'programID=0&status=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->project->doneProjects;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
    <div class='list-group'>
      <?php echo $closedProjectsHtml;?>
    </div>
  </div>
</div>
<style>
.tree li.has-list.open:before {border-left: none;}
#projectTree li {padding: 0 0 0 8px;}
#projectTree li.has-list {padding-left: 20px;}
#projectTree li > a {display: block; padding: 5px 0; border-radius: 2px; padding-left: 6px; height: 30px;}
#projectTree li > a > span {display: inline-block;}
#projectTree li > a > span + span {margin-left: 8px;}
#projectTree li.selected > a {background-color: #E8F3FC;}
#projectTree li.selected > a > span.title {color: #006AF1;}
#projectTree .label-id {border-color: #cbd0db; color: #7d8599}
#projectTree .label.label-type {background: #fff; border: 1px solid #7d8599; color:#7d8599}

#projectTree li > a > span.title {color: #3C4353; white-space: nowrap; max-width: 60%; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;}
#projectTree li > a > span.user {color: #838a9d;}
#projectTree li > a > span.user > .icon-person {font-size: 14px; position: relative; top: -1px; color: #a6aab8}

#projectTree li > .list-toggle {transform: rotate(0deg); width: 16px; height: 16px; border: 4px solid #a6aab8; border-radius: 2px; top: 7px;}
#projectTree li > .list-toggle:before {content: ' '; display: block; position: absolute; border: 1px solid #a6aab8; top: 2px; left: -3px; right: -3px; bottom: 2px; min-width: 0; transition: all .2s;}
#projectTree li > .list-toggle:hover:before,
#projectTree li > .list-toggle:hover {border-color: #006AF1;}
#projectTree li.open > .list-toggle {width: 12px; height: 12px; top: 9px; background-color: #a6aab8; border-width: 3px; left: 3px;}
#projectTree li.open > .list-toggle:before {border: none; height: 2px; width: 6px; left: 0; top: 2px; background: #fff;}
#projectTree li.open > .list-toggle:hover {background: #006AF1;}

#projectTree ul > li:after {display: block; position: absolute; content: ' '; border-top: 1px dashed #cbd0db; top: 15px; left: -12px; z-index: 1; width: 10px;}
#projectTree ul > li:before,
#projectTree ul > li.has-list:before {background: none; content: ' '; display: block; position: absolute; width: auto; height: auto; border: none; border-left: 1px dashed #cbd0db; top: -13px; bottom: 12px; left: -12px;}
#projectTree ul > li:last-child:before {bottom: auto; height: 29px;}
#projectTree ul > li:first-child:before {top: -9px;}
#projectTree ul > li.has-list:first-child:before {top: -13px;}
#projectTree ul > li.tree-single-item:before {height: 23px;}
#projectTree ul > li.has-list:after {width: 14px;}
#projectTree ul > li.item-meas a.selected{color: #0c64eb;}
#showClosed1 + label{color: #3c4353}
</style>
<script>
$('#projectTree').tree();
</script>
