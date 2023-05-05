<style>
.block-projectdoc .nav-stacked {overflow:auto; height:220px; max-height:220px; }
.block-projectdoc .panel-heading {border-bottom:1px solid #ddd;}
.block-projectdoc .panel-body {padding-top: 0; height:240px; padding-right:0px; padding-bottom:0px;}
.block-projectdoc .tab-content {padding-right:0px;}
.block-projectdoc .tab-pane {max-height:220px; overflow:auto;}
.block-projectdoc table.tablesorter th{border-bottom:0px !important;}
.block-projectdoc .tile {margin-bottom: 30px;}
.block-projectdoc .tile-title {font-size: 18px; color: #A6AAB8;}
.block-projectdoc .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-projectdoc .col-nav {border-right: 1px solid #EBF2FB; width: 210px; padding: 8px 0 0 0;}
.block-projectdoc .nav-secondary > li {position: relative;}
.block-projectdoc .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-projectdoc .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-projectdoc .nav-secondary > li.active > a:first-child {color: #3C4353; box-shadow: none;}
.block-projectdoc .nav-secondary > li.active > a:first-child:hover,
.block-projectdoc .nav-secondary > li.active > a:first-child:focus,
.block-projectdoc .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-projectdoc .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-projectdoc .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-projectdoc .nav-secondary > li:hover > a.btn-view {opacity: 1;}
.block-projectdoc .nav-secondary > li.active > a.btn-view {box-shadow: none;}
.block-projectdoc .nav-secondary > li.switch-icon {display: none;}
.block-projectdoc.block-sm .nav-stacked {height:auto;}
.block-projectdoc.block-sm .panel-body {padding: 45px 8px 10px 8px; position: relative; border-radius: 3px; height:275px;}
.block-projectdoc.block-sm .panel-body > .table-row,
.block-projectdoc.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-projectdoc.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-projectdoc.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-projectdoc.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-projectdoc.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5; padding:0px; margin-top:5px;}
.block-projectdoc.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-projectdoc.block-sm .nav-secondary > li {display: none;}
.block-projectdoc.block-sm .nav-secondary > li.switch-icon,
.block-projectdoc.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-projectdoc.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-projectdoc.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0, 0, 0, 0.07);}
.block-projectdoc.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-projectdoc.block-sm .nav-secondary > li > a:before {display: none;}
.block-projectdoc.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-projectdoc.block-sm .nav-secondary > li.active > a:first-child:before {display: none}
.block-projectdoc.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-projectdoc.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-projectdoc.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.1);}

.block-projectdoc .data {width: 40%; text-align: left; padding: 10px 0px; font-size: 14px; font-weight: 700;}
.block-projectdoc .dataTitle {width: 60%; text-align: right; padding: 10px 0px; font-size: 14px;}
.block-projectdoc .executionName {padding: 2px 10px; font-size: 14px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;}
.block-projectdoc .lastIteration {padding-top: 6px;}

.forty-percent {width: 40%;}
html[lang="de"] .block-projectdoc .c-user{width: 105px;}

.block-projectdoc #projectType {position: absolute;top: 8px;left: 120px;}
.block-projectdoc #projectType .btn {border:0px;}
.block-projectdoc #projectType .btn:hover {background-color:#f5f5f5;}

