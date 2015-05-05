<?php 
include("function.php");


if($_GET['log_file'])
			define("log_file", get_logs_folder().$_GET['log_file']);
				log_info(log_file, "db backup file after closing .");

if($_GET['date'])
{
define("date", $_GET['date']);
}else{
define("date", date("Y-m-d"));

}

$backup_file = realpath(get_dbbackup_path()) . "/" .$db_name ."_" .date. "_" .time(). '.sql';
 $command = "C:/xampp/mysql/bin/mysqldump.exe --opt -h" .$db_host.
	 " -u" .$db_user. " -p" .$db_password. " " .$db_name. " > " .$backup_file;
	 $res=0;
system($command, $res);

//echo "Database backup taken at " .$backup_file;

//echo $res;
if ($res)
{
	log_error("Error[code = " .$res. "] while taking DB backup. Exiting process");
	mail_exit(__FILE__, __LINE__);
}
else
{

	log_info("Database backup taken at " .$backup_file);
	
	/* TODO: Here we can delete previous day db backups to avoid memory over-run */
}
	 
	 log_info(" Closing Process Finished  ");
	 
	 

?>