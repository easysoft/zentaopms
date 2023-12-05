<?php
/**
 * The show script view file of testcase of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     testcase
 * @version     $Id: showscript.html.php 4723 2022-07-28 08:47:29Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.lite.html.php';
css::import($jsRoot . 'misc/highlight/styles/code.css');
js::import($jsRoot  . 'misc/highlight/highlight.pack.js');
?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <span title='<?php echo $case->title?>'><?php echo $case->title;?></span>
    </h2>
  </div>
  <div class="main-col repoCode main">
    <div class="content panel">
      <pre class="cpp"><?php echo $case->script;?></pre>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
