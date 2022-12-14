<style>
.block-guide .col-nav {border-right: 1px solid #EBF2FB; width: 170px; padding: 0;}
.block-guide .panel-body {padding-top: 0;}
.block-guide .nav-secondary > li {position: relative;}
.block-guide .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-guide .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-guide .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-guide .nav-secondary > li.active > a:first-child:hover,
.block-guide .nav-secondary > li.active > a:first-child:focus,
.block-guide .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-guide .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-guide .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-guide .nav-secondary > li:hover > a.btn-view {opacity: 1;}
.block-guide .nav-secondary > li.active > a.btn-view {box-shadow: none;}
.block-guide .nav-secondary > li.switch-icon {display: none;}
</style>
<script>
<?php $blockNavId = 'nav-' . uniqid(); ?>
$(function()
{
    var $nav = $('#<?php echo $blockNavId;?>');
    $nav.on('click', '.switch-icon', function(e)
    {
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a[data-toggle="tab"]').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
        e.preventDefault();
    });
});
</script>
<div class="panel-body">
  <div class="table-row">
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover" id='<?php echo $blockNavId;?>'>
        <?php foreach($lang->block->guideTabs as $tab => $tabName):?>
        <?php if(strpos($tab, 'download') !== false and (!isset($config->xxserver->installed) or !$config->xuanxuan->turnon)) continue;?>
        <li <?php if($tab == 'flowchart') echo "class='active' id='activeGuide'";?>>
          <a href="###" title="<?php echo $tabName?>" data-target='<?php echo "#tab3{$blockNavId}Content{$tab}";?>' data-toggle="tab"><?php echo $tabName;?></a>
          <?php echo html::a('###', "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view'");?>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class="col tab-content">
    </div>
  </div>
</div>
