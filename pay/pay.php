<form method="POST" action="https://demomoney.yandex.ru/eshop.xml">
	<input type="hidden" name="scid" value="51189">
	<input type="hidden" name="ShopID" value="14893">
	<input type="hidden" name="shopSuccessURL" value="http://europrofcosmetic.ru/pay/pay.php?x=1">
	<input type="hidden" name="shopFailURL" value="http://europrofcosmetic.ru/pay/pay.php?x=2">
	
	
<table>
  <tr>
    <td>
      <table border = "1" cellspacing = "0" width = "400" bgcolor = "#FFFFFF" align = "center" bordercolor = "#000000">
				<tr>
					<td>Sum</td>
					<td> <input type=text name="sum" value="10"> </td>
				</tr>
				<tr>
					<td>Customer Number</td>
					<td><input type="text" name="customerNumber" value="112233"></td>
					<td><input type="text" name="orderNumber" value="<? print time(); ?>"></td>
				</tr>
				<tr>
					<td>PaymnetType</td>
					<td>
						<input name="paymentType" checked="checked" value="" type="radio">Со счета в Яндекс.Деньгах<br>
                        <input name="paymentType" value="AC" type="radio">С банковской карты<br>
						<input name="paymentType" value="GP" type="radio">По коду через терминал<br>
					</td>
				</tr>
</table>
        <table border="0" cellspacing="1" align="center" width="400" bgcolor="#CCCCCC" >
          <tr bgcolor="#FFFFFF">
            <td width="490"></td>
            <td width="48">
              <input type="submit" name = "BuyButton" value = "Submit">
            </td>
          </tr>
        </table>

</form>