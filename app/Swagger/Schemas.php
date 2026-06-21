<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'google_id', type: 'string', nullable: true),
        new OA\Property(property: 'avatar', type: 'string', nullable: true),
        new OA\Property(property: 'auth_provider', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'company', ref: '#/components/schemas/Company', type: 'object', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'Company',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'logo', type: 'string', nullable: true),
        new OA\Property(property: 'phone', type: 'string'),
        new OA\Property(property: 'email', type: 'string', nullable: true),
        new OA\Property(property: 'address', type: 'string'),
        new OA\Property(property: 'manager_name', type: 'string'),
        new OA\Property(property: 'signature', type: 'string', nullable: true),
        new OA\Property(property: 'stamp', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
)]
#[OA\Schema(
    schema: 'Client',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'phone', type: 'string', nullable: true),
        new OA\Property(property: 'address', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
)]
#[OA\Schema(
    schema: 'Document',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'company_id', type: 'integer'),
        new OA\Property(property: 'client_id', type: 'integer'),
        new OA\Property(property: 'type', type: 'string', enum: ['quote', 'invoice']),
        new OA\Property(property: 'number', type: 'string'),
        new OA\Property(property: 'project_name', type: 'string'),
        new OA\Property(property: 'issue_date', type: 'string', format: 'date'),
        new OA\Property(property: 'valid_until', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'notes', type: 'string', nullable: true),
        new OA\Property(property: 'subtotal', type: 'string'),
        new OA\Property(property: 'labor_cost', type: 'string'),
        new OA\Property(property: 'transport_cost', type: 'string'),
        new OA\Property(property: 'other_cost', type: 'string'),
        new OA\Property(property: 'total', type: 'string'),
        new OA\Property(property: 'total_in_words', type: 'string', nullable: true),
        new OA\Property(property: 'pdf_template', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'client', ref: '#/components/schemas/Client', type: 'object', nullable: true),
        new OA\Property(property: 'company', ref: '#/components/schemas/Company', type: 'object', nullable: true),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/DocumentItem')),
    ],
)]
#[OA\Schema(
    schema: 'DocumentItem',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'document_id', type: 'integer'),
        new OA\Property(property: 'designation', type: 'string'),
        new OA\Property(property: 'quantity', type: 'string'),
        new OA\Property(property: 'unit_price', type: 'string'),
        new OA\Property(property: 'total_price', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
)]
class Schemas {}
