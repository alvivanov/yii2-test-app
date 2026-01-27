<?php

namespace app\models\forms;

use app\models\Author;
use app\models\Book;
use Yii;
use yii\base\Model;
use yii\behaviors\AttributeTypecastBehavior;
use yii\web\Request;
use yii\web\UploadedFile;

final class BookForm extends Model
{
    private const string SCENARIO_UPDATE = 'update';
    private const string SCENARIO_CREATE = 'create';

    /** @var int|null */
    public mixed $id = null;
    /** @var string */
    public mixed $title = null;
    /** @var int */
    public mixed $publication_year = null;
    /** @var string */
    public mixed $isbn                = null;
    public mixed $main_page_image_url = null;
    /** @var UploadedFile|null */
    public mixed $main_page_image = null;
    /** @var int[] */
    public mixed $authors = [];

    public static function createFromModel(Book $book): self
    {
        $form = new self();
        $form->setScenario(self::SCENARIO_UPDATE);
        $form->setAttributes([
            'id'                  => $book->id,
            'title'               => $book->title,
            'publication_year'    => $book->publication_year,
            'isbn'                => $book->isbn,
            'authors'             => array_column($book->authors, 'id'),
            'main_page_image_url' => $book->getFileUrl('main_page_image'),
        ]);

        return $form;
    }

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();

        $this->setScenario(self::SCENARIO_CREATE);
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AttributeTypecastBehavior::class,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id', 'on' => self::SCENARIO_UPDATE],
            [['main_page_image'], 'required', 'on' => self::SCENARIO_CREATE],
            [['title', 'publication_year', 'isbn', 'authors'], 'required'],
            [['authors'], 'exist', 'allowArray' => true, 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['title', 'main_page_image_url'], 'string', 'max' => 255],
            [['isbn'], 'string', 'length' => 13],
            [['publication_year'], 'integer', 'min' => 0, 'max' => 9999],
            [['main_page_image'], 'image'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'title'            => Yii::t('app/book', 'Title'),
            'publication_year' => Yii::t('app/book', 'Publication year'),
            'isbn'             => Yii::t('app/book', 'ISBN'),
            'main_page_image'  => Yii::t('app/book', 'Main page image'),
            'authors'          => Yii::t('app/book', 'Authors'),
        ]);
    }

    public function handle(Request $request): bool
    {
        if (!$this->load($request->post())) {
            return false;
        }

        $this->main_page_image = UploadedFile::getInstanceByName('main_page_image');

        return $this->validate();
    }
}
