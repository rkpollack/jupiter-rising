<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$ort = $data['result']['parameters']['ort'];
$action = $data['result']['action'];
if ($action == "coosemansSales") {
    $url = "http://1webblvd.com/shpsls.htm";
    $offset = 3;
    if (strpos($ort,"arket") >0) $url = "http://dvr.coosemansla.com:81/stats/mktsls.htm";
    if (strpos($ort,"arket") >0) $offset = 0;
    $string = file_get_contents($url);
    $datestr = substr($string,121+$offset,2)."/".substr($string,124+$offset,2);
    $timestr = substr($string,163+$offset,5);
    $x3 = strpos($string,"Total All Items")+121;
    $amt = substr($string,$x3,12);
    $result = $ort." sales for ".$datestr." at ".$timestr." is $".$amt;
}
if ($action == "coolerTemps") {
if($page = fopen("http://dvr.coosemansla.com:8800","r")) {
  while ($line=fgets($page,65535)){
    if(strpos($line,"&deg;")) {
     $m1=substr($line,8,4);
     $m2=substr($line,28,4); 
    }
  }
} else {
	$m1="n/a";
	$m2="n/a";
}
if($page = fopen("http://weshipproduce.org:8800","r")) {
while ($line=fgets($page,65535)){
    if(strpos($line,"&deg;")) {
     $s1=substr($line,8,4);
     $s2=substr($line,28,4);
     $s3=substr($line,48,4);
     $s4=substr($line,68,4);
    }
  }
} else {
	$s1="n/a";
	$s2="n/a";
	$s3="n/a";
	$s4="n/a";
}
$result = "Cooler 1 temperature is ".$s1." degrees. Cooler 2 Temperature is ".$s2." degrees. Cooler 3 temperature is ".$s3." degrees. Cooler 4 Temperature is ".$s4." degrees.";
if (strpos($ort,"arket")>0) $result = "Cooler 1 temperature is ".$m1." degrees. Floor Temperature is ".$m2." degrees.";
}
$myarr = array ("speech" => $result, 
                "displayText" => $result,
                "source" => "coosemans-sales");
echo json_encode($myarr);
?>