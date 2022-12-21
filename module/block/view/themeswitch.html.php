<style>
.themeSwitch .table-row {border-top: 0px !important;}
.themeSwitch .theme-container {border-collapse:separate; border-spacing:10px; border: none;}
#theme-default {background: url('/theme/default/images/guide/theme_default.png') no-repeat;}
#theme-blue {background: url('/theme/default/images/guide/theme_blue.png') no-repeat;}
#theme-green {background: url('/theme/default/images/guide/theme_green.png') no-repeat;}
#theme-red {background: url('/theme/default/images/guide/theme_red.png') no-repeat;}
#theme-pink {background: url('/theme/default/images/guide/theme_pink.png') no-repeat;}
#theme-blackberry {background: url('/theme/default/images/guide/theme_blackberry.png') no-repeat;}
#theme-classic {background: url('/theme/default/images/guide/theme_classic.png') no-repeat;}
#theme-purple {background: url('/theme/default/images/guide/theme_purple.png') no-repeat;}
.theme {margin-left: 10px; color: #FFF; border: none; height: 103px; background-size: 100% !important; cursor:pointer; border-radius: 4px; position: relative;}
.theme:hover {box-shadow: 0 0 14px rgba(0, 0, 0, 0.4);}
.theme-text {width: 100px;position: absolute; bottom: 21px; left: 50%; transform: translateX(-50%);}
.theme-text .icon {padding-right: 10px;}
</style>
<script>
$(function()
{
    var $block        = $('#block<?php echo $blockID;?>');
    var themePosition = "<?php echo 'themePosition-' . $blockID;?>";
    $block.find('.themeSwitch > .theme').click(function()
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
    <div class='theme col-3' id="<?php echo 'theme-' . $themeKey;?>" data-value="<?php echo $themeKey;?>">
      <?php $hidden = $app->cookie->theme == $themeKey ? '' : 'hidden';?>
      <?php $active = $app->cookie->theme == $themeKey ? 'active' : '';?>
      <div class='theme-text text-center <?php echo $active;?>'><i class="icon icon-check-circle <?php echo $hidden;?>"></i><?php echo $themeName;?></div>
    </div>
  <?php if($i % 4 == 3) echo "</div>";?>
  <?php $i ++;?>
<?php endforeach;?>
</div>
