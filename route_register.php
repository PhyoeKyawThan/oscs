<?php

include __DIR__ . '/libs/router.php';
include __DIR__ . '/libs/render.php';
require __DIR__ . '/sample_datas.php';
$routers = [
    "customer" => new Router(basePath: "sys_customer", baseTemplate: "partials/template"),
    "admin" => new Router(basePath: "sys_admin")
];
// register customer routes
$routers["customer"]->register(route: "/", path: "home/index", datas: [
    "title" => "Home",
    "featured_products" => array_slice($products, 0, 4),
]);
$routers["customer"]->register(route: "/products", path: "products/index", datas: [
    "title" => "Products",
    "products" => $products
]);
$routers["customer"]->register(
    route: "/product/{int:id}/detail",
    path: "products/detail",
    pattern: '/{int:([a-zA-Z_][a-zA-Z0-9_]*)}/',
    datas: [
        "title" => "Products",
        "product" => [],
        "callback" => function (int $Id, &$datas) {
            foreach ($GLOBALS['products'] as $product) {
                if ($product['id'] === $Id) {
                    if (isset($GLOBALS['products'])) {
                        $datas['product'] = $product;
                        // print_r($datas['product']);
                    }
                }
            }
        }
    ]
);
// register customer routes
$routers["admin"]->register(route: "/admin", path: "index");
$routers["admin"]->register(route: "/admin/orders", path: "orders");

Render::setRoutes($routers);
Render::to();