<?php
/**
 * The install view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table class='table-1'>
  <caption><?php echo $header->title;?></caption>
  <tr>
    <td valign='middle'>
      <?php if($error):?>
        <?php 
        echo "<h3 class='error'>{$lang->extension->installFailed}</h3>"; 
        echo "<p>$error</p>";
        echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href');
        ?>
      <?php elseif(isset($license)):?>
        <?php
        echo "<h3 class='a-center'>{$lang->extension->license}</h3>"; 
        echo html::textarea('license', $license) . '<br />';
        echo '<h3>' . html::a($agreeLink, $lang->extension->agreeLicense) . '</h3>';
        ?>
      <?php else:?>
        <?php
        if($downloadedPackage) echo  "<h3 class='success'>{$lang->extension->successDownloadedPackage}</h3>";
        echo "<h3 class='success'>{$lang->extension->successCopiedFiles}</h3>";
        echo '<ul>';
        foreach($files as $fileName => $md5)
        {
            echo "<li>$fileName</li>";
        }
        echo '</ul>';
        echo "<h3 class='success'>{$lang->extension->successInstallDB}</h3>";
        echo "<h1 class='a-center'>{$lang->extension->installFinished}</h1>";
        echo "<p class='a-center'>" . html::commonButton($lang->extension->viewInstalled, 'onclick=parent.location.href="' . inlink('browse') . '"') . '</p>';
        ?>
      <?php endif;?>
    </td>
  </tr>
</table>
</body>
</html>
