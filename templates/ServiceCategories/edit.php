<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ServiceCategory $serviceCategory
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $serviceCategory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $serviceCategory->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Service Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="serviceCategories form content">
            <?= $this->Form->create($serviceCategory) ?>
            <fieldset>
                <legend><?= __('Edit Service Category') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
