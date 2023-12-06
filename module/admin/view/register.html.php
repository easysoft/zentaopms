<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#mainContent table {width: 95%;}
.input-group .input-group-btn .popover {font-size: 12px;}
.input-group-btn .btn {border-left: 0px}
.input-group:last-child .input-group-addon {border-right: 0px;}
</style>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->admin->registerNotice->caption;?></span></span>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div <?php if(common::hasPriv('admin', 'bind')) echo "class='col-md-6'";?>>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->admin->registerNotice->common?></strong></div>
      <form class='mw-600px' method="post" target="hiddenwin">
        <table align='center' class='table table-form'>
          <tr>
            <th class='w-100px'><?php echo $lang->user->account;?></th>
            <td>
              <div class="required required-wrapper"></div>
              <?php echo html::input('account', '', "class='form-control' placeholder='{$lang->admin->registerNotice->lblAccount}'");?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->user->realname;?></th>
            <td><div class="required required-wrapper"></div><?php echo html::input('realname', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->user->company;?></th>
            <td><div class="required required-wrapper"></div><?php echo html::input('company', $register->company, "class='form-control'");?></td>
          </tr>
          <?php if(empty($config->isINT)):?>
          <tr>
            <th><?php echo $lang->user->mobile;?></th>
            <td>
              <div class="required required-wrapper"></div>
              <div class='input-group'>
                <?php echo html::input('mobile', '', "class='form-control'");?>
                <span class='input-group-btn'><?php echo html::a(inlink('ajaxSendCode', 'type=mobile'), $lang->admin->getCaptcha, '', "id='mobileSender' class='btn'")?></span>
                <span class='input-group-addon' style='border:0px;background:none'></span>
                <span class='input-group-addon'><?php echo $lang->admin->captcha?></span>
                <?php echo html::input('mobileCode', '', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->user->email;?></th>
            <td>
              <div class="required required-wrapper"></div>
              <div class='input-group'>
                <?php echo html::input('email', $register->email, "class='form-control'");?>
                <span class='input-group-btn'><?php echo html::a(inlink('ajaxSendCode', 'type=email'), $lang->admin->getCaptcha, '', "id='mailSender' class='btn'")?></span>
                <span class='input-group-addon' style='border:0px;background:none'></span>
                <span class='input-group-addon'><?php echo $lang->admin->captcha?></span>
                <?php echo html::input('emailCode', '', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->user->password;?></th>
            <td>
              <div class="required required-wrapper"></div>
              <?php echo html::password('password1', '', "class='form-control' placeholder='{$lang->admin->registerNotice->lblPasswd}'");?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->user->password2;?></th>
            <td>
              <div class="required required-wrapper"></div>
              <?php echo html::password('password2', '', "class='form-control'");?>
            </td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::submitButton($lang->admin->registerNotice->submit) . html::hidden('sn', $sn) . html::hidden('bindSite', common::getSysURL());?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <?php if(common::hasPriv('admin', 'bind')):?>
  <div class='col-md-6'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->admin->registerNotice->bind?></strong></div>
      <form class='mw-400px' method="post" target="hiddenwin" action='<?php echo inlink('bind', "from=$from")?>'>
        <table align='center' class='table table-form'>
          <tr>
            <th class='w-100px'><?php echo $lang->user->account;?></th>
            <?php
            $account = zget($config->global, 'community', '');
            if($account == 'na') $account = '';
            ?>
            <td><?php echo html::input('account', $account, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->user->password;?></th>
            <td><?php echo html::password('password', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='2' class="text-center"><?php echo html::submitButton() . html::hidden('sn', $sn) . html::hidden('site', common::getSysURL());?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('#mobileSender').click(function()
    {
        var data = {mobile: $('#mobile').val()};
        var url  = $(this).attr('href');

        $.post(url, data, function(response)
        {
            if(response.result == 'success')
            {
                $('#mobileSender').popover({trigger:'manual', content:response.message, placement:'right'}).popover('show');
                $('#mobileSender').next('.popover').addClass('popover-success');
                function distroy(){$('#mobileSender').popover('destroy')}
                setTimeout(distroy,2000);
            }
            else
            {
                bootbox.alert(response.message);
            }
        }, 'json')
        return false;
    })
    $('#mailSender').click(function()
    {
        var data = {email: $('#email').val()};
        var url  = $(this).attr('href');

        $.post(url, data, function(response)
        {
            if(response.result == 'success')
            {
                $('#mailSender').popover({trigger:'manual', content:response.message, placement:'right'}).popover('show');
                $('#mailSender').next('.popover').addClass('popover-success');
                function distroy(){$('#mailSender').popover('destroy')}
                setTimeout(distroy,2000);
            }
            else
            {
                bootbox.alert(response.message);
            }
        }, 'json')
        return false;
    })  
});
</script>
<?php include '../../common/view/footer.html.php';?>
