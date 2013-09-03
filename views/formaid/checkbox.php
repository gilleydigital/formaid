<div class="formfield formfield-checkbox <?php echo $name ?>">
	<label for="<?php echo $name ?>"><?php echo $label ?></label>
	<?php echo Form::checkbox($name, $value, $checked, $attributes) ?>
	
</div>
