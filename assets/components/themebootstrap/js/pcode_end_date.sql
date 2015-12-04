SELECT `pagetitle`, modTemplateVarResourceEndDate.value AS end_date
FROM `modx_site_content` AS `modResource`
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResourceBlg`
  ON ( modTemplateVarResourceBlg.tmplvarid = 5
  AND modTemplateVarResourceBlg.contentid = modResource.id )
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResourcePa`
  ON  ( modTemplateVarResourcePa.tmplvarid = 15
  AND modTemplateVarResourcePa.contentid = modResource.id )
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResourceEndDate`
  ON  ( modTemplateVarResourceEndDate.tmplvarid = 4
  AND modTemplateVarResourceEndDate.value > FROM_UNIXTIME(NOW) )
WHERE  ( `modResource`.`parent` = 5135
AND `modTemplateVarResourceBlg`.`value` = '38' )  LIMIT 1
