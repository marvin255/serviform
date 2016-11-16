<?php

    use \serviform\helpers\Html;

    if ($this->getParent()) {
        $tag = 'fieldset';
    } else {
        $tag = 'form';
        $this->addToAttribute('class', ' form-inline');
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
                <div class="checkbox">
                    <label>
                        <?php echo $el->getInput(); ?>
                        <?php echo Html::clearText($el->getLabel()); ?>
                    </label>
                </div>
            </div>
        <?php elseif ($el instanceof \serviform\fields\Button): ?>
            <div class="form-group">
                <?php echo $el->addToAttribute('class', ' btn')->getInput(); ?>
            </div>
        <?php elseif ($el instanceof \serviform\fields\HtmlText): ?>
            <div class="form-group">
                <?php echo $el->getInput(); ?>
            </div>
        <?php else: ?>
            <div class="form-group<?php if (!empty($errors)) echo ' has-error'; ?>">
                <?php if ($el->getLabel()): ?>
                    <label for="<?php echo Html::clearAttribute($el->getAttribute('id')); ?>">
                        <?php echo Html::clearText($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <?php echo $el->addToAttribute('class', ' form-control')->getInput(); ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php echo Html::closeTag($tag); ?>
