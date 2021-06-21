<?php
include '../../control.php';
class myIm extends im
{
    public function downloadXxdPackage($xxdFileName)
    {
        $xxdFileName  = basename($xxdFileName);
        return parent::downloadXxdPackage($xxdFileName);
    }
}
