<td>
	<select name="hour">
		<option value="01" <?php if ($user_hour == "01"): ?>selected="selected"<?php endif; ?>>1</option>
		<option value="02" <?php if ($user_hour == "02"): ?>selected="selected"<?php endif; ?>>2</option>
		<option value="03" <?php if ($user_hour == "03"): ?>selected="selected"<?php endif; ?>>3</option>
		<option value="04" <?php if ($user_hour == "04"): ?>selected="selected"<?php endif; ?>>4</option>
		<option value="05" <?php if ($user_hour == "05"): ?>selected="selected"<?php endif; ?>>5</option>
		<option value="06" <?php if ($user_hour == "06"): ?>selected="selected"<?php endif; ?>>6</option>
		<option value="07" <?php if ($user_hour == "07"): ?>selected="selected"<?php endif; ?>>7</option>
		<option value="08" <?php if ($user_hour == "08"): ?>selected="selected"<?php endif; ?>>8</option>
		<option value="09" <?php if ($user_hour == "09"): ?>selected="selected"<?php endif; ?>>9</option>
		<option value="10" <?php if ($user_hour == "10"): ?>selected="selected"<?php endif; ?>>10</option>
		<option value="11" <?php if ($user_hour == "11"): ?>selected="selected"<?php endif; ?>>11</option>
		<option value="12" <?php if ($user_hour == "12"): ?>selected="selected"<?php endif; ?>>12</option>
	</select>
</td>
<td>
	<select name="minute">
		<option value="00">00</option>
		<option value="05">05</option>
		<option value="10">10</option>
		<option value="15">15</option>
		<option value="20">20</option>
		<option value="25">25</option>
		<option value="30">30</option>
		<option value="35">35</option>
		<option value="40">40</option>
		<option value="45">45</option>
		<option value="50">50</option>
		<option value="55">55</option>
	</select>
</td>
<td>
	<select name="ampm">
		<option value="am">AM</option>
		<option value="pm">PM</option>
	</select>
</td>