<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\Rates;
use app\models\YahooRateProvider;
use app\models\CbrRateProvider;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $rates = new Rates();
        return $this->render('index', [
            'latestRate' => $rates->latest,
        ]);
    }

    public function actionUpdate()
    {
        $yahoo = new YahooRateProvider();
        $yahooRateValues = $yahoo->getRateValues();
        $yahooRate = new Rates();
        $yahooQuery = $yahooRate->find()->source('Yahoo')->date($yahooRateValues['date'])->one();
        if(!$yahooQuery){
            $yahooRate->id = 0;
            $yahooRate->date = $yahooRateValues['date'];
            $yahooRate->rate = $yahooRateValues['rates'];
            $yahooRate->source = "Yahoo";
            $yahooRate->save();
        } else {
            $yahooQuery->rate = $yahooRateValues['rates'];
            $yahooQuery->update();
        }

        $cbr = new CbrRateProvider();
        $cbrRateValues = $cbr->getRateValues();
        $cbrRate = new Rates();
        $cbrQuery = $cbrRate->find()->source('Cbr')->date($cbrRateValues['date'])->one();
        if(!$cbrQuery){
            $cbrRate->id = 0;
            $cbrRate->date = $cbrRateValues['date'];
            $cbrRate->rate = $cbrRateValues['rates'];
            $cbrRate->source = "Cbr";
            $cbrRate->save();
        } else {
            $cbrQuery->rate = $cbrRateValues['rates'];
            $cbrQuery->update();
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
