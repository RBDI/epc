<? include "config.php";
$now=date("c"); 

$data='<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
  <loc>http://europrofcosmetic.ru/</loc>
  <lastmod>'.$now.'</lastmod>
</url>
<url>
  <loc>http://europrofcosmetic.ru/catalog.html</loc>
  <lastmod>'.$now.'</lastmod>
</url>
<url>
  <loc>http://europrofcosmetic.ru/delivery_and_payment.html</loc>
  <lastmod>'.$now.'</lastmod>
</url>
<url>
 <loc>http://europrofcosmetic.ru/contacts.html</loc>
  <lastmod>'.$now.'</lastmod>
</url>
<url>
 <loc>http://europrofcosmetic.ru/about.html</loc>
  <lastmod>'.$now.'</lastmod>
</url>';


$sql="SELECT slug FROM shop_params WHERE archive=0";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$data.='<url>
  <loc>http://europrofcosmetic.ru/catalog_'.$row['slug'].'.html</loc>
  <lastmod>'.$now.'</lastmod>
</url>
';
} 

$sql="SELECT ID FROM shop_catalog WHERE archive=0";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$data.='<url>
  <loc>http://europrofcosmetic.ru/catalog/products/'.$row['ID'].'</loc>
  <lastmod>'.$now.'</lastmod>
</url>
';
}


$data.='
</urlset>';
    
	$filename = "sitemap.xml";
	$fh = fopen($filename, "w");
	$is=fwrite($fh, $data);
	print '?='.$is;
	fclose($fh); 
?>