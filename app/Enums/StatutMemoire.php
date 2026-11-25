<?php

namespace App\Enums;

enum StatutMemoire: string
{
    case Soumis = 'soumis';
    case EnVerification = 'en_verification';
    case EnAttenteBinome = 'en_attente_binome';
    case Valide = 'valide';
    case Rejete = 'rejete';
}