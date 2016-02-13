/*
Создаем представление под названием modx_activations
Названия столбцов:
id, abonent, act_date, pc_id, blogger_id, pa_id, alias, bonus_set
*/

SELECT
  `modResource`.`id`,
  `modResource`.`pagetitle` as `abonent`,
  IFNULL(`TVactivation_date`.`value`, '') AS `act_date`,
  IFNULL(`TVpcode`.`value`, '') AS `pc_id`,
  IFNULL(`TVblogger`.`value`, '') AS `blogger_id`,
  IFNULL(`TVpromo_action`.`value`, '') AS `pa_id`,
  `modResource`.`alias` as `alias`,
  IFNULL(`TVbonus_set`.`value`, '') AS `bonus_set`
FROM `modx_site_content` AS `modResource`
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpromo_action` ON `TVpromo_action`.`contentid` = `modResource`.`id` AND `TVpromo_action`.`tmplvarid` = 15
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVactivation_date` ON `TVactivation_date`.`contentid` = `modResource`.`id` AND `TVactivation_date`.`tmplvarid` = 12
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpcode` ON `TVpcode`.`contentid` = `modResource`.`id` AND `TVpcode`.`tmplvarid` = 14
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVblogger` ON `TVblogger`.`contentid` = `modResource`.`id` AND `TVblogger`.`tmplvarid` = 5
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVbonus_set` ON `TVbonus_set`.`contentid` = `modResource`.`id` AND `TVbonus_set`.`tmplvarid` = 44
WHERE  ( `modResource`.`parent` = 4837 AND `modResource`.`published` = 1 AND `modResource`.`deleted` = 0 );