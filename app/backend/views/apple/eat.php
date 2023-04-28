<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Apple $model */

$this->title                   = 'Eat Apple';
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="apple-form">

        <form method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <div class="form-group">
                <input type="number" class="form-control" min="1" max="100" name="percent" placeholder="Percent" required />
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Save" />
            </div>
        </form>
    </div>


</div>
