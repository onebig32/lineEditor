<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity;
use DB;

class KktLineItemImgs extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_item_imgs';
	protected $fillable = [
		'item_id', 'img_id','is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";
	

}
