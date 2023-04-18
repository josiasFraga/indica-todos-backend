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
            <?= $this->Html->link(__('Edit Service Subcategory'), ['action' => 'edit', $serviceSubcategory->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Service Subcategory'), ['action' => 'delete', $serviceSubcategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceSubcategory->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Service Subcategories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Service Subcategory'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="serviceSubcategories view content">
            <h3><?= h($serviceSubcategory->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($serviceSubcategory->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($serviceSubcategory->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category Id') ?></th>
                    <td><?= $this->Number->format($serviceSubcategory->category_id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
