<?php 
include("function.php");


if($_GET['log_file'])
			define("log_file", get_logs_folder().$_GET['log_file']);
				log_info(log_file, "db backup file after closing .");
exec("database_open.bat");
	 
	 log_info(" Closing Process Finished  ");
	 
	 

?>