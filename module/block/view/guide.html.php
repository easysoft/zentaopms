<style>
.block-guide .panel-body {padding: 0; height: 286px;}
.block-guide .tutorialBtn {margin-right: 10px;}
.block-guide .tutorialBtn, .block-guide a.tutorialBtn:hover {color: #FFF; background: #FF9F46;}
.block-guide .flowchart {padding: 20px 24px 20px 24px;}
.block-guide .panel-body .table-row {height: 100%; border-top: 1px solid #EEE;}
.block-guide .col-nav {border-right: 1px solid #EBF2FB; width: 130px; padding: 0;}
.block-guide .nav-secondary > li {position: relative;}
.block-guide .nav-secondary > li.active {background: #FFF;}
.block-guide .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-guide .nav-secondary > li > a:hover {box-shadow: none;}
.block-guide .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-guide .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-guide .nav-secondary > li.active > a:first-child:before {content: ''; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #2E7FFF; position: absolute;}
.block-guide .nav-secondary > li > a > span.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-guide .nav-secondary > li:hover > a > span.btn-view {opacity: 1;}
.block-guide .nav-secondary > li.switch-icon {display: none;}
.block-guide .tab-pane h4 {color: #0B0F18;}
.block-guide .tab-pane .dataTitle {font-size: 12px; color: #5E626D;}
.block-guide .tab-pane .app-client .col,
.block-guide .tab-pane .app-qrcode .col {width: 100%;}
.block-guide .tab-pane .app-client .menu {margin-top: 10px;}
.block-guide .tab-pane .os-and-desc {padding-left: 0;}
.block-guide .tab-pane .app-client .tree-menu li {line-height: 56px; border: 1px solid #EDEEF2; padding-left: 0; white-space: nowrap}
.block-guide .tab-pane .app-client .tree-menu li:nth-child(2) {border-top: none; border-bottom: none;}
.block-guide .tab-pane .app-client .tree-menu li a.iframe {display: flex;}
.block-guide .tab-pane .app-client .tree-menu li .avatar {background: #E6EAF1; width: 24px; height: 24px; margin-top: 16px; margin-right: 5px;}
.block-guide .tab-pane .app-client .tree-menu li .avatar img {padding: 6px;}
.block-guide .tab-pane .app-client .client-desc {padding-top: 12px;}
.block-guide .tab-pane .app-client .client-desc img {height: 195px;}
.block-guide .tab-pane .app-qrcode .col-md-12 {padding-left: 0; padding-top: 10px;}
.block-guide .tab-pane .app-qrcode .qrcode-down img {padding-top: 16px;}
.block-guide .tab-pane .app-qrcode .qrcode-down .text-primary {padding-top: 10px;}
.block-guide .tab-pane {height: 100%;}
<?php if(common::checkNotCN()):?>
.block-guide .col-nav {width: 215px;}
<?php endif;?>
</style>
<script>
<?php $blockNavId = 'nav-' . $blockID; ?>
$(function()
{
    var $block             = $('#block<?php echo $blockID;?>');
    var $nav               = $block.find('#<?php echo $blockNavId;?>');
    var visionPosition     = "<?php echo 'visionPosition-' . $blockID;?>";
    var themePosition      = "<?php echo 'themePosition-' . $blockID;?>";
    var systemModePosition = "<?php echo 'systemModePosition-' . $blockID;?>";
    $nav.on('click', '.switch-icon', function(e)
    {
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a[data-toggle="tab"]').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
        e.preventDefault();
    });

    $nav.find('li').click(function()
    {
        if($(this).attr('id') == 'flowchart')
        {
            $('.block-guide .tutorialBtn').removeClass('hidden');
        }
        else
        {
            $('.block-guide .tutorialBtn').addClass('hidden');
        }

        if($(this).attr('id') !== 'visionSwitch') removePosition(visionPosition);
        if($(this).attr('id') !== 'themeSwitch') removePosition(themePosition);
        if($(this).attr('id') !== 'systemMode') removePosition(systemModePosition);
    })

    if(localStorage.getItem(visionPosition) && Number(localStorage.getItem(visionPosition)))
    {
        var scrollTopNum = Number(localStorage.getItem(visionPosition));
        $block.scrollTop('#guideBody', scrollTopNum);
        $block.find('#visionSwitch > a').click();
        window.scrollTo(0, scrollTopNum);
    }
    else if(localStorage.getItem(themePosition) && Number(localStorage.getItem(themePosition)))
    {
        var scrollTopNum = Number(localStorage.getItem(themePosition));
        $block.scrollTop('#guideBody', scrollTopNum);
        $block.find('#themeSwitch > a').click();
        window.scrollTo(0, scrollTopNum);
    }
    else if(localStorage.getItem(systemModePosition) && Number(localStorage.getItem(systemModePosition)))
    {
        var scrollTopNum = Number(localStorage.getItem(systemModePosition));
        $block.scrollTop('#guideBody', scrollTopNum);
        $block.find('#systemMode > a').click();
        window.scrollTo(0, scrollTopNum);
    }
    else
    {
        $block.find('#flowchart > a').click();
    }

    /**
     * Remove position.
     *
     * @param  string $key
     * @access public
     * @return void
     */
    function removePosition(key)
    {
        localStorage.getItem(key) && localStorage.removeItem(key);
    }
});
</script>

<div class="panel-body" id="guideBody">
  <div class="table-row">
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover guide-nav" id='<?php echo $blockNavId;?>'>
        <?php foreach($lang->block->guideTabs as $tab => $tabName):?>
        <?php if(strpos($tab, 'download') !== false and (!isset($config->xxserver->installed) or !$config->xuanxuan->turnon)) continue;?>
        <?php if($tab == 'downloadMoblie' and common::checkNotCN()) continue;?>
        <?php if(($tab == 'preference' or $tab == 'systemMode') and $this->config->vision == 'lite') continue;?>
        <?php if($tab == 'systemMode' and !common::hasPriv('custom', 'mode')) continue;?>
        <?php if($tab == 'preference' and !common::hasPriv('my', 'preference')) continue;?>
        <?php if($tab == 'visionSwitch' and !strpos($app->user->visions, ',')) continue;?>
        <li id="<?php echo $tab;?>">
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
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentsystemMode";?>'>
        <?php include 'systemmodeswitch.html.php';?>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentvisionSwitch";?>'>
        <?php include 'visionswitch.html.php';?>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentthemeSwitch";?>'>
        <?php include 'themeswitch.html.php';?>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}Contentpreference";?>'>
        <?php include 'preference.html.php';?>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentdownloadClient";?>'>
        <div class='table-row app-client'>
          <div class="col-4">
            <div class='col'><h4><?php echo $lang->block->zentaoclient->common;?></h4></div>
            <div class="col dataTitle"><?php echo $lang->block->zentaoclient->desc;?></div>
            <div class='col pull-left col-md-12 os-and-desc'>
              <nav class="menu pull-left col-md-3" data-ride="menu">
                <ul class="tree tree-menu" data-ride="tree">
                  <?php foreach($lang->block->zentaoclient->edition as $edition => $editionName):?>
                  <li><?php echo html::a($this->createLink('misc', 'downloadClient', "action=getPackage&os=$edition", '', true), '<div class="avatar has-img avatar-circle">' . html::image($config->webRoot . "theme/default/images/guide/edition_{$edition}.png") . "</div> $editionName", '', 'class="iframe"');?></li>
                  <?php endforeach;?>
                </ul>
              </nav>
              <div class="pull-left col-md-9 client-desc">
                <?php echo html::image($config->webRoot . 'theme/default/images/guide/' . (common::checkNotCN() ? 'client_en.png' : 'client_cn.png'));?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id='<?php echo "tab3{$blockNavId}ContentdownloadMoblie";?>'>
        <div class='table-row app-qrcode'>
          <div class="col-4">
            <div class='col'><h4><?php echo $lang->block->zentaoapp->common;?></h4></div>
            <div class="col dataTitle"><?php echo $lang->block->zentaoapp->desc;?></div>
            <div class='col pull-left col-md-12'>
              <div class="pull-left col-md-8 os-and-desc">
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
