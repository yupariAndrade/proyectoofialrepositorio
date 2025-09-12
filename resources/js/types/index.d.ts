import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    nombre: string;
    apellidoPaterno: string | null;
    apellidoMaterno: string | null;
    ci: string | null;
    telefono: string | null;
    direccion: string | null;
    email: string | null;
    password: string | null;
    fechaIngreso: string | null;
    fechaFinal: string | null;
    estado: boolean;
    idRol: number;
    created_at: string;
    updated_at: string;
    rol?: {
        id: number;
        nombre: string;
    };
    isAdmin?: boolean;
    isEmpleado?: boolean;
    isGerente?: boolean;
    isEncargado?: boolean;
}

export type BreadcrumbItemType = BreadcrumbItem;
