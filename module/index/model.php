<?php
/**
 * The model file of index module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php
class indexModel extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    function stat()
    {
        return get_included_files();
    }
}
