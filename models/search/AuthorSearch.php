<?php

namespace app\models\search;

use app\models\Author;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

final class AuthorSearch extends Author
{
    private const string SCENARIO_TOP_AUTHORS_BY_BOOK_COUNT = 'topAuthorsByBookCount';

    public ?int    $year      = null;
    public ?int    $limit     = null;
    public ?string $full_name = null;

    public function rules(): array
    {
        return [
            [['id'], 'integer', 'min' => 1, 'max' => 2147483647],
            [['id', 'full_name'], 'default', 'value' => null],
            [['full_name'], 'string', 'max' => 50],
            [['year'], 'integer', 'min' => 0, 'max' => 9999, 'on' => self::SCENARIO_TOP_AUTHORS_BY_BOOK_COUNT],
            [['limit'], 'integer', 'min' => 0, 'max' => 100, 'on' => self::SCENARIO_TOP_AUTHORS_BY_BOOK_COUNT],
            [['limit', 'year'], 'required', 'on' => self::SCENARIO_TOP_AUTHORS_BY_BOOK_COUNT],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $this->scenario     = self::SCENARIO_DEFAULT;
        $fullNameExpression = new Expression("CONCAT(last_name, ' ', first_name, ' ', middle_name)");
        $query              = self::find()->select(['*', 'full_name' => $fullNameExpression]);

        if ($this->load($params) && $this->validate()) {
            $query
                ->andFilterWhere(['id' => $this->id])
                ->andFilterWhere(['like', $fullNameExpression, $this->full_name]);
        }

        return new ActiveDataProvider([
            'sort'       => [
                'attributes'   => ['id', 'full_name', 'created_at', 'updated_at'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => ['defaultPageSize' => 20],
            'query'      => $query,
        ]);
    }

    public function searchTopByBookCount(int $year, int $limit): ActiveDataProvider
    {
        $query          = self::find()->limit(0);
        $this->scenario = self::SCENARIO_TOP_AUTHORS_BY_BOOK_COUNT;

        if ($this->setAttributes(['year' => $year, 'limit' => $limit]) && $this->validate()) {
            $query
                ->alias('a')
                ->innerJoinWith('books b')
                ->andWhere(['b.publication_year' => $this->year])
                ->groupBy('a.id')
                ->orderBy(new Expression('COUNT(b.id) DESC, a.id'))
                ->limit($this->limit);
        }

        return new ActiveDataProvider(['pagination' => false, 'sort' => false, 'query' => $query]);
    }

    public function searchForDropdown(string $from = 'id', string $to = 'fullName', ?int $limit = null): array
    {
        return ArrayHelper::map(self::find()->limit($limit)->all(), $from, $to);
    }
}
