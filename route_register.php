<?php

include __DIR__.'/libs/router.php';
include __DIR__.'/libs/render.php';

$routers = [
    "customer" => new Router(basePath: "sys_customer"),
    "admin" => new Router(basePath: "sys_admin")
];
// register customer routes
$routers["customer"]->register(route: "/", path: "index",);
$routers["customer"]->register(route: "/products", path: "products/index", datas:[
    "title" => "Products",
    "text" => "This is a rendered var text"
]);
// register customer routes
$routers["admin"]->register(route: "/admin", path: "index");
$routers["admin"]->register(route: "/admin/orders", path: "orders");

Render::setRoutes($routers);
Render::to();