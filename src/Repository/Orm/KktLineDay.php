<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\TourEntity;
use Exception;
use Line\Services\Query\QueryWhereDto;
use DB;

class KktLineDay extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_day';
	protected $fillable = [
		'line_id','tour_id', 'title', 'day', 'is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";

	
}
