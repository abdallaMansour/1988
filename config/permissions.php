<?php

return [
    'groups' => [
        'admins' => [
            'label' => 'إدارة الأدمن',
            'permissions' => [
                'admins.view' => 'عرض الأدمن',
                'admins.create' => 'إنشاء أدمن',
                'admins.edit' => 'تعديل أدمن',
                'admins.delete' => 'حذف أدمن',
            ],
        ],
        'roles' => [
            'label' => 'إدارة الأدوار',
            'permissions' => [
                'roles.view' => 'عرض الأدوار',
                'roles.create' => 'إنشاء دور',
                'roles.edit' => 'تعديل دور',
                'roles.delete' => 'حذف دور',
            ],
        ],
        'permissions' => [
            'label' => 'إدارة الصلاحيات',
            'permissions' => [
                'permissions.view' => 'عرض الصلاحيات',
                'permissions.edit' => 'تعديل الصلاحيات',
            ],
        ],
        'users' => [
            'label' => 'المستخدمين',
            'permissions' => [
                'users.view' => 'عرض المستخدمين',
                'users.edit' => 'تعديل المستخدم',
                'users.delete' => 'حذف المستخدم',
                'users.ban' => 'حظر المستخدم',
            ],
        ],
        'packages' => [
            'label' => 'الباقات',
            'permissions' => [
                'packages.view' => 'عرض الباقات',
                'packages.create' => 'إنشاء باقة',
                'packages.edit' => 'تعديل باقة',
                'packages.delete' => 'حذف باقة',
            ],
        ],
        'subscriptions' => [
            'label' => 'الإشتراكات',
            'permissions' => [
                'subscriptions.view' => 'عرض الإشتراكات',
                'subscriptions.manage' => 'إدارة الإشتراكات',
            ],
        ],
        'content' => [
            'label' => 'المحتوى',
            'permissions' => [
                'faq.view' => 'عرض الأسئلة الشائعة',
                'faq.manage' => 'إدارة الأسئلة الشائعة',
                'features.view' => 'عرض المميزات',
                'features.manage' => 'إدارة المميزات',
            ],
        ],
        'support' => [
            'label' => 'الدعم الفني',
            'permissions' => [
                'support-tickets.view' => 'عرض التذاكر',
                'support-tickets.manage' => 'إدارة التذاكر',
                'technical-support.view' => 'عرض المراسلات',
                'technical-support.manage' => 'إدارة المراسلات',
            ],
        ],
        'settings' => [
            'label' => 'الإعدادات',
            'permissions' => [
                'site-settings.manage' => 'إدارة إعدادات الموقع',
                'media-department.manage' => 'إدارة القسم الإعلامي',
            ],
        ],
    ],
];
