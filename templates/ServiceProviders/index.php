<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ServiceProvider> $serviceProviders
 */
?>
<div class="serviceProviders index content">
    <?= $this->Html->link(__('New Service Provider'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Service Providers') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($serviceProviders as $serviceProvider): ?>
                <tr>
                    <td><?= $this->Number->format($serviceProvider->id) ?></td>
                    <td><?= h($serviceProvider->name) ?></td>
                    <td><?= h($serviceProvider->email) ?></td>
                    <td><?= h($serviceProvider->phone) ?></td>
                    <td><?= h($serviceProvider->address) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $serviceProvider->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $serviceProvider->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $serviceProvider->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceProvider->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
