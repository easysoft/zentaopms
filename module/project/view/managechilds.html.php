<?php
/**
 * The manage child product view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: managechilds.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['project']);?></span>
      <strong><?php echo $lang->project->manageChilds;?></strong>
    </div>
  </div>
  <form method='post'>
    <table align='center' class='table table-form'> 
      <tr>
        <td>
        <?php
        echo html::checkbox("childs", $projects, $childProjects);
        ?>
        </td>
      </tr>
      <tr><td class='text-center'><input type='submit' name='submit' class='btn btn-primary' /></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
