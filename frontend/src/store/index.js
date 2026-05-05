/**
 * Zustand Store — Auth & UI State
 */
import { create } from 'zustand';
import { authApi } from '../api/client';

export const useAuthStore = create((set) => ({
  user: null,
  isAuthenticated: false,
  isLoading: true,

  checkSession: async () => {
    try {
      const data = await authApi.checkSession();
      if (data && data.loggedIn) {
        set({ user: data.user, isAuthenticated: true, isLoading: false });
      } else {
        set({ user: null, isAuthenticated: false, isLoading: false });
      }
    } catch {
      set({ user: null, isAuthenticated: false, isLoading: false });
    }
  },

  login: async (email, password) => {
    const data = await authApi.login(email, password);
    if (data && (data.success || data.status === 'success')) {
      set({ user: data.user || { email }, isAuthenticated: true });
      return { success: true };
    }
    return { success: false, message: data?.message || 'Login failed' };
  },

  register: async (name, email, password) => {
    const data = await authApi.register(name, email, password);
    return data;
  },

  logout: async () => {
    try {
      await authApi.logout();
    } catch {
      // Logout even if API fails
    }
    localStorage.clear();
    sessionStorage.clear();
    set({ user: null, isAuthenticated: false });
  },
}));

export const useUIStore = create((set) => ({
  activeModal: null,
  toasts: [],

  openModal: (modalId) => set({ activeModal: modalId }),
  closeModal: () => set({ activeModal: null }),

  addToast: (toast) =>
    set((state) => ({
      toasts: [
        ...state.toasts,
        { id: Date.now(), ...toast },
      ],
    })),

  removeToast: (id) =>
    set((state) => ({
      toasts: state.toasts.filter((t) => t.id !== id),
    })),
}));
