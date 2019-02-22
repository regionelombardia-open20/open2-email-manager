<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\email
 * @category   CategoryName
 */

use lispa\amos\emailmanager\models\EmailSpool;
use yii\db\Migration;

class m170220_161458_add_viewparams extends Migration
{
    public function safeUp()
    {
        $this->addColumn(EmailSpool::tableName(), 'viewParams', 'LONGBLOB DEFAULT NULL AFTER files');

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(EmailSpool::tableName(), 'viewParams');

        return true;
    }
}
