<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use DB;

class KktLineDayItems extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_day_items';
	protected $fillable = [
		'id','day_id','item_id','is_import','sort','is_delete', 'created_at', 'updated_at',
	];
	protected $connection = "mysql_platform";
	

	
}
