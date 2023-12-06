<?php
/**
 * The edit domain view file of system module of chandao.net.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php $ldapLinked = false;?>
<?php js::set('errors', $lang->system->errors);?>
<?php js::set('notices', $lang->system->notices);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
  <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
    <h2><?php echo $lang->system->domain->config;?></h2>
    </div>
    <form id='domainForm' class='cell not-watch load-indicator main-form form-ajax'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->system->domain->oldDomain;?></th>
            <td><?php echo $this->loadModel('cne')->sysDomain();?> <span class='with-padding text-danger'><?php echo $lang->system->domain->notReuseOldDomain;?></span></td>
            <td></td>
          </tr>
          <tr>
            <th class='new-domain-label'><?php echo $lang->system->domain->newDomain;?></th>
            <td class='required'>
              <?php echo html::input('customDomain', zget($domainSettings, 'customDomain', ''), "class='form-control' placeholder=''");?>
              <div class='with-padding'>
                <span><?php echo $lang->system->domain->setDNS;?></span>
                <?php echo html::a('https://www.qucheng.com/book/Installation-manual/47.html', $lang->system->domain->dnsHelperLink, '_blank', "class='text-primary'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <h4>
              <?php $domainUsed = false;?>
              <?php echo html::checkbox('https', array('true' => $lang->system->domain->uploadCert), $domainSettings->https, ($domainUsed ? "onclick='return false;'" : ''));?>
              </h4>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <table id='cert-box' class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->system->certPem;?></th>
            <td class='required' colspan='2'><?php echo html::textarea('certPem', zget($domainSettings, 'certPem', ''), "class='form-control' rows='5'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->system->certKey;?></th>
            <td class='required' colspan='2'><?php echo html::textarea('certKey', zget($domainSettings, 'certKey', ''), "class='form-control' rows='5'");?></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td colspan='2' >
              <?php echo html::commonButton($lang->system->verify, "id='validateCertBtn'");?>
              <span class='with-padding' id='validateMsg'></span>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div class='text-center'><?php echo html::commonButton($lang->save, "id='submitBtn'", "btn btn-primary");?></div>
    </form>
  </div>
</div>
<div class="modal fade" id="waiting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <h4><?php echo $lang->system->domain->updateInstancesDomain;?></h4>
        <div>
          <span id='message'><?php echo $lang->system->domain->updating;?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

