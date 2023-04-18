<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ServiceProvider $serviceProvider
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Service Provider'), ['action' => 'edit', $serviceProvider->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Service Provider'), ['action' => 'delete', $serviceProvider->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceProvider->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Service Providers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Service Provider'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="serviceProviders view content">
            <h3><?= h($serviceProvider->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($serviceProvider->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($serviceProvider->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($serviceProvider->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($serviceProvider->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($serviceProvider->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
