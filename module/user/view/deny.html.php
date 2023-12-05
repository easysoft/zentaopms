<?php
/**
 * The html template file of deny method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: deny.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
include '../../common/view/header.lite.html.php';
?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'><strong><?php echo $app->user->account, ' ', $lang->user->deny;?></strong></div>
    <div class='modal-body'>
      <div class='alert with-icon alert-pure'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'>
        <?php
        if($denyType == 'nopriv')
        {
            $this->app->loadLang('group');
            $groupPriv  = isset($lang->resource->$module->$method) ? $lang->resource->$module->$method : $method;
            $moduleName = isset($lang->$module->common)  ? $lang->$module->common  : $module;
            $methodName = isset($lang->$module->$groupPriv) && is_string($lang->$module->$groupPriv) ? $lang->$module->$groupPriv : $method;

            if($module == 'execution' && $method == 'gantt') $methodName = $methodName->common;

            /* find method name if method is lowercase letter. */
            if(!isset($lang->$module->$method))
            {
                $tmpLang = array();
                foreach($lang->$module as $key => $value) $tmpLang[strtolower($key)] = $value;
                $methodName = isset($tmpLang[$method]) ? $tmpLang[$method] : $method;
            }

            printf($lang->user->errorDeny, $moduleName, $methodName);
        }

        if($denyType == 'noview')
        {
            $menuName = isset($lang->$menu->common) ? $lang->$module->common : $menu;
            if(isset($lang->menu->$menu)) list($menuName) = explode('|', $lang->menu->$menu);
            printf($lang->user->errorView, $menuName);
        }
        ?>
        </div>
      </div>
    </div>
    <div class='modal-footer'>
    <?php
    $isOnlybody = helper::inOnlyBodyMode();
    unset($_GET['onlybody']);
    echo html::a($this->createLink('my'), $lang->my->common, ($isOnlybody ? '_parent' : ''), "class='btn show-in-app' data-app='my'");
    if($refererBeforeDeny) echo html::a(helper::safe64Decode($refererBeforeDeny), $lang->user->goback, ($isOnlybody ? '_parent' : ''), "class='btn'");
    echo html::a($this->createLink('user', 'logout', "referer=" . helper::safe64Encode($denyPage)), $lang->user->relogin, ($isOnlybody ? '_parent' : ''), "class='btn btn-primary'");
    ?>
    </div>
  </div>
</div>
<?php js::set('isOnlybody', $isOnlybody);?>
<?php js::set('indexLink', helper::createLink('my', 'index'));?>
</body>
</html>
