<?php
/**
 * The install view file of extension module of ZenTaoPMS.
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
        <span class='prefix'><?php echo html::icon('cog');?></span>
        <strong><?php echo $title;?></strong>
        <small>
        <?php
        if(isset($license) and $upgrade == 'yes') printf($lang->extension->upgradeVersion, $this->post->installedVersion, $this->post->upgradeVersion);
        ?>
        </small>
      </h2>
    </div>
    <?php if($error):?>
    <div class='text-left'>
      <div class='container mw-500px'>
        <h5 class='text-danger'><?php printf($lang->extension->installFailed, $installType);?></h5>
        <p class='text-danger'><?php echo $error;?></p>
      </div>
      <hr>
      <?php echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href', 'btn btn-primary');?>
    </div>
    <?php elseif(isset($license)):?>
    <div class='text-left'>
      <div class='content text-center'>
        <h5><?php echo $lang->extension->license;?></h5>
        <p><?php echo html::textarea('license', $license, "class='form-control' rows='15'");?></p>
        <?php echo html::a($agreeLink, $lang->extension->agreeLicense, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <?php else:?>
    <div class='text-left'>
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
  </div>
</div>
</body>
</html>
