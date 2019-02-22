<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\email
 * @category   CategoryName
 */

use lispa\amos\emailmanager\AmosEmail;
use lispa\amos\emailmanager\models\EmailTemplate;

/* @var $this yii\web\View */
/* @var $model EmailTemplate */

$this->title = 'Update Email Template: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => AmosEmail::t('amosemail','Email Templates'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="email-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
