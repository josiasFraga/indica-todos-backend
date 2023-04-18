<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $services
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $review->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $review->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Reviews'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="reviews form content">
            <?= $this->Form->create($review) ?>
            <fieldset>
                <legend><?= __('Edit Review') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('service_id', ['options' => $services]);
                    echo $this->Form->control('rating');
                    echo $this->Form->control('comment');
                    echo $this->Form->control('created_at');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
