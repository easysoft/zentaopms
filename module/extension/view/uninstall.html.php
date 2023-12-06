<?php
/**
 * The uninstall view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
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
            echo join('<br />', $removeCommands);
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
