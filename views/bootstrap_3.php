<?php

    use marvin255\serviform\helpers\Html;
    use marvin255\serviform\interfaces\HasChildren;
    use marvin255\serviform\fields\Checkbox;
    use marvin255\serviform\fields\Button;
    use marvin255\serviform\fields\Input;
    use marvin255\serviform\fields\Select;
    use marvin255\serviform\fields\Textarea;

    if ($this->getParent()) {
        $tag = 'fieldset';
    } else {
        $tag = 'form';
    }

    echo Html::createTag($tag, $this->getAttributes(), false);
    if ($tag === 'fieldset' && $this->getLabel()) {
        echo Html::createTag('legend', null, Html::clearAttributeValue($this->getLabel()));
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
        <?php if ($el instanceof Checkbox): ?>
            <div class="checkbox<?php if (!empty($errors)) {
        echo ' has-error';
    } ?>">
                <label>
                    <?php echo $el->getInput(); ?>
                    <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                </label>
                <?php if (!empty($errors)): ?>
                    <span class="help-block">
                        <?php echo Html::clearAttributeValue(implode(', ', $errors)); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php elseif ($el instanceof HasChildren): ?>
            <?php echo $el->getInput(); ?>
        <?php elseif ($el instanceof Button): ?>
            <?php echo $el->addToAttribute('class', ' btn')->getInput(); ?>
        <?php else: ?>
            <div class="form-group<?php if (!empty($errors)) {
        echo ' has-error';
    } ?>">
                <?php if ($el->getLabel()): ?>
                    <label for="<?php echo Html::clearAttributeValue($el->getAttribute('id')); ?>">
                        <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <?php
                    if ($el instanceof Input || $el instanceof Select || $el instanceof Textarea) {
                        $el->addToAttribute('class', ' form-control');
                    }
                    echo $el->getInput();
                ?>
                <?php if (!empty($errors)): ?>
                    <span class="help-block">
                        <?php echo Html::clearAttributeValue(implode(', ', $errors)); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php echo Html::createCloseTag($tag); ?>
