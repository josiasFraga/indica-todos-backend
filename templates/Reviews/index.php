<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Review> $reviews
 */
?>
<div class="reviews index content">
    <?= $this->Html->link(__('New Review'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Reviews') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('service_id') ?></th>
                    <th><?= $this->Paginator->sort('rating') ?></th>
                    <th><?= $this->Paginator->sort('created_at') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= $this->Number->format($review->id) ?></td>
                    <td><?= $review->has('user') ? $this->Html->link($review->user->name, ['controller' => 'Users', 'action' => 'view', $review->user->id]) : '' ?></td>
                    <td><?= $review->has('service') ? $this->Html->link($review->service->title, ['controller' => 'Services', 'action' => 'view', $review->service->id]) : '' ?></td>
                    <td><?= $this->Number->format($review->rating) ?></td>
                    <td><?= h($review->created_at) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $review->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $review->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $review->id], ['confirm' => __('Are you sure you want to delete # {0}?', $review->id)]) ?>
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
