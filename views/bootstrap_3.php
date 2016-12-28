<?php

    use \marvin255\serviform\helpers\Html;

    if ($this->getParent()) {
        $tag = 'fieldset';
    } else {
        $tag = 'form';
        $this->addToAttribute('class', ' form-horizontal');
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
                    <label class="col-sm-3 control-label">
                        <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <div class="col-sm-9">
                    <?php echo $el->getInput(); ?>
                </div>
            </div>
        <?php elseif ($el instanceof \marvin255\serviform\fields\Checkbox): ?>
            <div class="form-group<?php if (!empty($errors)) {
        echo ' has-error';
    } ?>">
                <div class="col-sm-offset-3 col-sm-9">
                    <div class="checkbox">
                        <label>
                            <?php echo $el->getInput(); ?>
                            <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                        </label>
                    </div>
                    <?php if (!empty($errors)): ?>
                        <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($el instanceof \marvin255\serviform\fields\Button): ?>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <?php echo $el->addToAttribute('class', ' btn')->getInput(); ?>
                </div>
            </div>
        <?php elseif ($el instanceof \marvin255\serviform\fields\HtmlText): ?>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <?php echo $el->getInput(); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="form-group<?php if (!empty($errors)) {
        echo ' has-error';
    } ?>">
                <?php if (!$this->getParent() || $el->getLabel()): ?>
                    <label for="<?php echo Html::clearAttribute($el->getAttribute('id')); ?>" class="col-sm-3 control-label">
                        <?php echo Html::clearAttributeValue($el->getLabel()); ?>
                    </label>
                <?php endif; ?>
                <div class="<?php echo (!$this->getParent() || $el->getLabel()) ? 'col-sm-9' : 'col-sm-12'; ?>">
                    <?php
                        echo $el->addToAttribute('class', ' form-control')->getInput();
                    ?>
                    <?php if (!empty($errors)): ?>
                        <span class="help-block"><?php echo implode(', ', $errors); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php echo Html::createCloseTag($tag); ?>
