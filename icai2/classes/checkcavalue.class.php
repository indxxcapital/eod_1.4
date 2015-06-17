h<?php

class Checkcavalue extends Application{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function index()
	{
		
							if($_GET['log_file'])
define("log_file",get_logs_folder().$_GET['log_file']);
if($_GET['date'])
define("date",$_GET['date']);
else
define("date","Y-m-d");
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
		$checkArray['DVD_CASH']=array('CP_NET_AMT','CP_GROSS_AMT','CP_DVD_CRNCY','CP_DVD_TYP');
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
		
		if(!empty($indxxs))
		{
			foreach($indxxs as $row)
			{
					if($this->checkHoliday($row['zone'], $datevalue2)){
			$final_array[$row['id']]=$row;
			

			
		
			
			
			//echo $datevalue;
		//	exit;
			$query="SELECT  it.ticker  FROM `tbl_indxx_ticker` it where it.indxx_id='".$row['id']."'";			
		
		
		
			$indxxprices=	$this->db->getResult($query,true);
			//$this->pr($indxxprices,true);	
			if(!empty($indxxprices))
			{
			foreach($indxxprices as $key=> $indxxprice)
			{
		  $ca_query="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date='".$datevalue2."' and identifier='".$indxxprice['ticker']."' ";
			
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
			$text.=$ca['company_name']."(".$ca['identifier'].")=>".$_SESSION['variable'][$ca['mnemonic']]."=>".$_SESSION['variable'][$fieldname]."=>".$fieldname."<br>";
			}
						
		}
		
		}
			
			
		
			
			}
			}
			
			
			//$indxxprices[$key]['ca']=$cas;
			
			
			
			
		  $ca_query_missing="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date>='".$datevalue2."' and  eff_date<='".date("Y-m-d",strtotime($datevalue2)+7*86400)."' and identifier='".$indxxprice['ticker']."' ";
			
			//exit;
			$casmissing=$this->db->getResult($ca_query_missing,true);	
	//		echo "<br>";
			//$caflag=false;
			
			
			if(!empty($casmissing))
			{
			foreach($casmissing as $cakey=> $nca)
			{
			
			
			
		
		if(array_key_exists($nca['mnemonic'],$ncheckArray))
		{
		foreach($ncheckArray[$nca['mnemonic']] as $nfieldname)
		{
			
				 $nca_value_query="Select id from tbl_ca_values where ca_id='".$nca['id']."'  and ca_action_id='".$nca['action_id']."' and field_name='".$nfieldname."' ";
			$nca_values=$this->db->getResult($nca_value_query);	
			if( count($ca_values)<=0)
			{
			$missingvalue7daysText.=$nca['company_name']."(".$nca['identifier'].")=>".$_SESSION['variable'][$nca['mnemonic']]."=>".$_SESSION['variable'][$nfieldname]."=>".$nfieldname."<br>";
			}
						
		}
		
		}
			
			
		
			
			}
			}
			
			
		//	$indxxprices[$key]['ca']=$cas;
			
			
			}
			}
			
		//	$final_array[$row['id']]['values']=$indxxprices;
		
		
	//$this->pr($indxxprices);	
			
					}
			}	
		
		}
	//	}
	//echo $text;
//exit;
$to='';

