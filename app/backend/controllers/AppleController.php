<?php

namespace backend\controllers;

use common\models\Apple;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class AppleController extends Controller {
  /**
   * @inheritDoc
   */
  public function behaviors() {
    return array_merge(
      parent::behaviors(),
      [
        'access' => [
          'class' => AccessControl::class,
          'rules' => [
            [
              'allow'   => true,
              'roles'   => ['@'],
            ],
          ],
        ],
        'verbs'  => [
          'class'   => VerbFilter::className(),
          'actions' => [
            'delete' => ['POST'],
          ],
        ],
      ]
    );
  }


  public function actionIndex(): string {
    $dataProvider = new ActiveDataProvider([
      'query' => Apple::find(),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * @param $id
   * @return Response
   * @throws NotFoundHttpException
   */
  public function actionDrop($id): Response {
    $model = $this->findModel($id);
    $model->fallToGround();

    return $this->goBack();
  }

  public function actionCreate(): Response {
    $colors = ['green', 'red', 'yellow'];
    shuffle($colors);
    $model = new Apple();
    $model->color = $colors[0];
    $model->save();

    return $this->redirect(['apple/index']);
  }

  /**
   * @param $id
   * @return string|Response
   * @throws NotFoundHttpException
   * @throws \backend\exceptions\CanNotEatException
   * @throws \backend\exceptions\InvalidEatValueException
   */
  public function actionEat($id): string|Response {
    $model = $this->findModel($id);

    if ($this->request->isPost && $this->request->post('percent')) {
      $model->eat((int)$this->request->post('percent'));
      return $this->redirect(['apple/index']);
    }

    return $this->render('eat', [
      'model' => $model,
    ]);
  }

  /**
   * @param $id
   * @return Apple|null
   * @throws NotFoundHttpException
   */
  protected function findModel($id): ?Apple {
    if (($model = Apple::findOne(['id' => $id])) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }
}
