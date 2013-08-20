<?php if(isset($form_result)): ?>
	<?php foreach($form_result AS $type => $messages): ?>
	<div class="result <?php echo $type ?>">
		<ul>
	<?php foreach($messages AS $message): 
			if(is_array($message)): 
				foreach($message AS $inner): ?>
					<li><?php echo $inner ?></li>
				<?php endforeach; ?>
			<?php else: ?>
				<li><?php echo $message ?></li>
			<?php endif; ?>
	<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
<?php endif; ?>
