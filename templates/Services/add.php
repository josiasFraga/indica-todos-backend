<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 * @var \Cake\Collection\CollectionInterface|string[] $serviceCategories
 * @var \Cake\Collection\CollectionInterface|string[] $serviceSubcategories
 * @var \Cake\Collection\CollectionInterface|string[] $serviceProviders
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Services'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="services form content">
            <?= $this->Form->create($service) ?>
            <fieldset>
                <legend><?= __('Add Service') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('description');
                    echo $this->Form->control('category_id', ['options' => $serviceCategories]);
                    echo $this->Form->control('subcategory_id', ['options' => $serviceSubcategories]);
                    echo $this->Form->control('provider_id', ['options' => $serviceProviders]);
                    echo $this->Form->control('price');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
