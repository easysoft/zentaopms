<?php
/**
 * The roadmap view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: roadmap.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div>
  <div id='titlebar'><div class='heading'><i class='icon-code-fork'></i> <?php echo $lang->product->roadmap;?></div></div>
  <table class='table-grid w-p100'>
    <?php 
    $years = array_keys($roadmaps);
    echo '<tr class="text-center">';
    foreach($years as $year)
    {
        $year = $year == '0000' ? $lang->future : $year . $lang->year;
        echo "<th><h4>$year</h4></th>";
    }
    echo '</tr>';
    echo '<tr class="text-center text-top">';
    foreach($years as $year)
    {
        echo '<td>';
        foreach($roadmaps[$year] as $key => $roadmap)
        {
            if(isset($roadmap->build))
            {
                echo "<div class='roadmap release'>";
                echo "<h5>" . html::a($this->createLink('release', 'view', "releaseID=$roadmap->id"), $roadmap->name, '_blank') . '</h5>' . $roadmap->date;
            }
            else
            {
                echo "<div class='roadmap plan'>";
                echo "<h5>" . html::a($this->createLink('productplan', 'view', "planID=$roadmap->id"), $roadmap->title, '_blank') . '</h5>' . $roadmap->begin . ' ~ ' . $roadmap->end;
            }
            echo "</div>";
            if(isset($roadmaps[$year][$key + 1])) echo "<h5>{$lang->downArrow}</h5>";
        }
        echo '</td>';
    }
    echo '</tr>';
    ?>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
