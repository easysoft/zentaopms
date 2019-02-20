<?php
/**
 * The certifyztmobile view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-700px' id='checkEmail'>
    <div class='main-header'>
      <h2><?php echo $lang->admin->certifyEmail;?></h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->user->email;?></th>
          <td><?php echo html::input('email', $email, "class='form-control'");?></td>
          <td><?php echo html::a(inlink('ajaxsendcode', 'type=email'), $lang->admin->getCaptcha, '', "id='codeSender' class='btn'");?></td>
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
        var data = {email: $('#email').val()};
        var url  = $(this).attr('href');

        $.post(url, data, function(response)
        {   
            if(response.result == 'success')
            {   
                $('#codeSender').popover({trigger:'manual', content:response.message, placement:'right'}).popover('show');
                $('#codeSender').next('.popover').addClass('popover-success');
                function distroy(){$('#codeSender').popover('destroy')}
                setTimeout(distroy, 2000);
            }   
            else
            {   
                alert(response.message);
            }   
        }, 'json')
        return false;
    })  
});
</script>
<?php include '../../common/view/footer.html.php';?>
