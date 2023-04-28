<?php

namespace common\models;

use backend\exceptions\CanNotEatException;
use backend\exceptions\InvalidEatValueException;
use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Apple".
 * по хорошему делать некий AppleRepository и там делать методы eat, fallToGround и eaten
 *
 * @property int         $id
 * @property string      $color
 * @property string      $created_at
 * @property string|null $drop_at
 * @property int         $status
 * @property float       $size
 */
class Apple extends \yii\db\ActiveRecord {
  public const SPOILED_HOURS = 5;

  public const STATUS_ON_TREE = 1;
  public const STATUS_DROPPED = 2;

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'apple';
  }

  public function behaviors() {
    return [
      [
        'class'              => TimestampBehavior::class,
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => false,
        'value'              => static fn() => new Expression('NOW()'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['status'], 'integer'],
      [['size'], 'double'],
      [['color'], 'string'],
      [['created_at', 'drop_at'], 'safe'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id'         => 'ID',
      'color'      => 'Color',
      'created_at' => 'Created At',
      'drop_at'    => 'Drop At',
      'status'     => 'Status',
      'size'       => 'Size',
    ];
  }

  public function fallToGround(): bool {
    if ($this->status === self::STATUS_DROPPED) {
      return true;
    }

    $this->drop_at = new Expression('NOW()');
    $this->status  = self::STATUS_DROPPED;

    return $this->save();
  }

  public function eat(int $percent): bool {
    if ($this->status !== self::STATUS_DROPPED) {
      throw new CanNotEatException('Apple is not dropped yet');
    }

    if ($this->isSpoiled()) {
      throw new CanNotEatException('Apple is spoiled');
    }

    if (!($percent > 0 && $percent <= 100)) {
      throw new InvalidEatValueException('Percent value must be from 1 to 100');
    }

    $this->size = $this->size - ($percent / 100);
    if ($this->size <= 0) {
      return $this->eaten();
    }

    return $this->save();
  }

  public function isSpoiled(): bool {
    if ($this->status !== self::STATUS_DROPPED) {
      return false;
    }

    $droppedAt   = new DateTime($this->drop_at);
    $now         = new DateTime();
    $diff        = $now->diff($droppedAt);
    $diffInHours = $diff->h + ($diff->days * 24);

    return $diffInHours >= self::SPOILED_HOURS;
  }

  protected function eaten(): bool {
    return $this->delete() !== false; // в реальном проекте наверное стоит не удалять из БД а писать статус
  }
}
