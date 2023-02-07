<style>
.themeSwitch .table-row {border-top: 0px !important;}
.themeSwitch .theme-container:first-child {padding-top: 1%;}
@media screen and (max-width: 1366px){.themeSwitch .theme-container:first-child {padding-top: 3%;} .theme-container + .theme-container {padding-top: 3%;}}
.themeSwitch .theme-container {border-collapse:separate; border-spacing:10px; border: none;}
.theme {margin-left: 10px; color: #FFF; border: none; background-size: 100% !important; cursor:pointer; border-radius: 4px; position: relative;}
.theme:hover {box-shadow: 0 0 14px rgba(0, 0, 0, 0.4);}
.theme-text {width: 100%;position: absolute; bottom: 6%; left: 50%; transform: translateX(-50%);}
.theme-text .icon {padding-right: 10px;}
.theme-text.active {left: 45%;}
div.theme-default {background: #3785ff;}
div.theme-blue {background: #2b80ff;}
div.theme-green {background: #248f83;}
div.theme-red {background: #f34a5a;}
div.theme-pink {background: #f7889c;}
div.theme-blackberry {background: #304269;}
div.theme-classic {background: #114f8e;}
div.theme-purple {background: #9958dc;}
</style>
<script>
$(function()
{
    var $block        = $('#block<?php echo $blockID;?>');
    var themePosition = "<?php echo 'themePosition-' . $blockID;?>";
    $block.find('.themeSwitch .theme').click(function()
    {
        var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop || $block.find('#guideBody').offset().top;
        localStorage.setItem(themePosition, scrollTop);

        selectTheme($(this).attr('data-value'));
    });
})
</script>
<div class='themeSwitch'>
<?php $i = 0;?>
<?php foreach($lang->block->themes as $themeKey => $themeName):?>
  <?php if($i % 4 == 0) echo "<div class='col-12 table-row theme-container'>"?>
    <div class="theme col-3 theme-<?php echo $themeKey;?>" data-value="<?php echo $themeKey;?>">
      <?php echo html::image($config->webRoot . "theme/default/images/guide/theme_{$themeKey}.png");?>
      <?php $hidden = $app->cookie->theme == $themeKey ? '' : 'hidden';?>
      <?php $active = $app->cookie->theme == $themeKey ? 'active' : '';?>
      <div class='theme-text text-center <?php echo $active;?>'><i class="icon icon-check-circle <?php echo $hidden;?>"></i><?php echo $themeName;?></div>
    </div>
  <?php if($i % 4 == 3) echo "</div>";?>
  <?php $i ++;?>
<?php endforeach;?>
</div>
