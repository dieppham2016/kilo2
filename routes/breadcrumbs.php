<?php


Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});
