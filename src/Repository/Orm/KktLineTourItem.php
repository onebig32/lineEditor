<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use DB;

class KktLineTourItem extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_tour_item';
	protected $fillable = [
		'id','tour_id','item_id','is_delete', 'created_at', 'updated_at','sort'
	];
	protected $connection = "mysql_platform";
	

	
}
