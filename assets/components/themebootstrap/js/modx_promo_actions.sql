/*
Создаем представление под названием modx_promo_actions
Названия столбцов:
act_id,
pagetitle,
pa_code,
pa_start,
pa_end,
bonus,
activations,
pc_active,
pc_free
*/

SELECT
	modResource.id AS act_id,
	modResource.pagetitle AS pagetitle,
	modTVpa_code.value AS pa_code,
	FLOOR(UNIX_TIMESTAMP(modTVpa_start_date.value)) AS pa_start,
	FLOOR(UNIX_TIMESTAMP(modTVpa_end_date.value)) AS pa_end,
	modTVbonus_size.value AS bonus,
	COUNT(viewAct.id) AS activations,
	(
	SELECT COUNT(modPromocode.id) as promo_codes_active
	FROM `modx_site_content` AS modPromocode
		LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpromocode
		ON modTVpromocode.tmplvarid = 15
		AND modTVpromocode.contentid = modPromocode.id
		JOIN `modx_site_tmplvar_contentvalues` AS modTVpromocodeFired ON modTVpromocodeFired.tmplvarid = 4 AND modTVpromocodeFired.value IS NOT NULL
		AND modTVpromocodeFired.contentid = modPromocode.id
	WHERE modPromocode.parent = 5135 AND modTVpromocodeFired.value > NOW() AND modTVpromocode.value = modResource.id
	) AS pc_active,
	(
	SELECT COUNT(modPromocodeFree.id)
	FROM `modx_site_content` AS modPromocodeFree
		LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpromocodeFree
		ON modTVpromocodeFree.tmplvarid = 15
		AND modTVpromocodeFree.contentid = modPromocodeFree.id
		LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVPromocodeFreeFired ON modTVPromocodeFreeFired.tmplvarid = 4
		AND modTVPromocodeFreeFired.contentid = modPromocodeFree.id
	WHERE modPromocodeFree.parent = 5135 AND modTVpromocodeFree.value = modResource.id AND modTVPromocodeFreeFired.value IS NULL
	) AS pc_free
FROM `modx_site_content` AS modResource
LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpa_code ON modTVpa_code.contentid = modResource.id AND modTVpa_code.tmplvarid = 6
LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpa_start_date ON modTVpa_start_date.contentid = modResource.id AND modTVpa_start_date.tmplvarid = 2
LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVpa_end_date ON modTVpa_end_date.contentid = modResource.id AND modTVpa_end_date.tmplvarid = 3
LEFT JOIN `modx_site_tmplvar_contentvalues` AS modTVbonus_size ON modTVbonus_size.contentid = modResource.id AND modTVbonus_size.tmplvarid = 17
LEFT OUTER JOIN `modx_activations` AS viewAct ON modResource.id = viewAct.pa_id
WHERE modResource.parent = 28
GROUP BY modResource.id;