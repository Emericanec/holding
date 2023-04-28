<?php

use common\models\Apple;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title                   = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
      <?= Html::a('Create Apple', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      'color',
      'created_at',
      'drop_at',
      [
        'class' => DataColumn::class, // can be omitted, as it is the default
        'label' => 'Status',
        'value' => function(Apple $model) {
          return match (true) {
            $model->isSpoiled() => 'Spoiled',
            $model->status === Apple::STATUS_DROPPED => 'Dropped',
            default => 'On tree'
          };
        },
      ],
      [
        'class' => DataColumn::class, // can be omitted, as it is the default
        'label' => 'Size',
        'value' => function(Apple $model) {
          return $model->size * 100 . '%';
        },
      ],
      [
        'class'      => ActionColumn::class,
        'buttons'    => [
          'drop' => function($url, Apple $model) {
            return Html::a('Drop', $url);
          },
          'eat'  => function($url, Apple $model) {
            return Html::a('Eat', $url);
          },
        ],
        'urlCreator' => function($action, Apple $model, $key, $index, $column) {
          return Url::toRoute([$action, 'id' => $model->id]);
        },
        'template'   => '{drop}  {eat}',
      ],
    ],
  ]); ?>


</div>
