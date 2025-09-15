<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

$cartItemParam = $this->params['cartItemCount'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
    /* Backend-style search form - Exact replica */
    .navbar-search {
        width: 400px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    
    .navbar-search .input-group {
        position: relative;
    }
    
    .navbar-search .search-input-separated {
        background-color: #f8f9fc !important;
        border: 1px solid #e3e6f0 !important;
        border-radius: 0.35rem;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        color: #6c757d;
        margin-right: 10px;
        display: inline-block;
        vertical-align: middle;
        width: 300px;
    }
    
    .navbar-search .search-input-separated:focus {
        background-color: #fff !important;
        border-color: #bac8f3 !important;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        color: #6c757d;
    }
    
    .navbar-search .search-button-separated {
        border-radius: 0.35rem;
        border-color: #28a745;
        color: #28a745;
        background-color: transparent;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        display: inline-block;
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .navbar-search .search-button-separated {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .item-card {
        transition: transform 0.2s ease-in-out;
        height: 100%;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .item-card-img {
        height: 200px;
        object-fit: cover;
    }

    .item-card-footer {
        margin-top: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .navbar-search .search-input-separated {
            margin-right: 8px;
            width: 250px;
        }
    }
    
    @media (max-width: 576px) {
        .navbar-search .search-input-separated {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
            margin-right: 6px;
            width: 200px;
        }
        
        .navbar-search .search-button-separated {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
    }
    
    /* Hide search on very small screens */
    @media (max-width: 480px) {
        .navbar-search {
            display: none !important;
        }
    }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-expand-lg navbar-dark bg-primary fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        [
                'label' => 'Cart <span id="cart-quantity" class="badge bg-danger text-white"">'.$cartItemParam.'</span>',
                'url' => ['/cart/index'],
                'encode' => false,
        ]
        // ['label' => 'About', 'url' => ['/site/about']],
        // ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $username = Yii::$app->user->identity->getDisplayName();

        $menuItems[] = [
            'label' => $username,
            'items' => [
                ['label' => 'Profile', 'url' => ['/profile/index']],
                ['label' => 'Log out', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                // Add more items as needed
            ],
            'options' => ['class' => 'nav-item dropdown'],
            'linkOptions' => [
                'class' => 'nav-link dropdown-toggle',
                'data-bs-toggle' => 'dropdown',
                'role' => 'button',
                'aria-expanded' => 'false',
            ],
            'dropDownOptions' => ['class' => 'dropdown-menu'],
        ];
    }

    // Search form - Separated input and button
    echo Html::beginForm(['/site/search'], 'get', [
        'class' => 'd-none d-sm-inline-block form-inline mx-auto navbar-search'
    ]);
    
    // Search input
    echo Html::textInput('q', Yii::$app->request->get('q'), [
        'class' => 'form-control bg-light border-0 small search-input-separated',
        'placeholder' => 'Search for...',
        'aria-label' => 'Search',
        'aria-describedby' => 'basic-addon2'
    ]);
    
    // Search button - separated
    echo Html::submitButton('Search', [
        'class' => 'btn btn-outline-success my-2 my-sm-0 search-button-separated',
        'type' => 'submit'
    ]);
    
    echo Html::endForm();

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
