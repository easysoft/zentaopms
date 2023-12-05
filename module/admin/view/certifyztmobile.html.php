<?php
/**
 * The certifyztmobile view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-700px' id='checkMobile'>
    <div class='main-header'>
      <h2><?php echo $lang->admin->certifyMobile;?></h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->user->mobile;?></th>
          <td><?php echo html::input('mobile', $mobile, "class='form-control'");?></td>
          <td><?php echo html::a(inlink('ajaxsendcode', 'type=mobile'), $lang->admin->getCaptcha, '', "id='codeSender' class='btn'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->admin->captcha;?></th>
          <td><?php echo html::input('captcha', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$(function()
{
    $('#codeSender').click(function()
    {   
        var data = {mobile: $('#mobile').val()};
        var url  = $(this).attr('href');

        $.post(url, data, function(response)
        {   
            if(response.result == 'success')
            {   
                $('#codeSender').popover({trigger:'manual', content:response.message, placement:'right'}).popover('show');
                $('#codeSender').next('.popover').addClass('popover-success');
                function distroy(){$('#codeSender').popover('destroy')}
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
