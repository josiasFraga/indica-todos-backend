<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ServiceSubcategory> $serviceSubcategories
 */
?>
<div class="serviceSubcategories index content">
    <?= $this->Html->link(__('New Service Subcategory'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Service Subcategories') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('category_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($serviceSubcategories as $serviceSubcategory): ?>
                <tr>
                    <td><?= $this->Number->format($serviceSubcategory->id) ?></td>
                    <td><?= h($serviceSubcategory->name) ?></td>
                    <td><?= $this->Number->format($serviceSubcategory->category_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $serviceSubcategory->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $serviceSubcategory->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $serviceSubcategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceSubcategory->id)]) ?>
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
