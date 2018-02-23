<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */
?>
<?php $this->extend('layout/default'); ?>


<?php $this->beginBlock('content');?>
    <div class="row">
        <div class="col-md-9 .col-lg-push-9 col-sm-12">
            <?= $this->renderBlock('main'); ?>
        </div>
        <div class="col-md-3 .col-lg-pull-3 col-sm-12">
            <?php if($this->ensureBlock('sidebar')): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Navigation</div>
                    <div class="panel-body">
                        Default navigation
                    </div>
                </div>
            <?php endif; ?>
            <?php $this->endBlock() ?>
            <?= $this->renderBlock('sidebar'); ?>
        </div>
    </div>
<?php $this->endBlock() ?>