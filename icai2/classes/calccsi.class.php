<?php

class Calccsi extends Application{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function index()
	{
		
					if($_GET['date'])
			define("date", $_GET['date']);
			else
			define("date", date("Y-m-d"));

		
		//echo "select * from tbl_indxx_cs  where status='1' ";
		$indxxs=$this->db->getResult("select * from tbl_indxx_cs  where status='1' ",true);	
		//$this->pr($indxxs);
		 //$datevalue2=date('Y-m-d',strtotime($this->_date)-(86400));
		 $datevalue2=$this->_date;
//		 $datevalue2="2015-04-24";
		  if($_GET['log_file'])
			define("log_file", $_GET['log_file']);
				$this->log_info(log_file, "In Complex Strategies index File  generation  for live index");
	
		 $final_array=array();
		 if(!empty($indxxs))
		 {
			 
			 foreach($indxxs as $row)
			 {
				
					$this->log_info(log_file, "Preparing data for ".$row['name']);
				
				$final_array[$row['id']]=$row;
				
				
					$client=$this->db->getResult("select tbl_ca_client.ftpusername from tbl_ca_client where id='".$row['client_id']."'",false,1);	
					$final_array[$row['id']]['client']=$client['ftpusername'];
			//	echo "select * from tbl_csi_adj_factor  where ca_indxx_id='".$row['id']."' ";
				$calcfactors=$this->db->getResult("select * from tbl_csi_adj_factor  where cs_indxx_id='".$row['id']."' ",true);	
				if(!empty($calcfactors))
				{
				foreach($calcfactors as $key=> $calcfactor)
				{
				//$this->pr($calcfactor);
				
				
				$indxx_name=$this->db->getResult("select name from tbl_indxx  where code='".$calcfactor['code']."' ",false,1);
			//	echo "select indxx_value from tbl_indxx_value  where code='".$calcfactor['code']."' order by date desc ";
				$indxx_value=$this->db->getResult("select indxx_value from tbl_indxx_value  where code='".$calcfactor['code']."' and date='".$datevalue2."' order by date desc ",false,1);
					$calcfactors[$key]['indxx_name']=$indxx_name['name'];
					$calcfactors[$key]['indxx_value']=$indxx_value['indxx_value'];
				
				
				}
				}
				$final_array[$row['id']]['values']=$calcfactors;
				
				
			
			}
			 
			 
		 }
		 
		//$this->pr( $final_array,true);
		
		if(!empty($final_array))
		{  file_put_contents('../files/backup/preCLOSECCSIdata'.date("Y-m-d-H-i-s").'.json', json_encode($final_array));
		 	foreach($final_array as $key=>$closeIndxx)
		{
			if(!$closeIndxx['client'])
			$file="../files/ca-output/Closing-".$closeIndxx['code']."-".$datevalue2.".txt";
			else
			$file="../files/ca-output/".$closeIndxx['client']."/Closing-".$closeIndxx['code']."-".$datevalue2.".txt";
			
			$client_folder="../files/ca-output/".$closeIndxx['client']."/";
			if (!file_exists($client_folder))
			mkdir($client_folder, 0777, true);
			
			$open=fopen($file,"w+");
			
			$entry1='Date'.",";
			$entry1.=date("Y-m-d",strtotime($datevalue2)).",\n";
			$entry1.='INDEX VALUE'.",";
			$entry3.='NAME'.",";
			$entry3.='CODE'.",";
			$entry3.='FACTOR'.",";
			$entry3.='INDEX VALUE'.",";
			
			
			$entry4='';
		$index_value=0;
		
			if(!empty($closeIndxx))
		{
		foreach($closeIndxx['values'] as $security)
		{
		$index_value+=$security['fraction']*$security['indxx_value'];
		
		
            $entry4.= "\n".$security['indxx_name'].",";
            $entry4.=$security['code'].","; 
			 $entry4.=$security['fraction'].",";
            $entry4.=$security['indxx_value'].",";
		
		}
		
		}

	$entry2=number_format($index_value,2,'.','').",\n";

 $insertQuery='INSERT into tbl_indxx_cs_value (indxx_id,code,indxx_value,date) values ("'.$closeIndxx['id'].'","'.$closeIndxx['code'].'","'.number_format($index_value,2,'.','').'","'.$datevalue2.'")';
		$this->db->query($insertQuery);	
	
	if($open){   
 if(   fwrite($open,$entry1.$entry2.$entry3.$entry4))
{
	$this->log_info(log_file, "file writing done for ".$row['name']);
}}
		
		}
		 file_put_contents('../files/backup/postOPENCSIdata'.date("Y-m-d-H-i-s").'.json', json_encode($final_array));
		}
		
		
		$this->saveProcess(2);
		$this->Redirect2("index.php?module=calcsl&date=" .date. "&log_file=" . basename(log_file),"","");	
		//$this->Redirect("index.php?module=calcftpclose","","");		
	}

}