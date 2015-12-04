/*
Создаем представление под названием modx_blogger_activations
Названия столбцов:
blogger_id,
email,
fullname,
phone,
activations_count
*/

SELECT
	modUser.id AS blogger_id,
	modUserAttribute.email AS email,
	modUserAttribute.fullname AS fullname,
	modUserAttribute.mobilephone AS phone,
	COUNT(viewAct.id) AS activations_count
FROM `modx_users` AS modUser
LEFT OUTER JOIN `modx_activations` AS viewAct ON modUser.id = viewAct.blogger_id
LEFT JOIN `modx_user_attributes` AS modUserAttribute ON modUser.id = modUserAttribute.internalKey
GROUP BY modUser.id

SELECT
	modUser.id AS blogger_id,
	modUserAttribute.email AS email,
	modUserAttribute.fullname AS fullname,
	modUserAttribute.mobilephone AS phone
FROM `modx_users` AS modUser
LEFT JOIN `modx_user_attributes` AS modUserAttribute ON modUser.id = modUserAttribute.internalKey;

/*
 it works

*/

SELECT
	modUser.id AS blogger_id,
	modUserAttribute.email AS email,
	modUserAttribute.fullname AS fullname,
	modUserAttribute.mobilephone AS phone,
	COUNT(viewAct.id) AS activations_count
FROM `modx_users` AS modUser
LEFT OUTER JOIN `modx_activations` AS viewAct ON modUser.id = viewAct.blogger_id AND viewAct.act_date = 1445040000
LEFT JOIN `modx_user_attributes` AS modUserAttribute ON modUser.id = modUserAttribute.internalKey
GROUP BY modUser.id



SELECT
	`modx_bloggers`.blogger_id,
	`modx_bloggers`.email,
	`modx_bloggers`.fullname,
	`modx_bloggers`.phone,
	COUNT(viewAct.id) AS activations_count
FROM `modx_bloggers`
LEFT JOIN `modx_activations` AS viewAct ON `modx_bloggers`.blogger_id = viewAct.blogger_id AND viewAct.act_date = 1445040000
GROUP BY `modx_bloggers`.blogger_id
