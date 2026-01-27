<?php

namespace app\models\forms;

use app\models\Author;
use Yii;
use yii\base\Model;
use yii\behaviors\AttributeTypecastBehavior;
use yii\web\Request;

/**
 * @property-read string fullName
 */
final class AuthorForm extends Model
{
    private const string SCENARIO_UPDATE = 'update';

    /** @var int */
    public $id;
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $middle_name;

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [AttributeTypecastBehavior::class]);
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => 'id', 'on' => self::SCENARIO_UPDATE],
            [['first_name', 'last_name', 'middle_name'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'first_name'  => Yii::t('app/author', 'First name'),
            'last_name'   => Yii::t('app/author', 'Last name'),
            'middle_name' => Yii::t('app/author', 'Middle name'),
        ]);
    }

    public function getFullName(): string
    {
        return "$this->last_name $this->first_name $this->middle_name";
    }

    public function handle(Request $request): bool
    {
        return $this->load($request->post()) && $this->validate();
    }

    public static function createFromModel(Author $author): self
    {
        $form = new self();
        $form->setScenario(self::SCENARIO_UPDATE);
        $form->setAttributes([
            'id'          => $author->id,
            'first_name'  => $author->first_name,
            'last_name'   => $author->last_name,
            'middle_name' => $author->middle_name,
        ]);

        return $form;
    }
}
