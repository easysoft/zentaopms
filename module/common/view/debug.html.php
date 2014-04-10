<div id='debugPanel'>
  <header>
    <div class='actions pull-right'><i id='debugHandle' class='icon-chevron-up'></i></div>
    <i class='icon icon-terminal'></i> <?php echo $lang->debug;?>
  </header>
  <iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin'></iframe>
</div>
<style>
/* debug panel */
#debugPanel {width: 120px; background: rgba(255,255,255,0.8); float: left; transition:all 0.3s;}
#debugPanel.show {width: 600px; height: 540px; position: relative; top: -500px; box-shadow: 2px 2px 5px rgba(0,0,0,0.25); background: #fff}
#debugPanel > header {color: #fff; padding: 0 15px; background: rgba(0,0,0,0.8); cursor: pointer;}
#debugPanel > header > .icon {display: inline-block; border-radius: 4px; background: #ddd; background: rgba(255,255,255,0.5); color: #000; padding: 2px 5px; margin-right: 5px;}
#debugHandle {color: #ddd}
#debugHandle:hover {color: #fff}
</style>
<script>
$(function()
{
    var dp = $('#debugPanel'), dph = $('#debugHandle');
    $('#debugPanel > header').click(function()
    {
        dp.toggleClass('show');
        var showed = dp.hasClass('show');
        dph.toggleClass('icon-chevron-up', !showed).toggleClass('icon-chevron-down', showed);
    });
});
</script>
