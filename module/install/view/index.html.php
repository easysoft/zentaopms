<?php
/**
 * The html template file of index method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <div class='btn-group'>
        <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $app->config->langs[$app->cookie->lang];?> <span class='caret'></span></button>
        <ul class='dropdown-menu'>
        <?php
        foreach ($app->config->langs as $key => $value)
        {
            if($key == $app->cookie->lang) continue;
            echo '<li>' . html::a('javascript:selectLang("' . $key . '")', $value) . '</li>';
        }
        ?>
        </ul>
      </div>
    </div>
    <div class='modal-body'>
      <h3><?php echo $lang->install->welcome;?></h3>
      <table>
        <tr><td colspan='2'><?php echo nl2br($lang->install->desc);?></td></tr>
        <tr>
          <td class=''><?php echo nl2br(sprintf($lang->install->links, $config->version));?></td>
          <td class='w-p25'>
            <img src="<?php echo $this->app->getWebRoot() . 'theme/default/images/main/weixin.jpg'?>">
          </td>
        </tr> 
      </table>
    </div>
    <div class='modal-footer'>
      <?php if(isset($latestRelease) and (version_compare($latestRelease->version, $config->version) > 0)):?>
      <div class='mgb-20'><?php vprintf($lang->install->newReleased, $latestRelease);?></div>
      <div class='form-group'>
        <?php 
        echo html::a($latestRelease->url, $lang->install->seeLatestRelease, '_blank', "class='btn btn-success'");
        echo "<span class='text-muted'> &nbsp; " . $lang->install->or . ' &nbsp; </span>';
        echo html::a($this->createLink('install', 'step1'), $lang->install->keepInstalling, '', "class='btn btn-primary'");
        ?>
      </div>
      <?php else:?>
      <div class='form-group'>
        <?php echo html::a($this->createLink('install', 'step1'), $lang->install->start, '', "class='btn btn-primary'");?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
