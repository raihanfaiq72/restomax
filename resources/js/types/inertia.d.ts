import { Page } from '@inertiajs/core';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Flash {
  success?: string;
  error?: string;
}

interface InertiaProps {
  auth: {
    user: User | null;
  };
  flash: Flash;
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaProps {}
}

declare module 'vue' {
  interface ComponentCustomProperties {
    $page: Page<InertiaProps>;
  }
}