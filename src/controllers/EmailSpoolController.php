<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\emailmanager\AmosEmail;
use open20\amos\emailmanager\models\EmailSpool;
use open20\amos\emailmanager\models\search\EmailSpoolSearch;
use open20\amos\admin\AmosAdmin;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class EmailSpoolController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new EmailSpool());
        $this->setModelSearch(new EmailSpoolSearch());
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosEmail::t('amosemail', '{iconaTabella}' . Html::tag('p', AmosEmail::t('amosemail', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);
        parent::init();
        $this->setUpLayout();
    }

    /**
     * @return mixed
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'preview'
                        ],
                        'roles' => ['AMMINISTRATORE_EMAIL_MANAGER']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);

        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosEmail::t('amosemail', 'Email Manager');
            $urlLinkAll   = '';

            $ctaLoginRegister = Html::a(
                AmosEmail::t('amosemail', '#beforeActionCtaLoginRegister'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => AmosEmail::t('amosemail',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name]
                    )
                ]
            );
            $subTitleSection  = Html::tag(
                'p',
                AmosEmail::t('amosemail',
                    '#beforeActionSubtitleSectionGuest',
                    ['ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosEmail::t('amosemail', 'Coda Email');
            // $labelLinkAll = AmosEmail::t('amosemail', 'Template Email');
            // $urlLinkAll   = AmosEmail::t('amosemail', '/email/template');
            // $titleLinkAll = AmosEmail::t('amosemail', 'Visualizza la coda delle email');

            $labelLinkAll = AmosEmail::t('amosemail', '');
            $urlLinkAll   = AmosEmail::t('amosemail', '');
            $titleLinkAll = AmosEmail::t('amosemail', '');

            $subTitleSection = Html::tag('p', AmosEmail::t('amosemail', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = AmosEmail::t('amosemail', '');
        $titleCreate = AmosEmail::t('amosemail', '');
        $labelManage = AmosEmail::t('amosemail', '');
        $titleManage = AmosEmail::t('amosemail', '');
        $urlCreate   = AmosEmail::t('amosemail', '');
        $urlManage   = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'emailmanager',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'hideCreate' => true,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
            'hideManage' => true
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        $links[] = [];

        if (\Yii::$app->user->can(\open20\amos\emailmanager\widgets\icons\WidgetIconTemplate::class)) {
            $links[] = [
                'title' => AmosEmail::t('amosemail', 'Gestisci template email'),
                'label' => AmosEmail::t('amosemail', 'Template email'),
                'url' => '/email/template',
            ];
        }
        return $links;
    }

    /**
     * Lists all EmailSpool models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        $this->setUpLayout('list');
        $this->view->params['containerFullWidth'] = true;

        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single EmailSpool model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->setUpLayout('main');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EmailSpool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $model = new EmailSpool();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EmailSpool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EmailSpool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Preview
     * @param $id
     */
    public function actionPreview($id)
    {
        $emailSpool = $this->loadModel($id);
        echo CHtml::tag('div', array('style' => 'font-family: Arial; font-weight: bold;'), $emailSpool->subject) . '<hr/>';
        echo $emailSpool->swiftMessage->getBody();
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}
