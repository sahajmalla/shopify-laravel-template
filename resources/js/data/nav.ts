export type AppNavItem = {
    name: string;
    href: string;
    rel?: string;
};

export const appNavItems: AppNavItem[] = [
    { name: 'Home', href: '/', rel: 'home' },
    { name: 'Settings', href: '/settings' },
];
