import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import { Navbar } from './components/Navbar';
import { Sidebar } from './components/Sidebar';
import { Footer } from './components/Footer';
import { Home } from './pages/Home';
import { Login } from './pages/Login';
import { Register } from './pages/Register';
import { Dashboard } from './pages/Dashboard';
import { Attendance } from './pages/Attendance';
import { Announcements } from './pages/Announcements';
import { AdminDashboard } from './pages/AdminDashboard';
import { ManageUsers } from './pages/ManageUsers';
import { ApplicationStatus } from './pages/scholar/ApplicationStatus';
import { ProfilePage } from './pages/scholar/ProfilePage';
import { AcademicProgress } from './pages/scholar/AcademicProgress';
import { FinancialAid } from './pages/scholar/FinancialAid';
import { RenewalApplication } from './pages/scholar/RenewalApplication';
import { NotesJournal } from './pages/scholar/NotesJournal';

// Protected Route Component
const ProtectedRoute: React.FC<{ children: React.ReactNode; requiredRole?: 'admin' | 'scholar' }> = ({ 
  children, 
  requiredRole 
}) => {
  const { user, isLoading } = useAuth();

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (!user) {
    return <Navigate to="/login" />;
  }

  if (requiredRole && user.role !== requiredRole) {
    return <Navigate to="/dashboard" />;
  }

  return <>{children}</>;
};

// Authenticated Layout Component
const AuthenticatedLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const { user } = useAuth();

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
      <Sidebar isOpen={sidebarOpen} userRole={user?.role || 'scholar'} />
      <div className="flex-1 flex flex-col lg:ml-0">
        <Navbar 
          onMenuToggle={() => setSidebarOpen(!sidebarOpen)} 
          userRole={user?.role || 'scholar'}
        />
        <main className="flex-1 p-6 lg:p-8 overflow-auto">
          {children}
        </main>
        <Footer />
      </div>
    </div>
  );
};

function AppContent() {
  return (
    <Router>
      <div className="App">
        <Routes>
          {/* Public Routes */}
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/programs" element={<Home />} />
          <Route path="/scholars" element={<Home />} />
          
          {/* Protected Routes */}
          <Route path="/dashboard" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <Dashboard />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          <Route path="/application-status" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <ApplicationStatus />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          <Route path="/profile" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <ProfilePage />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          <Route path="/attendance" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <Attendance />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          <Route path="/announcements" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <Announcements />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          {/* Scholar Pages */}
          <Route path="/academic-progress" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <AcademicProgress />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/financial-aid" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <FinancialAid />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/renewal" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <RenewalApplication />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/notes" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <NotesJournal />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/certificates" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <div className="text-center py-12">
                  <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Certificates
                  </h1>
                  <p className="text-gray-600 dark:text-gray-400">
                    Download your achievement certificates - Coming Soon
                  </p>
                </div>
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/settings" element={
            <ProtectedRoute>
              <AuthenticatedLayout>
                <div className="text-center py-12">
                  <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Settings
                  </h1>
                  <p className="text-gray-600 dark:text-gray-400">
                    Account and application settings - Coming Soon
                  </p>
                </div>
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          {/* Admin Routes */}
          <Route path="/admin/users" element={
            <ProtectedRoute requiredRole="admin">
              <AuthenticatedLayout>
                <ManageUsers />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/admin/dashboard" element={
            <ProtectedRoute requiredRole="admin">
              <AuthenticatedLayout>
                <AdminDashboard />
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />
          
          <Route path="/admin/analytics" element={
            <ProtectedRoute requiredRole="admin">
              <AuthenticatedLayout>
                <div className="text-center py-12">
                  <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Analytics Dashboard
                  </h1>
                  <p className="text-gray-600 dark:text-gray-400">
                    Advanced analytics and reporting features would be displayed here.
                  </p>
                </div>
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          <Route path="/admin/seminars" element={
            <ProtectedRoute requiredRole="admin">
              <AuthenticatedLayout>
                <div className="text-center py-12">
                  <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Seminar Management
                  </h1>
                  <p className="text-gray-600 dark:text-gray-400">
                    Create and manage seminars and workshops - Coming Soon
                  </p>
                </div>
              </AuthenticatedLayout>
            </ProtectedRoute>
          } />

          {/* Fallback route */}
          <Route path="*" element={<Navigate to="/" />} />
        </Routes>
      </div>
    </Router>
  );
}

function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
}

export default App;