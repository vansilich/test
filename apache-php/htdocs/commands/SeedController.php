<?php

namespace app\commands;

use InvalidArgumentException;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

class SeedController extends Controller
{

    /**
     * Run seeding from .sql file for all SQL DB tables
     *
     * @param string $seeds_path - relative from @app path to seed file
     * @return int
     * @throws Exception
     */
    public function actionRawAll(string $seeds_path = '.data/test_db_data.sql'): int
    {
        $abs_path = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . ltrim($seeds_path, '\\/');
        if (!file_exists($abs_path)){
            echo sprintf('"%s" file don`t exist', $abs_path);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        Yii::$app->db->createCommand(file_get_contents($abs_path))->execute();

        return ExitCode::OK;
    }

}