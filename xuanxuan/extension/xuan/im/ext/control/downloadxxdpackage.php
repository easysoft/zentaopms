<?php
helper::import('../../control.php');
class myIm extends im
{
    public function downloadXxdPackage($xxdFileName)
    {
        $xxdFileName  = basename($xxdFileName);
        return parent::downloadXxdPackage($xxdFileName);
    }
}
