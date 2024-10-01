<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DeliveryPersonController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DeliveryAddressController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ViaCepController;
use App\Http\Controllers\Admin\OrderTypeController;
use App\Http\Controllers\Admin\OrderOriginController;

use App\Http\Controllers\Site\BannerController;

// Grupo de rotas para o Admin
Route::prefix('admin-v1')->group(function () {
    Route::get('status', function () {
        return response()->json(['status' => 'API Admin V1 is alive!'], 200);
    });

    // Rotas públicas
    Route::post('login', [AuthController::class, 'login']);

    // Rotas privadas
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/check-token-validity', [AuthController::class, 'checkTokenValidity']);

        // Rotas para autenticação
        Route::post('logout', [AuthController::class, 'logout']);

        //Rotas de Roles e permissons
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('roles', RoleController::class);

        //Rota de usuários do sistema
        Route::apiResource('users', UserController::class);
        Route::get('/me', [UserController::class, 'showLoggedUser']);

        // Rotas para Itens
        Route::apiResource('items', ItemController::class);
        Route::get('/items/category/{categoryId}', [ItemController::class, 'showByCategoryId']);

        // Rotas para Categorias
        Route::apiResource('categories', CategoryController::class);
        Route::get('/categories-with-company-items', [CategoryController::class, 'listCategoriesWithCompanyItems']);

        // Rotas para Clientes
        Route::apiResource('customers', CustomerController::class);
        Route::get('/customers-search', [CustomerController::class, 'SearchCustomer']);

        // Rotas para Endereços de Entrega
        Route::apiResource('delivery-addresses', DeliveryAddressController::class);

        // Rotas para Pedidos
        Route::apiResource('orders', OrderController::class);
        Route::get('/orders-location/has-location', [OrderController::class, 'orderByLocation']);
        Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus']);
        Route::put('/orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus']);
        Route::post('/orders/get-last-status-and-reativate', [OrderController::class, 'getLastStatusAndReativate']);
        Route::put('/orders/{order}/set-delivery-person', [OrderController::class, 'setDeliveryPerson']);
        Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem']);
        Route::post('/orders/{order}/remove-item', [OrderController::class, 'removeItem']);
        Route::post('/orders/{order}/update-item-quantity', [OrderController::class, 'updateItemQuantity']);

        Route::post('/orders/{order}/update-item', [OrderController::class, 'updateItem']);
        Route::post('/orders/{order}/update-item-price', [OrderController::class, 'updateItemPrice']);

        // Rotas de tipo de pedidos
        Route::apiResource('/order-types', OrderTypeController::class);

        // Rotas de origem de pedidos
        Route::apiResource('/order-origins', OrderOriginController::class);

        // Rotas para Avaliações
        Route::apiResource('reviews', ReviewController::class);

        // Rotas para Entregadores
        Route::apiResource('delivery-persons', DeliveryPersonController::class);

        // Rotas para Status
        Route::apiResource('statuses', StatusController::class);

        // Rotas para Métodos de Pagamento
        Route::apiResource('payment-methods', PaymentMethodController::class);

        // Rotas para Pagamentos
        Route::apiResource('payments', PaymentController::class);

        // Rotas de configuração
        Route::apiResource('settings', SettingController::class);

        // Rotas para Empresas
        Route::apiResource('companies', CompanyController::class);

        // Rotas para Estoque
        Route::apiResource('stocks', StockController::class);

        // Rotas para CEP
        Route::get('/zip-code/{zipCode}', [ViaCepController::class, 'search']);
    });
});

// Grupo de rotas para o Site
Route::prefix('site-v1')->group(function () {
    Route::get('status', function () {
        return response()->json(['status' => 'API Site V1 is alive!'], 200);
    });

    // Rotas de banners do site
    Route::apiResource('banners', BannerController::class);
});

// Grupo de rotas para o Bot
Route::prefix('bot-v1')->group(function () {
    Route::get('status', function () {
        return response()->json(['status' => 'API Bot V1 is alive!'], 200);
    });
});