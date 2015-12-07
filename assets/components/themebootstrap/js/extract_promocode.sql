SELECT pagetitle, modTemplateVarResource.value AS blg
FROM `modx_site_content` AS `modResource`
LEFT JOIN `modx_site_tmplvar_contentvalues` `modTemplateVarResource`
	ON (`modTemplateVarResource`.`tmplvarid` = '5' AND `modTemplateVarResource`.`contentid` = modResource.id)
WHERE ( `modResource`.`parent` = 5135 AND `modResource`.`pagetitle` REGEXP '^01[0-9]{1,4}$' AND `modTemplateVarResource`.`value` IS NULL) ORDER BY pagetitle ASC LIMIT 10
