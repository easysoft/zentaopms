<?php
/**
 * The index view file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col'>
    <div class='row plug-container'>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'dashboard');?>'>
          <span class='logo'><i class='icon icon-info-dashboard'></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->common;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('backup', 'index');?>'>
          <span class='logo'><i class='icon icon-info-sign'></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->systemInfo;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'dblist');?>'>
          <span class='logo'><img class='logo' src='/theme/default/images/main/db_logo.svg' /></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->dbManagement;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'installLDAP');?>'>
          <span class='logo'><img src='/theme/default/images/main/ldap_logo.jpg' /></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->ldapManagement;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'ossview');?>'>
          <span class='logo'><img src='/theme/default/images/main/minio_logo.svg' /></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->oss->common;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'installSMTP');?>'>
          <span class='logo'><i class='icon icon-envelope-o'></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->SMTP->common;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'configDomain');?>'>
          <span class='logo'><i class='icon icon-globe'></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->domain->common;?></span>
        </a>
      </div>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'configSLB');?>'>
          <span class='logo'><i class='icon icon-treemap'></i></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->SLB->common;?></span>
        </a>
      </div>
      <?php if($config->edition == 'biz'):?>
      <div class='text-center col-xs-6 col-sm-3 col-md-2 col-lg-2'>
        <a class='cell' href='<?php echo helper::createLink('system', 'license');?>'>
          <span class='logo'><img src='/theme/default/images/main/license.svg' /></span><br/>
          <span class='plug-title text-center'><?php echo $lang->system->license;?></span>
        </a>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
