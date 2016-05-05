<?php

class DatetimeProcessor extends FieldProcessor {
	
	public function process(&$item) {
		$dt = new DateTime($item);
		$dt_str = $dt->format('Y-m-d H:i:s');
		$dt_arr = explode(' ', $dt_str);
		$d_arr = explode('-', $dt_arr[0]);
		$t_arr = explode(':', $dt_arr[1]);
		$item = array(
			'raw'	=>	$item,
			'year'	=>	$d_arr[0],
			'month'	=>	$d_arr[1],
			'date'	=>	$d_arr[2],
			'hour'	=>	$t_arr[0],
			'min'	=>	$t_arr[1],
			'sec'	=>	$t_arr[2]
		);
	}
}