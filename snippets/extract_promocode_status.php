<?php
/**
 * Показ статуса промо-кода
 * на входе pa_id
 *
 */

if(!empty($scriptProperties['pa_id'])) {
	$pa_id = $scriptProperties['pa_id'];
	// Проверяем участвует ли блогер в акции и меет ли промокод
	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if ($extended['promo_actions'] !== NULL) {
			if (array_search($pa_id, $extended['promo_actions']) !== FALSE) {
				// Для подключенного блогера получаем состояние промо-кода
				$q = $modx->newQuery('modResource');
				$q->select('pagetitle, modTemplateVarResourceEndDate.value AS end_date');
				$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceBlg', array('modTemplateVarResourceBlg.tmplvarid = 5',
						'modTemplateVarResourceBlg.contentid = modResource.id'));
				$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourcePa', array('modTemplateVarResourcePa.tmplvarid = 15',
						'modTemplateVarResourcePa.contentid = modResource.id'));
				$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceEndDate', array('modTemplateVarResourceEndDate.tmplvarid = 4',
						'modTemplateVarResourceEndDate.value > NOW()'));

				$q->where(array('parent' => 5135,
						'modTemplateVarResourceBlg.value' => $user->get('id'),
						'modTemplateVarResourcePa.value' => $pa_id));
				$q->limit(1);
				$q->prepare();

				$q->stmt->execute();
				$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($res) > 0) {
					$output = 'Промо-код: <span class="badge">' . $res[0]['pagetitle'] . '</span> сгорает ' . date('d.m.Y', strtotime($res[0]['end_date'])) . '.';
					return $output;
				} else {
					// Извлечение промокода
					$output = '<a data-toggle="modal" data-target="#extract_promocode" data-whatever="'.$pa_id.'" style="cursor: pointer;">Извлечь промокод</a>';
					return $output;
				}
			}
		}
		$output = '<a data-toggle="modal" data-target="#extract_promocode" data-whatever="' . $pa_id . '" style="cursor: pointer;" class="disactive">Извлечь промокод</a>';
		return $output;
	}
}