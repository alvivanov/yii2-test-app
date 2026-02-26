<?php

namespace app\models\search;

use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * @property-read string authorsAsString
 */
final class BookSearch extends Book
{
    public ?string $authors_string = null;

    public function rules(): array
    {
        return [
            [['id', 'publication_year'], 'integer', 'min' => 1, 'max' => 2147483647],
            [['publication_year'], 'integer', 'min' => 1, 'max' => 9999],
            [['id', 'authors_string', 'publication_year'], 'default', 'value' => null],
            [['authors_string', 'title'], 'string', 'max' => 255],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $authorStringExpression = new Expression("GROUP_CONCAT(CONCAT(a.last_name, ' ', a.first_name, ' ', a.middle_name) ORDER BY a.id)");
        $query                  = self::find()
            ->alias('b')
            ->select(['b.*', 'authors_string' => $authorStringExpression])
            ->joinWith('authors a', false)
            ->groupBy('b.id');

        if ($this->load($params) && $this->validate()) {
            $query
                ->andFilterWhere(['b.id' => $this->id])
                ->andFilterWhere(['like', 'publication_year', $this->publication_year])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterHaving(['like', 'authors_string', $this->authors_string]);
        }

        return new ActiveDataProvider([
            'sort'       => [
                'attributes'   => [
                    'authors_string' => [
                        'asc'  => "$authorStringExpression->expression ASC, b.id ASC",
                        'desc' => "$authorStringExpression->expression DESC, b.id DESC",
                    ],
                    ...ArrayHelper::map(
                        ['id', 'title', 'publication_year', 'updated_at'],
                        static fn (string $attribute): string => $attribute,
                        static fn (string $attribute): array => [
                            'asc'  => [$attribute => SORT_ASC, 'id' => SORT_ASC],
                            'desc' => [$attribute => SORT_DESC, 'id' => SORT_DESC],
                        ],
                    ),
                ],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => ['defaultPageSize' => 20],
            'query'      => $query,
        ]);
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'authors_string' => Yii::t('app/book', 'Authors'),
        ]);
    }
}
