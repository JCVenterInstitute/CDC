<!-- this is first dropdown, second dropdown will be based on selection from this dropdown -->
<form method="post" action="">
<select name="first_dropdown">
<option value="a">a</option>
<option value="b">b</option>
<option value="c">c</option>
</select>
<input type="submit" />
</form>
<?php 
if (isset($_POST['first_dropdown']) && !empty($_POST['first_dropdown']) ):
?>


<select name="second_dropdown">

<?php
//if user select a
if ($_POST['first_dropdown'] == "a"):
?>
<option value="a1">a1</option>
<option value="a2">a2</option>
<option value="a3">a3</option>
<?php endif; ?>

<?php
//if user select b
if ($_POST['first_dropdown'] == "b"):
?>
<option value="b1">b1</option>
<option value="b2">b2</option>
<option value="b3">b3</option>
<?php endif; ?>

<?php
//if user select c
if ($_POST['first_dropdown'] == "c"):
?>
<option value="c1">c1</option>
<option value="c2">c2</option>
<option value="c3">c3</option>
<?php endif; ?>

</select>

<?php endif; ?>