if($text!='' && $text)
{
	
	$useremails=$this->db->getResult("select email from tbl_ca_user where 1=1",true);
	$emailids=array();
	foreach($useremails as $key=>$users)
	{
		$emailids[]=$users['email'];
	}
	
	$to=implode(',',$emailids);
	$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Actions Value Not Inserted"; 
    $message = 'Hi <br>';
	$message.='Values for following corporate actions has not been inserted :<br>' ;
	$message.=$text;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
	// $missingvalue7daysText
	
	
	
		$from = "Indexing <indexing@indxx.com>"; 
    $subject ="7 Days values of Corporate Actions not available  "; 
    $message = 'Hi <br>';
	$message.='Values for following corporate actions is not available for next 7 days  :<br>' ;
	$message.=$missingvalue7daysText;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
	
}

	
	$text2="";
	
	  $ca_query2="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date='".$datevalue2."'   ";
			
			//exit;
			$cas2=$this->db->getResult($ca_query2,true);
			if(!empty($cas2))
			{
			foreach($cas2 as $allca){
				
			$text2.=$allca['company_name']."(".$allca['identifier'].")=>".$_SESSION['variable'][$allca['mnemonic']]."<br>";
			
				}
			}
			
		if($text2)		
			{
				
				$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Todays Corporate Action"; 
    $message = 'Hi <br>';
	$message.='Todays Corporate Action that going to execute are as below :<br>' ;
	$message.=$text2;
	$message.='Please visit '.$this->siteconfig->base_url;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
				
				//mail($to,"Todays Corporate Action ICALC 1.4",);
			}
	
	$text3="";
	
	  $ca_query3="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date<='".date("Y-m-d",strtotime($datevalue2)+7*86400)."' and  eff_date>='".$datevalue2."' ";
			
			//exit;
			$cas3=$this->db->getResult($ca_query3,true);
			if(!empty($cas3))
			{
			foreach($cas3 as $allca){
				
			$text3.=$allca['company_name']."(".$allca['identifier'].")=>".$_SESSION['variable'][$allca['mnemonic']]."<br>";
			
				}
			}
			
		if($text3)		
			{
				
				$from = "Indexing <indexing@indxx.com>"; 
    $subject ="upcomming 7 Days Corporate Action"; 
    $message = 'Hi <br>';
	$message.='7 days Corporate Action that are available portal as below :<br>' ;
	$message.=$text3;
	$message.='Please visit '.$this->siteconfig->base_url;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
				
				//mail($to,"Todays Corporate Action ICALC 1.4",);
			}
	
	
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
			foreach($caslastDayModified as $allca){
				
			$lastDayModifiedText.=$allca['company_name']."(".$allca['identifier'].")=>".$_SESSION['variable'][$allca['mnemonic']]."=>".$allca['eff_date']."<br>";
			
				}
			}
			
if($lastDayModifiedText)
{
$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Last Day Modified Corporate Action"; 
    $message = 'Hi <br>';
	$message.='Last Day Updated Corporate Action that are available portal as below :<br>' ;
	$message.=$lastDayModifiedText;
	$message.='Please visit '.$this->siteconfig->base_url;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
				
}
	
	
	$intraDayText='';
	if(date("D")=="Mon")
	$intraDayDate=date("Y-m-d",strtotime(date)-3*86400);
	else
	$IntraDayDate=date("Y-m-d",strtotime(date)-86400);
	
$lastDayModifiedText='';
$IntraDayCAQuery="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  ann_date='".$datemodified."' and eff_date='".$datemodified."'   ";
			
			//exit;
			$IntraDayCA=$this->db->getResult($IntraDayCAQuery,true);
			if(!empty($IntraDayCA))
			{
			foreach($IntraDayCA as $allca){
				
			$intraDayText.=$allca['company_name']."(".$allca['identifier'].")=>".$_SESSION['variable'][$allca['mnemonic']]."=>".$allca['eff_date']."<br>";
			
				}
			}
			
if($intraDayText)
{
$from = "Indexing <indexing@indxx.com>"; 
    $subject ="New Intra Day Corporate Action"; 
    $message = 'Hi <br>';
	$message.='New Intra Day Corporate Action that are available portal as below :<br>' ;
	$message.=$intraDayText;
	$message.='Please visit '.$this->siteconfig->base_url;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
				
}
	$SpecialCAText='';
$SpecialCAQuery="select identifier,action_id,id,mnemonic,company_name,eff_date,currency from tbl_ca cat where  eff_date>='".$datevalue2."' and eff_date<='".date("Y-m-d",strtotime($datevalue2)+86400)."'   and mnemonic in('ACQUIS','DELIST') ";
			
			//exit;
			$SpecialCA=$this->db->getResult($SpecialCAQuery,true);
			if(!empty($SpecialCA))
			{
			foreach($SpecialCA as $allca){
				
			$SpecialCAText.=$allca['company_name']."(".$allca['identifier'].")=>".$_SESSION['variable'][$allca['mnemonic']]."=>".$allca['eff_date']."<br>";
			
				}
			}
			
if($SpecialCAText)
{
$from = "Indexing <indexing@indxx.com>"; 
    $subject ="Corporate Events : Icalc 1.4"; 
    $message = 'Hi <br>';
	$message.='Corporate Events that are available portal as below :<br>' ;
	$message.=$SpecialCAText;
	$message.='Please visit '.$this->siteconfig->base_url;
	$message.='Thanks.';
	
    $headers = "From: $from" . "\r\n"."CC: indexing@indxx.com". "\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	mail($to,$subject,$message,$headers);
				
}
	
	
$this->saveProcess();

$this->Redirect("index.php?module=calcspinstockadd&log_file=".basename(log_file)."&date=".date,"","");	



}
   
} 
?>