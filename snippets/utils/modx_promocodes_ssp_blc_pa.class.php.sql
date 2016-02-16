--- Пример запроса для вывода промо-акций блогера на странице Статистика блогера
--- Данный пример реализован в ssp_blg_pa.class.php

SELECT modPromocodes.pa_id AS pa_id,
       modPromocodes.blogger_id AS blogger_id,
       (SELECT MIN(modBegin.pc_start_date)
        FROM `modx_promocodes` AS modBegin
        WHERE modBegin.pa_id = modPromocodes.pa_id AND modBegin.pc_start_date > 0
       ) AS pc_start_date,
       (SELECT SUM(modBonus.bonus_sum)
        FROM `modx_promocodes` AS modBonus
              WHERE modBonus.pa_id = modPromocodes.pa_id AND modBonus.blogger_id = 43
       ) AS bonus_sum,
       (SELECT SUM(modActivs.pc_activations_count)
        FROM `modx_promocodes` AS modActivs
        WHERE modActivs.pa_id = modPromocodes.pa_id AND modActivs.blogger_id = 43
       ) AS pc_activations_count
FROM `modx_promocodes` AS modPromocodes
WHERE modPromocodes.blogger_id > 0 AND modPromocodes.blogger_id = 43
GROUP BY modPromocodes.pa_id;