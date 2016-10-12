<?php
return array(
	//'配置项'=>'配置值',
	'URL_INDEX' => '/monitor/index/index',
	'URL_STATUS' => '/monitor/index/chkstatus',
	'URL_LOGIN' => '/monitor/index/login',
	'URL_PUBLIC' => '/monitor/Public',
	'MOD_WARN' => '/monitor/warn',
	'TMPL_L_DELIM' => '<{',
	'TMPL_R_DELIM' => '}>',
	'DB_TYPE' => 'mysql',
	'DB_HOST' => 'localhost',
	'DB_NAME' => 'winnerlook',
	'DB_USER' => 'root',
	'DB_PWD' => '123123',
	'DB_PORT' => '3306',
	'DB_PREFIX' => '', //设置表前缀
	'DATA_COLL'=>'/home/cph/jsons/data_coll.json',//设置统计数据模块的配置文件路径
	'WARN_CONF'=>'/home/cph/jsons/warn_config.json',//设置服务报警配置文件路径
	'WARN_COLS_CONF'=>'/home/cph/jsons/warn_cols_config.json',//设置统计报警配置文件路径
);
