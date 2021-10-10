ALTER TABLE `zt_project` drop COLUMN `product`, drop COLUMN `statge`, drop COLUMN `storyConcept`;
ALTER TABLE `zt_product` drop COLUMN `storyConcept`;
UPDATE zt_todo SET assignedTo=account,assignedBy=account,assignedDate=`date` WHERE assignedTo='';
