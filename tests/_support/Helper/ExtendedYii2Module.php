<?php

namespace Helper;

use Codeception\Module\Yii2;
use yii\helpers\ArrayHelper;

final class ExtendedYii2Module extends Yii2
{
    public function amOmPagePost(string $uri, array $parameters = [], array $files = [], array $server = [], ?string $content = null)
    {
        return $this->_request('POST', $uri, $parameters, $files, $server, $content);
    }

    public function assertGrid(array $modelIdsOnPage, array $data, array $columns = [], string $primaryKey = 'id'): void
    {
        if (count($modelIdsOnPage) > 0) {
            $this->assertCount(count($modelIdsOnPage), $this->grabMultiple('tbody tr'));
        } else {
            $this->see('No results found.', 'tbody tr td .empty');
        }

        $indexedData = ArrayHelper::index($data, $primaryKey);

        foreach ($modelIdsOnPage as $i => $modelId) {
            if ($row = $indexedData[$modelId] ?? null) {
                $selector = sprintf('tbody tr:nth-child(%s) td', $i + 1);

                foreach ($columns as $column) {
                    $this->see(is_callable($column) ? call_user_func($column, $row) : $row[$column], $selector);
                }
            }
        }
    }

    protected function getFormPhpValues(array $requestParams): array
    {
        foreach ($requestParams as $name => $value) {
            $qs = http_build_query([$name => $value]);
            if (!empty($qs)) {
                // If the field's name is of the form of "array[key]",
                // we'll remove it from the request parameters
                // and set the "array" key instead which will contain the actual array.
                if (strpos($name, '[') && strpos($name, ']') > strpos($name, '[')) {
                    unset($requestParams[$name]);
                }

                parse_str($qs, $expandedValue);
            }
        }

        return $requestParams;
    }
}
