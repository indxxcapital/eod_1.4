h<?php

class Checkcavalue extends Application{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function index()
	{
		//error_reporting(2);
							if($_GET['log_file'])
define("log_file",get_logs_folder().$_GET['log_file']);
if($_GET['date'])
define("date",$_GET['date']);
else
define("date",date("Y-m-d"));
log_info("In Check CA Value ");
		
	//$this->pr($_SESSION,true);
		
		//$this->_baseTemplate="main-template";
		//$this->_bodyTemplate="404";
		$this->_title=$this->siteconfig->site_title;
		$this->_meta_description=$this->siteconfig->default_meta_description;
		$this->_meta_keywords=$this->siteconfig->default_meta_keyword;
		
		//echo "select tbl_indxx.* from tbl_indxx  where status='1' and usersignoff='1' and dbusersignoff='1' and submitted='1 and id='1'";
		$indxxs=$this->db->getResult("select tbl_indxx.* from tbl_indxx  where status='1' and usersignoff='1' and dbusersignoff='1' and submitted='1' ",true);	
		
		
		$checkArray=array();
		
		//'CP_NET_AMT','CP_GROSS_AMT'
		$checkArray['DVD_CASH']=array('CP_DVD_CRNCY','CP_DVD_TYP');
		$checkArray['CHG_NAME']=array('CP_OLD_NAME','CP_NEW_NAME');
		$checkArray['CHG_ID']=array('CP_OLD_ISIN','CP_NEW_ISIN');
		$checkArray['SPIN']=array('CP_ADJ');
		$checkArray['DVD_STOCK']=array('CP_AMT');
		$checkArray['STOCK_SPLT']=array('CP_ADJ');
		$checkArray['RIGHTS_OFFER']=array('CP_RATIO','CP_ADJ','CP_PX','CP_CRNCY');		
		 $datevalue2=date;
		//$this->pr($checkArray,true);
		 
		 //print_r(array_keys($checkArray));
		 //exit;
		 $text='';
		 $missingvalue7daysText='';
	$final_array=array();
		
		
		  $ca_query="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date='".$datevalue2."'";
			
			//exit;
			$cas=$this->db->getResult($ca_query,true);	
			
	//		echo "<br>";
			//$caflag=false;
			
			
			if(!empty($cas))
			{
			foreach($cas as $cakey=> $ca)
			{
			
			
			
		
		if(array_key_exists($ca['mnemonic'],$checkArray))
		{
		foreach($checkArray[$ca['mnemonic']] as $fieldname)
		{
			
				 $ca_value_query="Select id from tbl_ca_values where ca_id='".$ca['id']."'  and ca_action_id='".$ca['action_id']."' and field_name='".$fieldname."' ";
			$ca_values=$this->db->getResult($ca_value_query);	
			if( count($ca_values)<=0)
			{
			$text.="<tr><td>".$ca['company_name']."</td><td>".$ca['identifier']."</td><td>".$_SESSION['variable'][$ca['mnemonic']]."</td><td>".$fieldname."</td><td>".$ca['eff_date']."</td></tr>";
			
			}
						
		}
		if($ca['mnemonic']=='DVD_CASH')
		{
			 $ca_value_query="Select id from tbl_ca_values where ca_id='".$ca['id']."'  and ca_action_id='".$ca['action_id']."'  and (field_name='CP_NET_AMT' or  field_name='CP_GROSS_AMT') ";
			$ca_values=$this->db->getResult($ca_value_query);	
			if( count($ca_values)<=0)
			{
			$text.="<tr><td>".$ca['company_name']."</td><td>".$ca['identifier']."</td><td>Cash Dividend</td><td>CP_NET_AMT AND CP_GROSS_AMT </td><td>".$ca['eff_date']."</td><tr>";
			
			}
		}
		
		
		}
			
			
		
			
			}
			}
			
			
			//$indxxprices[$key]['ca']=$cas;
			
			
			
			
		  $ca_query_missing="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date>='".$datevalue2."' and  eff_date<='".date("Y-m-d",strtotime($datevalue2)+7*86400)."'  ";
			
			//exit;
			$casmissing=$this->db->getResult($ca_query_missing,true);	
	//		echo "<br>";
			//$caflag=false;
			
			//$this->pr($casmissing);
			if(!empty($casmissing))
			{
			foreach($casmissing as $cakey=> $nca)
			{
			
			
			
		
		if(array_key_exists($nca['mnemonic'],$checkArray))
		{
			//$this->pr($casmissing);
		foreach($checkArray[$nca['mnemonic']] as $nfieldname)
		{
			
				 $nca_value_query="Select id from tbl_ca_values where ca_id='".$nca['id']."'  and ca_action_id='".$nca['action_id']."' and field_name='".$nfieldname."' ";
			$nca_values=$this->db->getResult($nca_value_query);	
			if( count($nca_values)<=0)
			{
				//echo "found";
			$missingvalue7daysText.="<tr><td>".$nca['company_name']."</td><td>".$nca['identifier']."</td><td>".$_SESSION['variable'][$ca['mnemonic']]."</td><td>".$nfieldname."</td><td>".$nca['eff_date']."</td></tr>";
			
			}
						
		}
			if($nca['mnemonic']=='DVD_CASH')
		{
			 $nca_value_query2="Select id from tbl_ca_values where ca_id='".$nca['id']."'  and ca_action_id='".$nca['action_id']."'  and (field_name='CP_NET_AMT' or  field_name='CP_GROSS_AMT') ";
			$nca_values2=$this->db->getResult($nca_value_query2);	
			if( count($nca_values2)<=0)
			{
			 $missingvalue7daysText.="<tr><td>".$nca['company_name']."</td><td>".$nca['identifier']."</td><td>Cash Dividend</td><td>CP_NET_AMT AND CP_GROSS_AMT </td><td>".$nca['eff_date']."</td><tr>";
			
			}
		}
		}
			
			
		
			
			}
			}
			
			
			
