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
            <?= $this->Html->link(__('Edit Service Category'), ['action' => 'edit', $serviceCategory->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Service Category'), ['action' => 'delete', $serviceCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceCategory->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Service Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Service Category'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="serviceCategories view content">
            <h3><?= h($serviceCategory->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($serviceCategory->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($serviceCategory->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
