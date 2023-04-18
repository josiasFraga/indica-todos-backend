<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Location'), ['action' => 'edit', $location->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Location'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Locations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Location'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="locations view content">
            <h3><?= h($location->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Service Provider') ?></th>
                    <td><?= $location->has('service_provider') ? $this->Html->link($location->service_provider->name, ['controller' => 'ServiceProviders', 'action' => 'view', $location->service_provider->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($location->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('City') ?></th>
                    <td><?= h($location->city) ?></td>
                </tr>
                <tr>
                    <th><?= __('State') ?></th>
                    <td><?= h($location->state) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($location->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
