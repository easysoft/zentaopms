<?php
declare(strict_types=1);
/**
 * The testtask view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
?>
<style>#imgCutter img{max-width: 97%;}</style>
<script> /*! TangBin: image.ready.js http://www.planeart.cn/?p=1121 */ !function(n){"use strict";n.zui.imgReady=function(){var n=[],l=null,e=function(){for(var l=0;l<n.length;l++)n[l].end?n.splice(l--,1):n[l]();!n.length&&o()},o=function(){clearInterval(l),l=null};return function(o,r,t,u){var c,i,a,d,f,h=new Image;return h.src=o,h.complete?(r.call(h),void(t&&t.call(h))):(i=h.width,a=h.height,h.onerror=function(){u&&u.call(h),c.end=!0,h=h.onload=h.onerror=null},c=function(){d=h.width,f=h.height,(d!==i||f!==a||d*f>1024)&&(r.call(h),c.end=!0)},c(),h.onload=function(){!c.end&&c(),t&&t.call(h),h=h.onload=h.onerror=null},void(c.end||(n.push(c),null===l&&(l=setInterval(e,40)))))}}()}(jQuery); </script>
<?php import($this->app->getWebRoot() . 'js/zui/imgcutter/min.js');?>
<?php import($this->app->getWebRoot() . 'js/zui/imgcutter/min.css');?>
<div id='mainContent'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->user->cropAvatar;?></h2>
    </div>
    <div class="img-cutter fixed-ratio" id="imgCutter" style="max-width: 540px">
      <div class="canvas">
        <?php echo html::image($image->webPath);?>
      </div>
      <div class="form-actions">
        <h5 id='avatarCropTip'><?php echo $lang->user->cropAvatarTip;?></h5>
        <div class="img-cutter-preview"></div>
        <button type="button" class="btn btn-primary img-cutter-submit"><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>
<script>
var $imgCutter = $("#imgCutter");
$imgCutter.imgCutter(
{
    fixedRatio: true,
    minWidth: 48,
    minHeight: 48,
    post: '<?php echo inlink('cropavatar', "image={$image->id}")?>',
    ready: function() {$.zui.ajustModalPosition(); $imgCutter.css('width', $imgCutter.closest('#mainContent').width());},
    done: function(response)
    {
        var account = "<?php echo $this->app->user->account;?>";
        window.parent.$('#main-avatar, #menu-avatar').html('<img src="<?php echo $user->avatar?>"/>');
        window.parent.$('#mainContent>.cell>.main-header>.avatar').html('<img src="<?php echo $user->avatar?>"/>');
        window.parent.$('#mainContent .avatar-' + account).html('<img src="<?php echo $user->avatar?>"/>');
        if(window.parent.$('#main-avatar, #menu-avatar').hasClass('with-text')) window.parent.$('#main-avatar, #menu-avatar').toggleClass('with-text').css('background', 'none');
        location.href = createLink('my', 'profile');
    },
    onSizeError: function(size)
    {
        $('#avatarCropTip').text('<?php echo $lang->user->cropImageTip ?>'.replace('%s', size.width + 'x' + size.height)).addClass('text-danger');
    }
});
</script>
