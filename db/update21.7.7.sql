UPDATE `zt_risk` SET `pri` = 3 WHERE `pri` = 'middle';
UPDATE `zt_risk` SET `pri` = 1 WHERE `pri` = 'high';
UPDATE `zt_risk` SET `pri` = 2 WHERE `pri` = 'low';

UPDATE `zt_opportunity` SET `pri` = 3 WHERE `pri` = 'middle';
UPDATE `zt_opportunity` SET `pri` = 1 WHERE `pri` = 'high';
UPDATE `zt_opportunity` SET `pri` = 2 WHERE `pri` = 'low';
