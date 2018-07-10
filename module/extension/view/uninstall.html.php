<?php
/**
 * The uninstall view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
        <strong title='<?php echo $title;?>'><?php echo $title;?></strong>
        <small class='text-danger'><?php echo html::icon('cog');?> <?php echo $lang->extension->uninstall;?></small>
      </h2>
    </div>
    <?php if(isset($confirm) and $confirm == 'no'):?>
    <div class='alert alert-pure with-icon'>
      <i class='icon-exclamation-sign'></i>
      <div class='content'>
      <?php
        echo "<p class='waring'>{$lang->extension->confirmUninstall}";
        echo html::a(inlink('uninstall', "extension=$code&confirm=yes"), $lang->extension->uninstall, '', "class='btn'");
        echo "</p>";
      ?>
      </div>
    </div>
    <?php elseif(!empty($error)):?>
    <div class='alert alert-pure with-icon'>
      <i class='icon-exclamation-sign'></i>
      <div class='content'>
      <?php
        echo "<h3 class='error'>" . $lang->extension->uninstallFailed . "</h3>"; 
        echo "<p>$error</p>";
      ?>
      </div>
    </div>
    <?php else:?>
    <div class='alert alert-pure with-icon'>
      <i class='icon-check-circle'></i>
      <div class='content'>
        <?php
        echo "<h3>{$title}</h3>";
        if(!empty($backupFile)) echo "<p>" . sprintf($lang->extension->backDBFile, $backupFile) . '</p>';
        if($removeCommands)
        {
            echo "<p class='strong'>{$lang->extension->unremovedFiles}</p>";
            echo join($removeCommands, '<br />');
        }
        echo "<p class='text-center'>" . html::commonButton($lang->extension->viewAvailable, 'onclick=parent.location.href="' . inlink('browse', 'type=available') . '"') . '</p>';
        ?>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
</body>
</html>
