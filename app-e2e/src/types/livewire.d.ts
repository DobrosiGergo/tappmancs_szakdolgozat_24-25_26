declare global {
    interface Window {
        Livewire?: {
            all(): unknown[];
        };
    }
}

export {};
