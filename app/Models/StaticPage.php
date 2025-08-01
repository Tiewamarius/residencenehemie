<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'slug',
    'content',
    'meta_description',
    'is_published',
    'auteur_id',
];

protected $casts = [
    'is_published' => 'boolean',
];
}