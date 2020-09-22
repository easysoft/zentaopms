<?php js::set('programID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
    <?php
    echo $normalProjectsHtml;
    ?>
    </div>
    <div class="col-footer">
      <?php echo html::a(helper::createLink('program', 'PRJBrowse', 'programID=0&status=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->project->doneProjects?><i class='icon icon-angle-right'></i></a>
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
#programTree li {padding: 0 0 0 8px;}
#programTree li.has-list {padding-left: 20px;}
#programTree li > a {display: block; padding: 5px 0; border-radius: 2px; padding-left: 6px; height: 30px;}
#programTree li > a > span {display: inline-block;}
#programTree li > a > span + span {margin-left: 8px;}
#programTree li.selected > a {background-color: #E8F3FC;}
#programTree li.selected > a > span.title {color: #006AF1;}
#programTree .label-id {border-color: #cbd0db; color: #7d8599}
#programTree .label.label-type {background: #fff; border: 1px solid #7d8599; color:#7d8599}

#programTree li > a > span.title {color: #3C4353; white-space: nowrap; max-width: 60%; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;}
#programTree li > a > span.user {color: #838a9d;}
#programTree li > a > span.user > .icon-person {font-size: 14px; position: relative; top: -1px; color: #a6aab8}

#programTree li > .list-toggle {transform: rotate(0deg); width: 16px; height: 16px; border: 4px solid #a6aab8; border-radius: 2px; top: 7px;}
#programTree li > .list-toggle:before {content: ' '; display: block; position: absolute; border: 1px solid #a6aab8; top: 2px; left: -3px; right: -3px; bottom: 2px; min-width: 0; transition: all .2s;}
#programTree li > .list-toggle:hover:before,
#programTree li > .list-toggle:hover {border-color: #006AF1;}
#programTree li.open > .list-toggle {width: 12px; height: 12px; top: 9px; background-color: #a6aab8; border-width: 3px; left: 3px;}
#programTree li.open > .list-toggle:before {border: none; height: 2px; width: 6px; left: 0; top: 2px; background: #fff;}
#programTree li.open > .list-toggle:hover {background: #006AF1;}

#programTree ul > li:after {display: block; position: absolute; content: ' '; border-top: 2px solid #cbd0db; top: 14px; left: -12px; z-index: 1; width: 10px;}
#programTree ul > li:before,
#programTree ul > li.has-list:before {background: none; content: ' '; display: block; position: absolute; width: auto; height: auto; border: none; border-left: 2px solid #cbd0db; top: -13px; bottom: 12px; left: -12px;}
#programTree ul > li:last-child:before {bottom: auto; height: 29px;}
#programTree ul > li:first-child:before {top: -9px;}
#programTree ul > li.has-list:first-child:before {top: -13px;}
#programTree ul > li.tree-single-item:before {height: 23px;}
#programTree ul > li.has-list:after {width: 14px;}
#programTree ul > li.item-meas a.selected{color: #0c64eb;}
#showClosed1 + label{color: #3c4353}
</style>
<script>
$('#projectTree').tree();
</script>