.block-projectdoc .table .c-title > .doc-title {display: inline-block; overflow: hidden; background: transparent; padding-right:0px;}
.block-projectdoc .table .c-title > span.doc-title {line-height: 0; vertical-align: inherit;}
.block-projectdoc .table .c-title[data-status=draft] > .doc-title {max-width: calc(100% - 35px);}
.block-projectdoc .table .c-title > .draft {background-color:rgba(129, 102, 238, 0.12); color:#8166EE;}
</style>
<?php $blockNavId = 'nav-' . uniqid();?>
<?php js::set('emptyProjects', empty($projects));?>
<?php js::set('emptyInvolveds', empty($involveds));?>
<script>
$(function()
{
    if($('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType').length > 1);
    {
        count = $('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType').length;
        $('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType').each(function()
        {
            if(count == 1) return;
            $(this).remove();
            count --;
        })
    }
});

function switch<?php echo "block{$block->id}";?>Project(obj)
{
    var $this = $(obj);
    var $nav = $this.closest('.nav');
    var isPrev = $this.is('.prev');
    var $activeItem = $nav.children('.active');
    var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
    if($next.length > 0)  $next.find('a[data-toggle="tab"]').trigger('click');
    if($next.length == 0) $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
    return false;
}

function change<?php echo "block{$block->id}";?>ProjectType(type)
{
    var hiddenData = type == 'all' ? emptyProjects : emptyInvolveds;
    if(hiddenData)
    {
        $('.block-projectdoc<?php echo "#block{$block->id}";?> .dataBlock').addClass('hidden');
        $('.block-projectdoc<?php echo "#block{$block->id}";?> .block-statistic > .table-empty-tip').removeClass('hidden');
    }
    else
    {
        $('.block-projectdoc<?php echo "#block{$block->id}";?> .dataBlock').removeClass('hidden');
        $('.block-projectdoc<?php echo "#block{$block->id}";?> .block-statistic > .table-empty-tip').addClass('hidden');
    }

    $('.block-projectdoc<?php echo "#block{$block->id}";?> .nav.projects').toggleClass('hidden', type != 'all');
    $('.block-projectdoc<?php echo "#block{$block->id}";?> .nav.involveds').toggleClass('hidden', type != 'involved');
    $('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType .btn').html($('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType [data-type=' + type + ']').html() + " <span class='caret'></span>");
    var name = type == 'all' ? '.block-projectdoc<?php echo "#block{$block->id}";?> .projects' : '.block-projectdoc<?php echo "#block{$block->id}";?> .involveds';
    var $obj = $(name + ' li.active').length > 0 ? $(name + ' .active:first').find('a') : $(name + ' li:not(.switch-icon):first').find('a');
    $(name + ' li').removeClass('active');
    $obj.closest('li').addClass('active');
    $('.block-projectdoc<?php echo "#block{$block->id}";?> .tab-pane').removeClass('active').removeClass('in');
    $('.block-projectdoc<?php echo "#block{$block->id}";?> .tab-pane' + $obj.data('target')).addClass('active').addClass('in');

    $('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType .dropdown-menu li').removeClass('active');
    $('.block-projectdoc<?php echo "#block{$block->id}";?> #projectType .dropdown-menu li a[data-type=' + type + ']').closest('li').addClass('active');
}

</script>
<div class="dropdown" id='projectType'>
  <button class="btn" type="button" data-toggle="dropdown"><?php echo $lang->project->involved;?> <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li class='active'><a href="javascript:change<?php echo "block{$block->id}";?>ProjectType('involved')" data-type='involved'><?php echo $lang->project->involved;?></a></li>
    <li><a href="javascript:change<?php echo "block{$block->id}";?>ProjectType('all')" data-type='all'><?php echo $lang->project->all;?></a></li>
  </ul>
</div>
<?php $hiddenBlock = empty($involveds) ? 'hidden' : '';?>
<?php $hiddenEmpty = empty($involveds) ? '' : 'hidden';?>
<div class="panel-body">
  <div class="table-row block-statistic">
    <div class="table-empty-tip  <?php echo $hiddenEmpty;?>">
      <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
    </div>
    <div class="col col-nav dataBlock <?php echo $hiddenBlock;?>">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover involveds">
        <li class='switch-icon prev'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Project(this)'><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = key($involveds);?>
        <?php foreach($involveds as $project):?>
        <li <?php if($project->id == $selected) echo "class='active' id='activeProject'";?> projectid='<?php echo $project->id;?>'>
          <a href="###" title="<?php echo $project->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$project->id}";?>' data-toggle="tab"><?php echo $project->name;?></a>
          <?php echo html::a(helper::createLink('doc', 'projectSpace', "projectID=$project->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view'");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Project(this)'><i class='icon icon-arrow-right'></i></a></li>
      </ul>
      <ul class="nav nav-stacked nav-secondary scrollbar-hover projects hidden">
        <li class='switch-icon prev'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Project(this)'><i class='icon icon-arrow-left'></i></a></li>
        <?php foreach($projects as $project):?>
        <li projectid='<?php echo $project->id;?>'>
          <a href="###" title="<?php echo $project->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$project->id}";?>' data-toggle="tab"><?php echo $project->name;?></a>
          <?php echo html::a(helper::createLink('doc', 'projectSpace', "projectID=$project->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view'");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a href='###' onclick='switch<?php echo "block{$block->id}";?>Project(this)'><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content dataBlock <?php echo $hiddenBlock;?>">
      <?php foreach($projects as $project):?>
      <div class="tab-pane fade<?php if($project->id == $selected) echo ' active in';?>" id='<?php echo "tab3{$blockNavId}Content{$project->id}";?>'>
        <?php if(isset($docGroup[$project->id])):?>
        <div class="table-row">
          <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter'>
            <thead>
              <tr>
                <th class='c-name'><?php echo $lang->doc->title?></th>
                <th class='c-user'><?php echo $lang->doc->addedBy?></th>
                <th class='c-date'><?php echo $lang->doc->addedDate?></th>
                <th class='c-date'><?php echo $lang->doc->editedDate?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($docGroup[$project->id] as $doc):?>
              <tr>
                <td class='c-title' data-status='<?php echo $doc->status?>'>
                  <?php
                  $docType = zget($config->doc->iconList, $doc->type);
                  $icon    = html::image("static/svg/{$docType}.svg", "class='file-icon'");
                  if(common::hasPriv('doc', 'view'))
                  {
                      echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $icon . $doc->title, '', "title='{$doc->title}' class='doc-title' data-app='{$this->app->tab}'");
                  }
                  else
                  {
                      echo "<span class='doc-title' title='{$doc->title}'>$icon {$doc->title}</span>";
                  }
                  if($doc->status == 'draft') echo "<span class='label label-badge draft'>{$lang->doc->draft}</span>";
                  ?>
                </td>
                <td class='c-user'><?php echo zget($users, $doc->addedBy);?></td>
                <td class='c-date'><?php echo substr($doc->addedDate, 0, 10);?></td>
                <td class='c-date'><?php echo substr($doc->editedDate, 0, 10);?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?php else:?>
        <div class="table-empty-tip">
          <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
        </div>
        <?php endif;?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
