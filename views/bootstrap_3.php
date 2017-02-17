<?php

    use \marvin255\serviform\helpers\Html;

    if ($this->getParent()) {
        $tag = 'fieldset';
    } else {
        $tag = 'form';
    }

    echo Html::createTag($tag, $this->getAttributes(), false);
    if ($tag === 'fieldset' && $this->getLabel()) {
        echo Html::createTag('legend', null, $this->getLabel());
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
        <?php if ($el instanceof \marvin255\serviform\interfaces\HasChildren): ?>
            <div class="form-group">
                <?php if ($el->getLabel()): ?>
                    <label>
                        <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <?php echo $el->getInput(); ?>
            </div>
        <?php elseif ($el instanceof \marvin255\serviform\fields\Checkbox): ?>
            <div class="checkbox<?php if (!empty($errors)) echo ' has-error'; ?>">
                <label>
                    <?php echo $el->getInput(); ?>
                    <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                </label>
                <?php if (!empty($errors)): ?>
                    <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                <?php endif; ?>
            </div>
        <?php elseif ($el instanceof \marvin255\serviform\fields\Button): ?>
            <?php echo $el->addToAttribute('class', ' btn')->getInput(); ?>
        <?php elseif ($el instanceof \marvin255\serviform\fields\HtmlText): ?>
            <div class="form-group">
                <?php echo $el->getInput(); ?>
            </div>
        <?php else: ?>
            <div class="form-group<?php if (!empty($errors)) echo ' has-error'; ?>">
                <?php if (!$this->getParent() || $el->getLabel()): ?>
                    <label for="<?php echo Html::clearAttributeValue($el->getAttribute('id')); ?>">
                        <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <?php
                    echo $el->addToAttribute('class', ' form-control')->getInput();
                ?>
                <?php if (!empty($errors)): ?>
                    <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php echo Html::createCloseTag($tag); ?>
