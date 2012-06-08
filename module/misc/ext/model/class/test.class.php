<?php
class testMisc extends miscModel
{
    public function hello()
    {
        return parent::hello() . " from ext model<br />";
    }
}
