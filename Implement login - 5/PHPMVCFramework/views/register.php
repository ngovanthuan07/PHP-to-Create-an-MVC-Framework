<?php
/** @var $model User */

use app\models\User;


?>
<?php $from = \app\core\form\Form::begin('', "post") ?>
    <div class="row">
        <div class="col">
            <?php  echo $from->field($model,'firstname')?>
        </div>
        <div class="col">
            <?php  echo $from->field($model,'lastname')?>
        </div>
    </div>
    <?php echo $from->field($model,'email')?>
    <?php echo $from->field($model,'password')->passwordField()?>
    <?php echo $from->field($model,'confirmPassword')->passwordField()?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo \app\core\form\Form::end() ?>