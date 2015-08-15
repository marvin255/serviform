<?php

	use \serviform\helpers\Html;

	$els = $this->getElements();
	$buttons = $this->getButtons();

	$tag = $this->getParent() ? 'div' : 'form';
	$this->setAttribute('class', $this->getAttribute('class') . ' form-horizontal');

	echo Html::tag($tag, $this->getAttributes(), false);

	foreach ($els as $key => $el) {
		if ($el->getAttribute('type') !== 'hidden') continue;
		echo $el->getInput();
		unset($els[$key]);
	}
?>

	<?php foreach ($els as $el):
		$errors = $el->getErrors();
	?>
		<div class="form-group<?php if (!empty($errors)) echo ' has-error'; ?>">
			<?php if ($el instanceof \serviform\fields\Checkbox): ?>
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<?php echo $el->getInput(); ?>
							<?php echo Html::clearText($el->getLabel()); ?>
						</label>
					</div>
				</div>
			<?php else: ?>
				<label for="<?php echo Html::clearAttribute($el->getAttribute('id')); ?>" class="col-sm-2 control-label">
					<?php echo Html::clearText($el->getLabel()); ?>
				</label>
				<div class="col-sm-10">
					<?php echo $el->getInput(); ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($errors)): ?>
				<span class="help-block"><?php echo implode(', ', $errors); ?></span>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>

	<?php if (!empty($buttons)): ?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php 
					foreach ($this->getButtons() as $btn) echo $btn->getInput();
				?>
			</div>
		</div>
	<?php endif; ?>

<?php echo Html::closeTag($tag); ?>