<?php
/*
 * @var \Framework\Template\PhpRenderer $this
 * @var string $name
 */
?>
<?php
/*
 * @var \Framework\Template\PhpRenderer $this
 * @var string $name
 */
$this->extend('layout/columns');
$this->params['title'] = 'Cabinet';
?>

<?php $this->beginBlock();?>
<div class="panel panel-default">
    <div class="panel-heading">Cabinet</div>
    <div class="panel-body">
        Cabinet navigation
    </div>
</div>
<?php $this->endBlock('sidebar') ?>

<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Cabinet</li>
</ul>

<h1>Cabinet of <?=htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE) ?></h1>
