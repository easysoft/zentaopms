<?php
/**
 * The crop avatar view file of user module of ZentaoPms.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: cropavatar.html.php 8669 2021-01-18 16:58:48Z sunguangming$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<script> /*! TangBin: image.ready.js http://www.planeart.cn/?p=1121 */ !function(n){"use strict";n.zui.imgReady=function(){var n=[],l=null,e=function(){for(var l=0;l<n.length;l++)n[l].end?n.splice(l--,1):n[l]();!n.length&&o()},o=function(){clearInterval(l),l=null};return function(o,r,t,u){var c,i,a,d,f,h=new Image;return h.src=o,h.complete?(r.call(h),void(t&&t.call(h))):(i=h.width,a=h.height,h.onerror=function(){u&&u.call(h),c.end=!0,h=h.onload=h.onerror=null},c=function(){d=h.width,f=h.height,(d!==i||f!==a||d*f>1024)&&(r.call(h),c.end=!0)},c(),h.onload=function(){!c.end&&c(),t&&t.call(h),h=h.onload=h.onerror=null},void(c.end||(n.push(c),null===l&&(l=setInterval(e,40)))))}}()}(jQuery); </script>
<?php js::import($this->app->getWebRoot() . 'js/zui/imgcutter/min.js');?>
<?php css::import($this->app->getWebRoot() . 'js/zui/imgcutter/min.css');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->user->cropAvatar;?></h2>
    </div>
    <table class='table table-form'>
      <tr>
        <td>
          <div class="img-cutter fixed-ratio" id="imgCutter" style="max-width: 100%">
            <div class="canvas">
              <?php echo html::image($image->webPath);?>
            </div>
            <div class="form-actions">
              <h5 id='avatarCropTip'><?php echo $lang->user->cropAvatarTip;?></h5>
              <div class="img-cutter-preview"></div>
              <button type="button" class="btn btn-primary img-cutter-submit"><?php echo $lang->save;?></button>
            </div>
          </div>
        </td>
      </tr>
    </table>
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
        window.parent.$('#main-avatar, #menu-avatar').html('<img src="<?php echo $user->avatar?>"/>');
        window.parent.$('#mainContent .avatar').html('<img src="<?php echo $user->avatar?>"/>');
        if(window.parent.$('#main-avatar, #menu-avatar').hasClass('with-text')) window.parent.$('#main-avatar, #menu-avatar').toggleClass('with-text').css('background', 'none');
        location.href = createLink('my', 'profile');
    },
    onSizeError: function(size)
    {
        $('#avatarCropTip').text('<?php echo $lang->user->cropImageTip ?>'.replace('%s', size.width + 'x' + size.height)).addClass('text-danger');
    }
});
</script>
<?php include '../../common/view/footer.html.php';?>
