<?php
/**
 * The install view file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->instance->installApp . $cloudApp->alias . $cloudApp->app_version;?></h2>
    </div>
    <form id='installForm' action='<?php echo helper::createLink("instance", "install", "id={$cloudApp->id}", '', '', false);?>' class="cell not-watch load-indicator main-form">
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-80px'><?php echo $lang->instance->name;?></th>
            <td class='w-250px'>
              <div class='input-group'>
                <?php echo html::input('customName', $cloudApp->alias, "class='form-control' maxlength='20'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->instance->domain;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('customDomain', $thirdDomain, "class='form-control' maxlength='20'");?>
                <span class='input-group-addon'><?php echo $this->cne->sysDomain();?></span>
              </div>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div class='advanced'><?php echo html::a("#advanced-settings", $lang->instance->advanceOption . "<i class='icon icon-chevron-double-down'></i>", '', "data-toggle='collapse'");?></div>
      <table class="collapse table table-form" id="advanced-settings">
        <tbody>
          <?php if(strtolower(getenv('ALLOW_SELECT_VERSION')) == 'true' || strtolower(getenv('ALLOW_SELECT_VERSION')) == '1'):?>
          <tr>
            <th class='w-80px'><?php echo $lang->instance->version;?></th>
            <td class='w-250px'>
              <div class='input-group'>
                <?php echo html::select('version', $versionList, '', "class='form-control'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <?php endif;?>
          <?php if(isset($cloudApp->features->ldap) && $this->loadModel('system')->hasSystemLDAP()):?>
          <tr>
            <th class='w-80px'></th>
            <td class='w-250px'>
              <div class='input-group'>
                <?php echo html::checkbox('ldapSnippet', array($this->system->ldapSnippetName() => $lang->instance->enableLDAP));?>
              </div>
            </td>
            <td></td>
          </tr>
          <?php endif;?>
          <?php if(isset($cloudApp->features->mail) && $this->loadModel('system')->isSMTPEnabled()):?>
          <tr>
            <th class='w-80px'></th>
            <td class='w-250px'>
              <div class='input-group'>
                <?php echo html::checkbox('smtpSnippet', array($this->system->smtpSnippetName() => $lang->instance->enableSMTP));?>
              </div>
            </td>
            <td></td>
          </tr>
          <?php endif;?>
          <?php if(isset($cloudApp->dependencies->mysql) and $mysqlList or isset($cloudApp->dependencies->postgresql) and $pgList):?>
          <tr>
            <th class='w-80px'><?php echo $lang->instance->dbType;?></th>
            <td class='w-250px'>
              <div class='input-group'>
                <?php echo html::radio('dbType', $lang->instance->dbTypes, 'sharedDB');?>
              </div>
            </td>
            <td><?php echo html::a('https://www.qucheng.com/book/Installation-manual/app-install-33.html',$lang->instance->howToSelectDB, '_blank');?></td>
          </tr>
          <?php endif;?>
          <?php if(isset($cloudApp->dependencies->mysql) and $mysqlList):?>
          <tr>
            <th></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('dbService', $mysqlList, '', "class='form-control'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <?php elseif(isset($cloudApp->dependencies->postgresql) and $pgList):?>
          <tr>
            <th></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('dbService', $pgList, '', "class='form-control'");?>
              </div>
            </td>
            <td></td>
          </tr>
          <?php endif;?>
        </tbody>
      </table>
      <div class="text-center form-actions"><?php echo html::submitButton($lang->instance->install);?></div>
    </form>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
