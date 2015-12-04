SELECT modResource.id AS id, `pagetitle`
FROM `modx_site_content` AS `modResource`
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResourceBlg`
  ON  ( modTemplateVarResourceBlg.tmplvarid = 5
  AND modTemplateVarResourceBlg.contentid = modResource.id )
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResourcePa`
  ON  ( modTemplateVarResourcePa.tmplvarid = 15
  AND modTemplateVarResourcePa.contentid = modResource.id
  AND `modTemplateVarResourcePa`.`value` = '30' )
WHERE  ( `modResource`.`parent` = 5135
AND `modTemplateVarResourceBlg`.`value` IS NULL )
ORDER BY pagetitle ASC LIMIT 1