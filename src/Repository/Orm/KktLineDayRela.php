<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use DB;
use Line\Repository\Entity\DayEntity;

class KktLineDayRela extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_day_rela';
	protected $fillable = [
		'line_id', 'day_id','is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";
	
}
