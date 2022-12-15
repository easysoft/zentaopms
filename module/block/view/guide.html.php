<style>
.block-guide .panel-body {padding: 0; height: 286px;}
.block-guide .panel-body .table-row {height: 100%; border-top: 1px solid #EEE;}
.block-guide .col-nav {border-right: 1px solid #EBF2FB; width: 170px; padding: 0; background: #F3F6FA;}
.block-guide .nav-secondary > li {position: relative;}
.block-guide .nav-secondary > li.active {background: #FFF;}
.block-guide .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-guide .nav-secondary > li > a:hover {box-shadow: none;}
.block-guide .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-guide .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-guide .nav-secondary > li.active > a:first-child:before {content: ''; display: block; left: -1px; height: 100%; top: 0px; width: 2px; background: #2E7FFF; position: absolute;}
.block-guide .nav-secondary > li > a > span.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-guide .nav-secondary > li a:hover {background: #FFF;}
.block-guide .nav-secondary > li.active > a > span.btn-view,
.block-guide .nav-secondary > li:hover > a > span.btn-view {opacity: 1;}
.block-guide .nav-secondary > li.switch-icon {display: none;}
.block-guide .tab-pane .app-qrcode {padding: 10px 20px;}
.block-guide .tab-pane .app-qrcode .col-md-12 {padding-left: 0; padding-top: 10px;}
.block-guide .tab-pane .app-qrcode .qrcode-down img {padding-top: 24px; width: 120px;}
.block-guide .tab-pane .app-qrcode .qrcode-down .text-primary {padding-top: 10px;}
<?php if(common::checkNotCN()):?>
.block-guide .col-nav {width: 215px;}
<?php endif;?>
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
          <a href="###" title="<?php echo $tabName?>" data-target='<?php echo "#tab3{$blockNavId}Content{$tab}";?>' data-toggle="tab">
            <?php echo $tabName;?>
            <span class='btn-view'><i class='icon-arrow-right text-primary'></i><span>
          </a>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class="col tab-content">
      <div class="tab-pane fade active in" id='<?php echo "tab3{$blockNavId}Contentflowchart";?>'>
      <?php include 'flowchart.html.php';?>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentsystemMode";?>'></div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentvisionSwitch";?>'></div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentthemeSwitch";?>'></div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}Contentpreference";?>'></div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentdownloadClient";?>'></div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentdownloadMoblie";?>'>
        <div class='table-row app-qrcode'>
          <div class="col-4">
            <div class='col'><h4><?php echo $lang->block->zentaoapp->commom;?></h4></div>
            <div class="col dataTitle"><?php echo $lang->block->zentaoapp->desc;?></div>
            <div class='col pull-left col-md-12'>
              <div class="pull-left col-md-7">
                <div class="col-md-4"><?php echo html::image($config->webRoot . 'theme/default/images/guide/app_index.png');?></div>
                <div class="col-md-4"><?php echo html::image($config->webRoot . 'theme/default/images/guide/app_execution.png');?></div>
                <div class="col-md-4"><?php echo html::image($config->webRoot . 'theme/default/images/guide/app_statistic.png');?></div>
              </div>
              <div class="pull-left col-md-4 text-center qrcode-down">
                <div><?php echo html::image($config->webRoot . 'theme/default/images/main/mobile_qrcode.png');?></div>
                <div class="text-center text-primary"><?php echo $lang->block->zentaoapp->downloadTip;?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
