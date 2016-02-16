/*
Создаем представление под названием modx_blg_promo_actions
Названия столбцов:
act_id,
pagetitle,
blg_begin_date,
blg_activations,
blg_incom
*/

--запрос промоакций
SELECT modPromoactions.id AS act_id,
		modPromoactions.pagetitle AS pagetitle,
		modTVpa_code.value AS pa_code
FROM `modx_site_content` AS modPromoactions
LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpa_code ON modTVpa_code.contentid = modPromoactions.id AND modTVpa_code.tmplvarid = 6
WHERE modPromoactions.parent = 28

-- запрос даты извлечения первого промо-кода, извлеченного данным блогером
SELECT modPromocodes.id AS pc_id,
	MIN(TVpc_start_date.value) AS pc_start_date,
	modPromocodes.pagetitle AS pagetitle,
	SUM(modPromocodes.bonus_sum) AS bonus_sum
FROM `modx_promocodes` AS modPromocodes
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_start_date` on (`TVpc_start_date`.`contentid` = modPromocodes.id and `TVpc_start_date`.`tmplvarid` = 8)
WHERE TVpc_start_date.value IS NOT NULL
AND modPromocodes.blogger_id = 43 AND modPromocodes.pa_id = 31



SELECT modPromocodes.id AS pc_id,
	modPromocodes.pa_id AS pa_id,
	modPromocodes.blogger_id AS blogger_id,
	TVpc_start_date.value AS pc_start_date,
	modPromocodes.pagetitle AS pagetitle,
	modPromocodes.bonus_sum AS bonus_sum,
	modPromocodes.pc_activations_count AS pc_activations_count
FROM `modx_promocodes` AS modPromocodes
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_start_date` on (`TVpc_start_date`.`contentid` = modPromocodes.id and `TVpc_start_date`.`tmplvarid` = 8)
WHERE modPromocodes.blogger_id > 0