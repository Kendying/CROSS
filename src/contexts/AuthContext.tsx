import React, { createContext, useContext, useState, useEffect } from 'react';
import { User } from '../types/auth';

interface AuthContextType {
  user: User | null;
  login: (email: string, password: string) => Promise<boolean>;
  register: (userData: any) => Promise<boolean>;
  logout: () => void;
  isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  // Check for stored user on app start
  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
    setIsLoading(false);
  }, []);

  const login = async (email: string, password: string): Promise<boolean> => {
    setIsLoading(true);
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));

    // Mock validation - in real app, this would be an API call
    const mockUsers = {
      'scholar@university.edu': {
        id: '1',
        name: 'Sarah Johnson',
        email: 'scholar@university.edu',
        role: 'scholar' as const,
        course: 'Computer Science',
        year: '3rd Year',
        idNumber: '2021-12345'
      },
      'admin@servusamoris.org': {
        id: '2',
        name: 'Admin User',
        email: 'admin@servusamoris.org',
        role: 'admin' as const
      },
      'maria.cruz@university.edu': {
        id: '3',
        name: 'Maria Cruz',
        email: 'maria.cruz@university.edu',
        role: 'scholar' as const,
        course: 'Engineering',
        year: '2nd Year',
        idNumber: '2022-54321'
      }
    };

    const mockPasswords = {
      'scholar@university.edu': 'password123',
      'admin@servusamoris.org': 'admin123',
      'maria.cruz@university.edu': 'student123'
    };

    if (mockUsers[email as keyof typeof mockUsers] && mockPasswords[email as keyof typeof mockPasswords] === password) {
      const userData = mockUsers[email as keyof typeof mockUsers];
      setUser(userData);
      localStorage.setItem('user', JSON.stringify(userData));
      setIsLoading(false);
      return true;
    }

    setIsLoading(false);
    return false;
  };

  const register = async (userData: any): Promise<boolean> => {
    setIsLoading(true);
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000));

    // Mock registration - in real app, this would be an API call
    const newUser: User = {
      id: Date.now().toString(),
      name: userData.name,
      email: userData.email,
      role: 'scholar',
      course: userData.course,
      year: userData.year,
      idNumber: userData.idNumber
    };

    setUser(newUser);
    localStorage.setItem('user', JSON.stringify(newUser));
    setIsLoading(false);
    return true;
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('user');
  };

  return (
    <AuthContext.Provider value={{ user, login, register, logout, isLoading }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};