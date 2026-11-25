<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMainNavItems()
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Tableau de bord',
                'path' => '/dashboard',
            ],
            [
                'icon' => 'pages',
                'name' => 'Catalogue public',
                'path' => '/catalogue',
            ],
            [
                'icon' => 'forms',
                'name' => 'Espace Étudiant',
                'subItems' => [
                    ['name' => 'Déposer un mémoire', 'path' => '/memoires/create'],
                    ['name' => 'Mes mémoires', 'path' => '/mes-memoires'],
                ],
            ],
            [
                'icon' => 'task',
                'name' => 'Validation des mémoires',
                'path' => '/validation',
            ],
            [
                'icon' => 'user-profile',
                'name' => 'Favoris & Historique',
                'subItems' => [
                    ['name' => 'Mes Favoris', 'path' => '/favoris'],
                    ['name' => 'Historique de lecture', 'path' => '/historique'],
                ],
            ],
        ];
    }

    public static function getAdminItems()
    {
        return [
            [
                'icon' => 'tables',
                'name' => 'Administration',
                'subItems' => [
                    ['name' => 'Utilisateurs', 'path' => '/admin/users'],
                    ['name' => 'Filières', 'path' => '/admin/filieres'],
                    ['name' => 'Années académiques', 'path' => '/admin/annees'],
                    ['name' => 'Statistiques globales', 'path' => '/admin/statistiques'],
                ],
            ],
            [
                'icon' => 'user-profile',
                'name' => 'Mon Profil',
                'path' => '/profile',
            ],
        ];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu Principal',
                'items' => self::getMainNavItems(),
            ],
            [
                'title' => 'Gestion & Admin',
                'items' => self::getAdminItems(),
            ],
        ];
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 20.75 5.5 20.75H9C11.25 20.75 11.25 18.5V15H5.5ZM12.75 5.5V8.99998H18.5V5.5H12.75ZM12.75 15V18.5H18.5V15H12.75Z" fill="currentColor"></path></svg>',
            'pages' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.5 4.25H15.28L18.5 6.72V16.75H14.75V19H6.25V7.25H8.5V4.25ZM7 4.25C7 3.01 8.01 2 9.25 2H15.28C15.87 2 16.45 2.24 16.87 2.66L19.34 5.13C19.76 5.55 20 6.13 20 6.72V16.75C20 17.99 18.99 19 17.75 19H16.25V19.75C16.25 20.99 15.24 22 14 22H6.25C5.01 22 4 20.99 4 19.75V7.25C4 6.01 5.01 5 6.25 5H7V4.25Z" fill="currentColor"></path></svg>',
            'forms' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25H18.5C19.74 3.25 20.75 4.26 20.75 5.5V18.5C20.75 19.74 19.74 20.75 18.5 20.75H5.5C4.26 20.75 3.25 19.74 3.25 18.5V5.5C3.25 4.26 4.26 3.25 5.5 3.25ZM7 9H17V10.5H7V9ZM7 13.5H17V15H7V13.5Z" fill="currentColor"></path></svg>',
            'task' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.5 4.75H18.5V15.5H8.5V4.75ZM8.5 3.25C7.26 3.25 6.25 4.26 6.25 5.5V6.26H5.5C4.26 6.26 3.25 7.27 3.25 8.51V18.5C3.25 19.74 4.26 20.75 5.5 20.75H15.5C16.73 20.75 17.74 19.74 17.74 18.5H18.5C19.74 18.5 20.75 17.49 20.75 16.25V5.5C20.75 4.26 19.74 3.25 18.5 3.25H8.5Z" fill="currentColor"></path></svg>',
            'tables' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 5.5C3.25 4.26 4.26 3.25 5.5 3.25H18.5C19.74 3.25 20.75 4.26 20.75 5.5V18.5C20.75 19.74 19.74 20.75 18.5 20.75H5.5C4.26 20.75 3.25 19.74 3.25 18.5V5.5ZM19.25 10H15.42V14H19.25V10ZM13.92 10H10.08V14H13.92V10ZM8.58 10H4.75V14H8.58V10Z" fill="currentColor"></path></svg>',
            'user-profile' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.3 3.5 3.5 7.3 3.5 12C3.5 14.15 4.3 16.12 5.62 17.62C6.17 15.31 8.25 13.6 10.72 13.6H13.27C15.75 13.6 17.83 15.31 18.38 17.62C19.7 16.12 20.5 14.15 20.5 12C20.5 7.3 16.7 3.5 12 3.5ZM2 12C2 6.48 6.48 2 12 2C17.52 2 22 6.48 22 12C22 17.52 17.52 22 12 22C6.48 22 2 17.52 2 12ZM12 7.25C10.88 7.25 9.98 8.15 9.98 9.27C9.98 10.38 10.88 11.29 12 11.29C13.11 11.29 14.02 10.38 14.02 9.27C14.02 8.15 13.11 7.25 12 7.25Z" fill="currentColor"></path></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/></svg>';
    }
}
