<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity;
use DB;

class KktLineItemImg extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_item_img';
	protected $fillable = [
		'item_id', 'cover', 'large_url', 'middle_url','small_url','group_large','group_middle','group_small','is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";
	

}
