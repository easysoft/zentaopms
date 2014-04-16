<?php if($config->debug):?>
<div id='debugPanel'>
  <header>
    <div class='actions pull-right'><i id='debugHandle' class='icon-chevron-up'></i></div>
    <i class='icon icon-terminal'></i> <strong id='debugTitle'><?php echo $lang->debug;?></strong>
  </header>
  <div id='debugContent'></div>
  <div id='debugIframeTip' class='hidden'>#<strong id='debugIframeTipId'></strong> <span id='debugIframeTipTime'></span> The following content from iframe.</div>
  <iframe style='width:580px; height: 400px ' frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin'></iframe>
</div>
<style>
/* debug panel */
#debugPanel {width: 160px; height: 40px; background: rgba(255,255,255,0.8); float: left; transition:all 0.3s;}
#debugPanel.show {width: 600px; height: 540px; position: relative; top: -500px; box-shadow: 2px 2px 5px rgba(0,0,0,0.25); background: #fff}
body > #debugPanel, body > #debugPanel.show {position: fixed; bottom: 0; top: inherit;} 
#debugPanel > header {color: #fff; padding: 0 15px; background: rgba(0,0,0,0.8); cursor: pointer; transition: all 0.5s; line-height: 40px}
#debugPanel.with-content > header {background: #D2322D}
#debugPanel > header > .icon {display: inline-block; border-radius: 4px; background: #ddd; background: rgba(255,255,255,0.3); padding: 2px 5px; margin-right: 5px;}
#debugHandle {color: #ddd}
#debugHandle:hover {color: #fff}
#debugContent, #debugIframeTip {padding: 10px; line-height: 20px; padding-bottom: 0;}
#debugContent .cell {margin-bottom: 10px;}
#debugContent .cell > header, #debugIframeTip {color: #ccc; font-size: 12px; line-height: 16px}
#debugContent .cell > .content {border-radius: 4px; padding: 3px 5px}
#debugIframeTip {padding-top: 0;}
#debugPanel.with-iframe-content #hiddenwin {border: 1px solid #ddd; border-radius: 4px; margin: 0 10px}
</style>
<script>
function debug(title)
{
    var id = 1;

    this.setTitle = function(title)
    {
        $('#debugTitle').html(title);
    };

    this.setContent = function(content, silence)
    {
      $('#debugContent').append("<div class='cell'><header><strong>#" + (id++) + '</strong> ' + (new Date().toLocaleString()) + "</header><pre class='content'>" + content + '</pre></div>');
      if(!silence) $('#debugPanel').addClass('with-content');
    };

    this.init = function()
    {
        var dp = $('#debugPanel'), dph = $('#debugHandle');
        $('#debugPanel > header').click(function()
        {
            dp.toggleClass('show');
            var showed = dp.hasClass('show');
            dph.toggleClass('icon-chevron-up', !showed).toggleClass('icon-chevron-down', showed);
        });

        var frame = document.getElementById('hiddenwin');
        frame.onload = frame.onreadystatechange = function()
        {
            if (this.readyState && this.readyState != 'complete') return;
            try
            {
                var $frame = $(window.frames['hiddenwin'].document);
                if($frame.find('body').html() != '')
                {
                    $('#debugIframeTipId').text(id++);
                    $('#debugIframeTipTime').text(new Date().toLocaleString());
                    $('#debugIframeTip').removeClass('hidden');

                    dp.addClass('with-content').addClass('with-iframe-content');
                }
            }
            catch(e){}
        }
    };

    this.init();
    this.setTitle(title);
    this.setContent('debug ready.', true);
}

$(function()
{
    window.debug = new debug('Debug');
});
</script>
<?php else:?>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php endif;?>
