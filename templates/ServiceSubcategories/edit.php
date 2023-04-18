<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ServiceSubcategory $serviceSubcategory
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $serviceSubcategory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $serviceSubcategory->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Service Subcategories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="serviceSubcategories form content">
            <?= $this->Form->create($serviceSubcategory) ?>
            <fieldset>
                <legend><?= __('Edit Service Subcategory') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('category_id');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
