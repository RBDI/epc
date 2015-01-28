<?php
$performedDatetime=date("c");
print '<?xml version="1.0" encoding="UTF-8"?>
<checkOrderResponse performedDatetime="'.$performedDatetime.'" code="0" invoiceId="'.$_POST['invoiceId'].'" shopId="'.$_POST['shopId'].'"/>';
?>