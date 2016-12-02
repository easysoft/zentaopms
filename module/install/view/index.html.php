<?php
/**
 * The html template file of index method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
            <img src="<?php echo $this->app->getWebRoot() . 'theme/default/images/main/weixin.jpg'?>" width='200' height='200'>
          </td>
        </tr> 
        <tr>
          <td colspan='2'>
            <h5><?php echo $lang->install->promotion?></h5>
            <div class='row'>
              <div class='col-md-4'>
                <a class="card ad" href="http://www.chanzhi.org" target="_blank">
                  <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/chanzhi.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/chanzhi.png'?>" alt=""></div>
                  <div class="card-reveal">
                    <h5 class="card-heading"><?php echo $lang->install->chanzhi->name?></h5>
                    <div class="card-content"><?php echo $lang->install->chanzhi->desc?></div>
                  </div>
                </a>
              </div>
              <div class='col-md-4'>
                <a class="card ad" href="http://www.ranzhico.com" target="_blank">
                  <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/ranzhi.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/ranzhi.png'?>" alt=""></div>
                  <div class="card-reveal">
                    <h5 class="card-heading"><?php echo $lang->install->ranzhi->name?></h5>
                    <div class="card-content"><?php echo $lang->install->ranzhi->desc?></div>
                  </div>
                </a>
              </div>
              <div class='col-md-4'>
                <a class="card ad" href="http://www.zdoo.com" target="_blank">
                  <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/zdoo.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/zdoo.png'?>" alt=""></div>
                  <div class="card-reveal">
                    <h5 class="card-heading"><?php echo $lang->install->zdoo->name?></h5>
                    <div class="card-content"><?php echo $lang->install->zdoo->desc?></div>
                  </div>
                </a>
              </div>
            </div>
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
        <?php echo html::a(inlink('license'), $lang->install->start, '', "class='btn btn-primary'");?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
