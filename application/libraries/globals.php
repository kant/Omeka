<?php 
//Useful global library functions
function get_option($name) {
		$options = Zend_Registry::get('options');
		return $options[$name];
}

function set_option($name, $value)
{
	$conn = Doctrine_Manager::getInstance()->connection();
	$conn->exec("REPLACE INTO options (name, value) VALUES (?,?)", array($name, $value));
	
	//Now update the options hash so that any subsequent requests have it available
	$options = Zend_Registry::get('options');
	$options[$name] = $value;
	
	Zend_Registry::set('options', $options);
}


function pluck($col, $array)
{
	$res = array();
	foreach ($array as $k => $row) {
		$res[$k] = $row[$col];
	}
	return $res;	
} 

function current_user()
{
	return Omeka::loggedIn();
}

function strip_slashes($text)
{
	if($text !== null) {
		$text = get_magic_quotes_gpc() ? stripslashes( $text ) : $text;
	}
	return $text;
}

/**
 * @copyright Wordpress 2007 (GPL)
 *
 * @return mixed
 **/
function stripslashes_deep($value)
{
	 $value = is_array($value) ?
							 array_map('stripslashes_deep', $value) :
							 stripslashes($value);

	 return $value;
}

function install_notification() { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Omeka Installation</title>

<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Stylesheets -->
<link rel="stylesheet" media="screen" href="<?php echo WEB_ROOT.DIRECTORY_SEPARATOR; ?>install/install.css" />
</head>

<body>
<div id="wrap">

<?php die('<h1>Welcome to Omeka!</h1><p>It looks like you have not installed Omeka. <a href="'.WEB_ROOT.DIRECTORY_SEPARATOR.'install/">Begin the installation process.</a></p></div></body></html>'); ?>
	
<?php }
?>