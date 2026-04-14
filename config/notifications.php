<?php

return [
    'middleware' => ['api', 'introspect'],

    'route_prefix' => '',

    'employee_model' => \App\Models\Employee::class,
];
