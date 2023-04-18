<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 * @var \Cake\Collection\CollectionInterface|string[] $serviceProviders
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Locations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="locations form content">
            <?= $this->Form->create($location) ?>
            <fieldset>
                <legend><?= __('Add Location') ?></legend>
                <?php
                    echo $this->Form->control('provider_id', ['options' => $serviceProviders]);
                    echo $this->Form->control('address');
                    echo $this->Form->control('city');
                    echo $this->Form->control('state');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
