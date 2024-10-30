<div class="wrap">
<h2>Browser Counter Admin</h2>
<?php
$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."browsercounter_counter");
foreach($results as $result)
{
    echo strtoupper($result->browsercounter_name)." : ";
    echo $result->browsercounter_visits."";
	echo "<br>";
}
?>
</div>