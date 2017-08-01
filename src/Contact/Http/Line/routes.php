<?php
/**
 * 线路:路由
 * type 'GET', 'PUT'、'DELETE'、'POST'
 * priv 1:无权限 2:全部角色具备权限 3:部分角色具备权限 4:部分用户具备权限
 */
return [
	'Line.LineInfo.listAjax:GET:1','Line.LineInfo.getOneAjax:GET:1',
	'Line.LineInfo.addAjax:POST:1','Line.LineInfo.updateAjax:PUT:1',
	'Line.LineInfo.copyAjax:POST:1','Line.LineInfo.deleteAjax:DELETE:1',
];
