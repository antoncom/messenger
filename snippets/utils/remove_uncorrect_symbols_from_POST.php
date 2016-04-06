<?php
// Фильтрация POST-данных - согласно требованию Билайн (см. таблицу требования - blog-04)
foreach($_POST as $key => &$value)	{
	if(is_array($value))	{
		foreach($value as $key2 => &$value2)	{
			if(is_array($value2))	{
				foreach($value2 as $key3 => &$value3)	{
					if(is_array($value3))	{
						foreach($value3 as $key4 => &$value4)	{
							$value4 = preg_replace('![^\w\d\s_=><+]*!','',$value4);
						}
					}
					else{
						$value3 = preg_replace('![^\w\d\s_=><+]*!','',$value3);
					}
				}
			}
			else{
				$value2 = preg_replace('![^\w\d\s_=><+]*!','',$value2);
			}
		}
	}
	else{
		$value = preg_replace('![^\w\d\s_=><+]*!','',$value);
	}
}