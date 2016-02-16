-- для создания представления modx_blgs, которое пришло на смену modx_bloggers
SELECT
  modUser.id AS blogger_id,
  modUser.username AS username,
  modUserAttribute.email AS email,
  modUserAttribute.fullname AS fullname,
  modUserAttribute.mobilephone AS phone,
  (SELECT
  COUNT(modResource.id) AS activations_count
   FROM `modx_site_content` AS modResource
     LEFT JOIN `modx_site_tmplvar_contentvalues` `TVpc_blogger` on (`TVpc_blogger`.`contentid` = modResource.id and `TVpc_blogger`.`tmplvarid` = 5)
   WHERE modResource.parent = 4837 AND `TVpc_blogger`.value = modUser.id)
    AS activations_count
FROM `modx_users` AS modUser
  LEFT JOIN `modx_user_attributes` AS modUserAttribute ON modUser.id = modUserAttribute.internalKey;