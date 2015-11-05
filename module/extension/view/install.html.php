<?php
/**
 * The install view file of extension module of ZenTaoPMS.
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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon('cog');?></span>
    <strong><?php echo $title;?></strong>
    <small>
    <?php
    if(isset($license) and $upgrade == 'yes') printf($lang->extension->upgradeVersion, $this->post->installedVersion, $this->post->upgradeVersion);
    ?>
    </small>
  </div>
</div>
<?php if($error):?>
<div class='panel panel-body text-left'>
  <div class='container mw-500px'>
    <h5 class='text-danger'><?php sprintf($lang->extension->installFailed, $installType);?></h5>
    <p class='text-danger'><?php echo $error;?></p>
  </div>
  <hr>
  <?php echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href');?>
</div>
<?php elseif(isset($license)):?>
<div class='panel panel-body '>
  <div class='content text-center'>
    <h5><?php echo $lang->extension->license;?></h5>
    <p><?php echo html::textarea('license', $license, "class='form-control' rows='15'");?></p>
    <?php echo html::a($agreeLink, $lang->extension->agreeLicense, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php else:?>
<div class='panel panel-body'>
  <h5 class='text-center mgb-20'><?php echo sprintf($lang->extension->installFinished, $installType);?></h5>
  <div class='text-center'>
    <?php echo html::commonButton($lang->extension->viewInstalled, 'onclick=parent.location.href="' . inlink('browse') . '" class="btn btn-success"');?>
  </div>
  <hr>
  <div class='alert'>
    <?php
    echo "<h5 class='success'>{$lang->extension->successDownloadedPackage}</h5>";
    echo "<h5 class='success'>{$lang->extension->successCopiedFiles}</h5>";
    echo '<ul>';
    foreach($files as $fileName => $md5)
    {
        echo "<li>$fileName</li>";
    }
    echo '</ul>';
    echo "<h5 class='success'>{$lang->extension->successInstallDB}</h5>";
    ?>
  </div>
</div>
<?php endif;?>
</body>
</html>
