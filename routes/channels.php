<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('company.{id}', function ($user, $id) {
    return (int) $user->company_id === (int) $id;
});

