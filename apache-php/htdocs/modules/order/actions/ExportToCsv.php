<?php

namespace app\modules\order\actions;

use InvalidArgumentException;
use Yii;
use yii\data\ActiveDataProvider;

class ExportToCsv
{

    const CSV_SEPARATOR = ',';
    const CSV_ENCLOSURE = '"';

    const ITERATION_BATCH_SIZE = 10000;

    /**
     * Saves all records from data provider to local .csv file
     *
     * @param ActiveDataProvider $dataProvider - datasource
     * @param ModelToFlatArrMapperInterface $modelMapper - function that maps
     * @return string - absolute local filepath to .csv file
     */
    public function export(ActiveDataProvider $dataProvider, ModelToFlatArrMapperInterface $modelMapper): string
    {
        if ($dataProvider->query === null) {
            throw new InvalidArgumentException('query is not initialized in dataProvider');
        }

        $filepath = tempnam(Yii::getAlias('@runtime'), sprintf('_csv_export_%d_', time()));
        $file = fopen($filepath, 'w');

        $dataProvider->pagination->page = 0;
        $dataProvider->pagination->pageSize = self::ITERATION_BATCH_SIZE;
        $dataProvider->refresh();

        while(true){
            $res = $dataProvider->getModels();
            if (empty($res)) {
                break;
            }

            foreach ($res as $key => $model) {
                $mappedArr = $modelMapper($model);
                $this->writeRow($file, $mappedArr, $dataProvider->pagination->page === 0 && $key === 0);
            }

            $dataProvider->pagination->page += 1;
            $dataProvider->refresh();
        }

        fclose($file);
        return $filepath;
    }

    /**
     * @param resource $fileResource - writable resource
     * @param array $row - array values from will be saved
     * @param bool $writeHeaders - prepend array keys of $row and store them as csv row
     * @return void
     */
    private function writeRow($fileResource, array $row, bool $writeHeaders = false): void
    {
        if ($writeHeaders) {
            $res = fputcsv($fileResource, array_keys($row), self::CSV_SEPARATOR, self::CSV_ENCLOSURE);
            if ($res === false) {
                throw new \RuntimeException('Unable to write headers to a file');
            }
        }

        $res = fputcsv($fileResource, $row, self::CSV_SEPARATOR, self::CSV_ENCLOSURE);
        if ($res === false) {
            throw new \RuntimeException('Unable to write content row to a file');
        }
    }

}