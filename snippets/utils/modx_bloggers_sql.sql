/*
Создаем представление под названием modx_blogger_activations
Названия столбцов:
blogger_id, username, fullname, phone, email, promo_codes_expires_count, promo_codes_live_count,activations_count
 где id - id ресурса активации
*/

SELECT
	modUser.id AS blogger_id,
	modUser.username AS username,
	modUserAttribute.email AS email,
	modUserAttribute.fullname AS fullname,
	modUserAttribute.mobilephone AS phone,
	(SELECT
		COUNT(modResource.id) AS promo_codes_expires_count
	 	FROM `modx_site_content` AS modResource
		LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
		LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_end_date` on (`TVpc_end_date`.`contentid` = modResource.id and `TVpc_end_date`.`tmplvarid` = 4)
	 	WHERE modResource.parent = 5135 AND `TVpc_end_date`.value < NOW() AND `TVpc_blogger`.value = modUser.id)
		AS promo_codes_expires_count,
	(SELECT
		COUNT(modResource.id) AS promo_codes_live_count
	 	FROM `modx_site_content` AS modResource
		LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
		LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_end_date` on (`TVpc_end_date`.`contentid` = modResource.id and `TVpc_end_date`.`tmplvarid` = 4)
	 	WHERE modResource.parent = 5135 AND `TVpc_end_date`.value > NOW() AND `TVpc_blogger`.value = modUser.id)
		AS promo_codes_live_count,
	(SELECT
		COUNT(modResource.id) AS activations_count
		FROM `modx_site_content` AS modResource
		LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
		WHERE modResource.parent = 4837 AND `TVpc_blogger`.value = modUser.id)
		AS activations_count
FROM `modx_users` AS modUser
LEFT JOIN `modx_user_attributes` AS modUserAttribute ON modUser.id = modUserAttribute.internalKey;



/* it works */

select
	COUNT(modResource.id) AS activation_count,
	COUNT(DISTINCT(TVpromo_action.value)) AS pa_count,
	ifnull(TVpc_blogger.value,'') AS blogger_id
from `modx_site_content` AS `modResource`
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
	LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpromo_action` ON `TVpromo_action`.`contentid` = modResource.id AND `TVpromo_action`.`tmplvarid` = 15
where (`modResource`.`parent` = 4837) and (`modResource`.`published` = 1) and (`modResource`.`deleted` = 0)
group by blogger_id


/*
// TODO
// присоединить даные блоггера из таблицы атрибутов
 */

/* it works - получаем число активаций по заданному блоггеру
это будет подзапрос
*/
SELECT
	COUNT(modResource.id) as activations_count
FROM `modx_site_content` AS modResource
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
WHERE modResource.parent = 4837 AND `TVpc_blogger`.value = 7


/* it works - получаем число промокодов по заданному блоггеру
это будет подзапрос
*/
SELECT
	COUNT(modResource.id) as promo_codes_expired_count
FROM `modx_site_content` AS modResource
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
WHERE modResource.parent = 5135 AND `TVpc_blogger`.value = 7 AND `TVpc_end_date`.value < NOW()

/* it works - получаем число промокодов активных по заданному блоггеру
это будет подзапрос
*/
SELECT
	COUNT(modResource.id) as promo_codes_live_count
FROM `modx_site_content` AS modResource
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
	left join `modx_site_tmplvar_contentvalues` `TVpc_end_date` on (`TVpc_end_date`.`contentid` = modResource.id and `TVpc_end_date`.`tmplvarid` = 4)
WHERE modResource.parent = 5135 AND `TVpc_end_date`.value > NOW() AND `TVpc_blogger`.value = 7

/* it works - получаем число промокодов активных по заданному блоггеру
это будет подзапрос
*/
SELECT
	COUNT(modResource.id) as promo_codes_count
FROM `modx_site_content` AS modResource
	left join `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
	left join `modx_site_tmplvar_contentvalues` `TVpc_end_date` on (`TVpc_end_date`.`contentid` = modResource.id and `TVpc_end_date`.`tmplvarid` = 4)
WHERE modResource.parent = 5135 AND `TVpc_end_date`.value IS NOT NULL AND `TVpc_blogger`.value = 7



(SELECT COUNT(*)
FROM `modx_site_content`
LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_id`
ON (`TVpc_id`.`contentid` = `modx_site_content`.id
AND `TVpc_id`.`tmplvarid` = 14
)
WHERE `TVpc_id`.value = modResource.id) AS pc_activations_count