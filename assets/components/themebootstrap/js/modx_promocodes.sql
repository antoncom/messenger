/*
id,pagetitle,pc_start_date,pc_end_date,pa_id,blogger_id,pc_activations_count
*/

select
	modResource.id AS id,
	modResource.pagetitle AS pagetitle,
	ifnull(TVpc_start_date.value,'') AS pc_start_date,
	ifnull(TVpc_end_date.value,'') AS pc_end_date,
	IFNULL(TVpromo_action.value, '') AS pa_id,
	ifnull(TVpc_blogger.value,'') AS blogger_id,
	(SELECT COUNT(*)
	 FROM `modx_site_content`
		 LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_id`
			 ON (`TVpc_id`.`contentid` = `modx_site_content`.id
					 AND `TVpc_id`.`tmplvarid` = 14
			 )
	 WHERE `TVpc_id`.value = modResource.id) AS pc_activations_count
from `modx_site_content` AS `modResource`
	left join `modx_site_tmplvar_contentvalues` `TVpc_end_date` on (`TVpc_end_date`.`contentid` = modResource.id and `TVpc_end_date`.`tmplvarid` = 4)
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
	left join `modx_site_tmplvar_contentvalues` `TVpc_start_date` on (`TVpc_start_date`.`contentid` = modResource.id and `TVpc_start_date`.`tmplvarid` = 8)
	left join `modx_site_tmplvar_contentvalues` `TVpc_activations_count` on (`TVpc_activations_count`.`contentid` = modResource.id and `TVpc_activations_count`.`tmplvarid` = 10)
	LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpromo_action` ON `TVpromo_action`.`contentid` = modResource.id AND `TVpromo_action`.`tmplvarid` = 15
where (`modResource`.`parent` = 5135) and (`modResource`.`published` = 1) and (`modResource`.`deleted` = 0)



sql_calc_found_rows

/* Подзапрос подсчета активаций промо-кода */
SELECT
	COUNT(modActivation.blogger_id) AS activations_count
FROM `modx_blogger_activations` AS `modActivation`
WHERE ( modActivation.blogger_id = 7 )



AND `TVpc_id`.`value` = modResource.id AS `pc_activations_count`