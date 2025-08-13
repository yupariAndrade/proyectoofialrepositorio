import { AppPageProps } from '@/types/index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}

// Global type definitions for Vue components
declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

// Global types for Inertia
declare global {
  interface Window {
    route: (name: string, params?: any) => string
  }
}

// Vue 3 Composition API types
declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $route: (name: string, params?: any) => string
  }
}

// Inertia types
declare module '@inertiajs/vue3' {
  export function useForm<T = Record<string, any>>(data?: T): {
    data: T
    errors: Record<string, string>
    processing: boolean
    progress: number | null
    wasSuccessful: boolean
    recentlySuccessful: boolean
    isDirty: boolean
    hasErrors: boolean
    transform: (callback: (data: T) => T) => void
    get: (url: string, options?: any) => void
    post: (url: string, options?: any) => void
    put: (url: string, options?: any) => void
    patch: (url: string, options?: any) => void
    delete: (url: string, options?: any) => void
    reset: (...fields: (keyof T)[]) => void
    clearErrors: (...fields: (keyof T)[]) => void
    setError: (field: keyof T, value: string) => void
    removeError: (field: keyof T) => void
  }

  export function router: {
    get: (url: string, options?: any) => void
    post: (url: string, options?: any) => void
    put: (url: string, options?: any) => void
    patch: (url: string, options?: any) => void
    delete: (url: string, options?: any) => void
    reload: (options?: any) => void
    visit: (url: string, options?: any) => void
  }

  export const Link: any
}

// Route helper type
declare function route(name: string, params?: any): string
