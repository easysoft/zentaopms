<?php
/**
 * The computeburn view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Fu Jia <fujia@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
foreach($burns as $burn)
{
    echo $burn->project . "\t" . $burn->projectName . "\t" . $burn->date . "\t" . $burn->left . "\n";
}
?>
