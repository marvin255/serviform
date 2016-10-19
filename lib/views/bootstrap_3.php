<?php

    use \serviform\helpers\Html;

    if ($this->getParent()) {
        $tag = 'fieldset';
    } else {
        $tag = 'form';
        $this->addToAttribute('class', ' form-horizontal');
    }

    echo Html::tag($tag, $this->getAttributes(), false);
    if ($tag === 'fieldset' && $this->getLabel()) {
        echo Html::tag('legend', null, $this->getLabel());
    }

    $els = $this->getElements();
    //show hidden element first
    foreach ($els as $key => $el) {
        if ($el->getAttribute('type') !== 'hidden') {
            continue;
        }
        echo $el->getInput();
        unset($els[$key]);
    }
?>
    <?php foreach ($els as $el):
        $errors = $el->getErrors();
    ?>
        <?php if ($el instanceof \serviform\IChildable): ?>
            <?php echo $el->getInput(); ?>
        <?php elseif ($el instanceof \serviform\fields\Checkbox): ?>
            <div class="form-group<?php if (!empty($errors)) echo ' has-error'; ?>">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <?php echo $el->getInput(); ?>
                            <?php echo Html::clearText($el->getLabel()); ?>
                        </label>
                    </div>
                    <?php if (!empty($errors)): ?>
                        <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($el instanceof \serviform\fields\Button): ?>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <?php echo $el->addToAttribute('class', ' btn')->getInput(); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="form-group<?php if (!empty($errors)) echo ' has-error'; ?>">
                <label for="<?php echo Html::clearAttribute($el->getAttribute('id')); ?>" class="col-sm-2 control-label">
                    <?php echo Html::clearText($el->getLabel()); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo $el->addToAttribute('class', ' form-control')->getInput(); ?>
                    <?php if (!empty($errors)): ?>
                        <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php echo Html::closeTag($tag); ?>
