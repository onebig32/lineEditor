<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Exception;
use DB;

class KktLineItemType extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_item_type';
	protected $fillable = [
		'pid', 'name','is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";
	

}
