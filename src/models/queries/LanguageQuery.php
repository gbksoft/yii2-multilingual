<?php
/**
 * Copyright © 2016 GBKSOFT. Web and Mobile Software Development.
 * See LICENSE.txt for license details.
 */
namespace gbksoft\multilingual\models\queries;

use gbksoft\multilingual\models\Language;
use yii\db\ActiveQuery;

/**
 * Class LanguageQuery
 */
class LanguageQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Language|null|array
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return Language[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
}
