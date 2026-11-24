<?php

namespace App\Enums;

enum Role: string
{
    case Etudiant = 'etudiant';
    case Responsable = 'responsable';
    case ChefDepartement = 'chef_departement';
    case Admin = 'admin';
}