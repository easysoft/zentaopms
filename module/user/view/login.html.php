<?php
/**
 * The html template file of login method of user module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 */
include '../../common/view/header.lite.html.php';
?>
<style>
html{background-color:#06294e;}
body{background-image:url(theme/default/images/main/loginbg.png); background-position:center top; background-repeat:no-repeat;}
table, tr, td, th, input{ border:none;}
.rowhead{width:300px; font-weight:normal; font-size:14px; text-align:right; color:#fff;}
.text-2 {width:150px; height:22px; background-color:#a4c5e0; border:1px solid #035793; font-size:16px; font-weight:bold}
.select-2 {width:150px}
.pt-20px {padding-top:20px}
.pt-200px{padding-top:200px}
.pt-25px {padding-top:25px}
#debugbar, .helplink{display:none}
#welcome{background:none; border:none; color:#FFF; padding-top:8px;}
#poweredby{color:#fff; margin-top:40px; text-align:center; line-height:1}
#poweredby a {color:#fff}
.button-s, .button-c {padding:3px 5px 3px 5px; width:80px; font-size:14px; font-weight:bold}
</style>
<script language='Javascript'>
$(document).ready(function(){
    $('#account').focus();
})
</script>
<div class='yui-d0 pt-200px'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4'> 
      <caption id='welcome'><?php printf($lang->welcome, $app->company->name);?></caption>
      <tr>
        <td class='rowhead pt-10px'><?php echo $lang->user->account;?>：</td>  
        <td class='pt-10px'><input class='text-2' type='text' name='account' id='account' /></td>
      </tr>  
      <tr>
        <td class='rowhead'><?php echo $lang->user->password;?>：</td>  
        <td><input class='text-2' type='password' name='password' /></td>
      </tr>
      <tr>
        <td class='rowhead'>Language:</td>  
        <td><?php echo html::select('lang', $config->langs, $this->app->getClientLang(), 'class=select-2 onchange=selectLang(this.value)');?></td>
      </tr>
      <tr>
        <td colspan='2' class='a-center pt-20px'>
        <?php 
        echo html::submitButton($lang->login);
        if($app->company->guest) echo html::linkButton($lang->user->asGuest, $this->createLink($config->default->module));
        echo html::hidden('referer', $referer);
        ?>
        </td>
      </tr>  
    </table>
    <div class='yui-d0' id='debugbar'><iframe frameborder='0' name='hiddenwin' id='hiddenwin' class='hidden'></iframe></div>
    <div id='poweredby'>
    powered by <a href='http://www.zentao.net' target='_blank'>ZenTaoPMS</a>(<?php echo $config->version;?>). <br />
    <script src='http://www.zentao.net/check.php?v=<?php echo $config->version;?>&s=<?php echo $s;?>'></script>
    </div>
  </form>
</div>  
</body>
</html>
