<?php
/** @var $model User */

use app\models\User;


?>
<h1>Login</h1>
<?php $from = \app\core\form\Form::begin('', "post") ?>
<?php echo $from->field($model,'email') ?>
<?php echo $from->field($model,'password')->passwordField()?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php echo \app\core\form\Form::end() ?>