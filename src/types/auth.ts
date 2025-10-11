export interface LoginFormData {
  email: string;
  password: string;
  rememberMe: boolean;
}

export interface RegisterFormData {
  name: string;
  email: string;
  course: string;
  year: string;
  idNumber: string;
  password: string;
  confirmPassword: string;
  acceptTerms: boolean;
}

export interface User {
  id: string;
  name: string;
  email: string;
  role: 'admin' | 'scholar';
  course?: string;
  year?: string;
  idNumber?: string;
}