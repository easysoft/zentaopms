</div><?php /* end '.outer' in 'header.html.php'. */ ?>
<script>$.initSidebar()</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>

<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>

<?php if($onlybody != 'yes'):?>
</main><?php /* end '#wrap' in 'header.html.php'. */ ?>
<footer id='footer'>
  <div class="container">
    <?php commonModel::printBreadMenu($this->moduleName, isset($position) ? $position : ''); ?>
    <div id='poweredBy'>
      <small class='muted'><?php echo $lang->designedByAIUX;?></small> &nbsp;
      <a href='<?php echo $lang->website;?>' target='_blank'><i class='icon-zentao'></i> <?php echo $lang->zentaoPMS . $config->version;?></a> &nbsp;
      <?php echo $lang->proVersion;?>
      <?php commonModel::printClientLink();?>
    </div>
  </div>
</footer>
<div id="noticeBox"><?php echo $this->loadModel('score')->getNotice(); ?></div>
<script>
<?php if(!isset($config->global->browserNotice)):?>
browserNotice = '<?php echo $lang->browserNotice?>'
function ajaxIgnoreBrowser(){$.get(createLink('misc', 'ajaxIgnoreBrowser'));}
$(function(){showBrowserNotice()});
<?php endif;?>

<?php 
$this->app->loadLang('chat');
$xxInstalled = $this->app->user->account . 'installed';
?>
<?php if(isset($config->xxserver->installed) and $config->xuanxuan->turnon and !isset($config->xxclient->$xxInstalled) and $config->global->flow == 'full'):?>
result = confirm('<?php echo $lang->chat->xxClientConfirm?>');
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=client'), function()
{
    if(result) $('#footer #poweredBy a:last-child').click();
});
<?php endif;?>

<?php if(!isset($config->xxserver->noticed) and $this->app->user->admin and $config->global->flow == 'full'):?>
result = confirm('<?php echo $lang->chat->xxServerConfirm?>');
$.get(createLink('admin', 'ajaxSaveXXStatus', 'type=noticed'), function()
{
    if(result) location.href=createLink('admin', 'xuanxuan');
});
<?php endif;?>

/* Alert get message. */
$(function()
{
    var windowBlur = false;
    if(window.Notification)
    {
        window.onblur  = function(){windowBlur = true;}
        window.onfocus = function(){windowBlur = false;}
    }
    setInterval(function()
    {
        $.get(createLink('message', 'ajaxGetMessage', "windowBlur=" + (windowBlur ? '1' : '0')), function(data)
        {
            if(!windowBlur)
            {
                $('#noticeBox').append(data);
                adjustNoticePosition();
            }
            else
            {
                if(data)
                {
                    if(typeof data == 'string') data = $.parseJSON(data);
                    if(typeof data.message == 'string') notifyMessage(data.message);
                }
            }
        });
    }, 5 * 60 * 1000);
})

<?php if(!empty($config->sso->redirect)):?>
<?php
$ranzhiAddr = $config->sso->addr;
$ranzhiURL  = substr($ranzhiAddr, 0, strrpos($ranzhiAddr, '/sys/'));
?>
<?php if(!empty($ranzhiURL)):?>
$(function(){ redirect('<?php echo $ranzhiURL?>', '<?php echo $config->sso->code?>'); });
<?php endif;?>
<?php endif;?>
</script>

<?php endif;?>
<?php
if($this->loadModel('cron')->runable()) js::execute('startCron()');
if(isset($pageJS)) js::execute($pageJS);  // load the js for current page.

/* Load hook files for current page. */
$extPath      = $this->app->getModuleRoot() . '/common/ext/view/';
$extHookRule  = $extPath . 'footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
</body>
</html>
