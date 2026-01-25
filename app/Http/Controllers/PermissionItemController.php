<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionItemResource;
use App\Services\PermissionService;

class PermissionItemController extends Controller
{
    private PermissionService $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return PermissionItemResource::collection($this->service->listarPermissoes());
    }
}