		//	$indxxprices[$key]['ca']=$cas;
			
			
			
	//	}
	//echo $text;
//exit;
$to='';
	$useremails=$this->db->getResult("select email from tbl_ca_user where 1=1",true);
	$emailids=array();
	foreach($useremails as $key=>$users)
	{
		$emailids[]=$users['email'];
	}
	
	$to=implode(',',$emailids);
	//$to="ggrover@indxx.com";


	
	$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Missing Values : Effective Today "; 
    $message = "<table  border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Missing Field</th>
			<th>Effective Date</th>
			</tr>";
	$message.=$text."</table>";
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
	// $missingvalue7daysText
	
	
	
		$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Missing Values : Next 7 days  "; 
    $message = '';
	$message.="<table  border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Missing Field</th>
			<th>Effective Date</th>
			</tr>";
	$message.=$missingvalue7daysText."</table>";
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
mail($to,$subject,$message,$headers);
//exit;

$intraDayText='';
	if(date("D")=="Mon")
	$intraDayDate=date("Y-m-d",strtotime(date)-3*86400);
	else
	$IntraDayDate=date("Y-m-d",strtotime(date)-86400);
	
$intraDayText='';
$IntraDayCAQuery="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  ann_date='".$datemodified."' and eff_date='".$datemodified."'   ";
			
			//exit;
			$IntraDayCA=$this->db->getResult($IntraDayCAQuery,true);
			if(!empty($IntraDayCA))
			{$intraDayText.="<table border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Effective Date</th>
			</tr>";
			foreach($IntraDayCA as $allca){
				
		$intraDayText.="<tr><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			
				}
					$intraDayText.="</table>";
			}
			

$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Intra Day "; 
		$message='';
$message.=$intraDayText;
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
	//echo $to;
	//exit;
	$text2="";
	
	  $ca_query2="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date='".$datevalue2."'   ";
		//	exit;
			//exit;
			$cas2=$this->db->getResult($ca_query2,true);
			if(!empty($cas2))
			{$text2.="<table  border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Effective Date</th>
			</tr>";
				
			foreach($cas2 as $allca){
				
			$text2.="<tr><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			
				}
				$text2.="</table>";
			}
			
		
				
				$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Effective Today "; 
	$message='';
	$message.=$text2;
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
			
				//mail($to,"Todays Corporate Action ICALC 1.4",);
			
	
	$text3="";
	
	  $ca_query3="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date<='".date("Y-m-d",strtotime($datevalue2)+7*86400)."' and  eff_date>='".$datevalue2."' ";
			
			//exit;
			$cas3=$this->db->getResult($ca_query3,true);
			if(!empty($cas3))
			{
				$text3.="<table  border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Effective Date</th>
			</tr>";
			foreach($cas3 as $allca){
				
				$text3.="<tr><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			
				}
					$text3.="</table>";
			}
			
		
				
				$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Upcoming Next 7 Days"; 
 		$message='';

	$message.=$text3;
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
mail($to,$subject,$message,$headers);
			
				//mail($to,"Todays Corporate Action ICALC 1.4",);
				
	
	if(date("D")=="Mon")
	$datemodified=date("Y-m-d",strtotime(date)-3*86400);
	else
	$datemodified=date("Y-m-d",strtotime(date)-86400);
	
$lastDayModifiedText='';
$lastDayModifiedCA="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  amd_date='".$datemodified."' and eff_date<='".date("Y-m-d",strtotime($datemodified)+15*86400)."' and eff_date>='".$datemodified."'   ";
			
			//exit;
			$caslastDayModified=$this->db->getResult($lastDayModifiedCA,true);
			if(!empty($caslastDayModified))
			{
						$lastDayModifiedText.="<table border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Effective Date</th>
			</tr>";
			foreach($caslastDayModified as $allca){
				
			
				$lastDayModifiedText.="<tr><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			
				}
					$lastDayModifiedText.="</table>";
			}
			

$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Modified : Previous day"; 
  	$message='';
	$message.=$lastDayModifiedText;
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
			

	
	
	
			

	$SpecialCAText='';
$SpecialCAQuery="select identifier,action_id,id,mnemonic,company_name,eff_date,currency,flag from tbl_ca cat where  eff_date>='".$datevalue2."'  and mnemonic in('ACQUIS','DELIST','RECLASS') ";
			
			//exit;
			$SpecialCA=$this->db->getResult($SpecialCAQuery,true);
			if(!empty($SpecialCA))
			{
				$SpecialCAText.="<table border='1'><tr>
			<th>Name</th>
			<th>Ticker</th>
			<th>Action Type</th>
			<th>Effective Date</th>
			</tr>";
			foreach($SpecialCA as $allca){
				if($allca['flag']=="N")
			$SpecialCAText.="<tr style='color:#008000'><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			else
			$SpecialCAText.="<tr><td>".$allca['company_name']."</td><td>".$allca['identifier']."</td><td>".$_SESSION['variable'][$allca['mnemonic']]."</td><td>".$allca['eff_date']."</td></tr>";
			
				}
					$SpecialCAText.="</table>";
			}
			

$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions : Upcoming Events"; 
  	$message='';
 
	$message.=$SpecialCAText;
	$message.='<br><br>Please visit '.$this->siteconfig->base_url;
	$message.='<br><br>Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);

	
		$this->saveProcess();

$this->Redirect("index.php?module=calcspinstockadd&log_file=".basename(log_file)."&date=".date,"","");	



}
   
} 
?>