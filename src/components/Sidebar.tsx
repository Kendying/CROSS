import React from 'react';
import { 
  LayoutDashboard, 
  Calendar, 
  Megaphone, 
  Users, 
  Settings, 
  LogOut,
  GraduationCap,
  BarChart3,
  User,
  FileText,
  TrendingUp,
  DollarSign,
  RefreshCw,
  Notebook,
  Heart
} from 'lucide-react';

interface SidebarProps {
  isOpen: boolean;
  userRole: 'admin' | 'scholar';
}

export const Sidebar: React.FC<SidebarProps> = ({ isOpen, userRole }) => {
  const scholarMenuItems = [
    { icon: LayoutDashboard, label: 'Dashboard', href: '/dashboard' },
    { icon: User, label: 'My Profile', href: '/profile' },
    { icon: FileText, label: 'Application Status', href: '/application-status' },
    { icon: Calendar, label: 'Seminars', href: '/seminars' },
    { icon: TrendingUp, label: 'Academic Progress', href: '/academic-progress' },
    { icon: DollarSign, label: 'Financial Aid', href: '/financial-aid' },
    { icon: RefreshCw, label: 'Renewal', href: '/renewal' },
    { icon: Megaphone, label: 'Announcements', href: '/announcements' },
    { icon: Notebook, label: 'My Notes', href: '/notes' },
    { icon: GraduationCap, label: 'Certificates', href: '/certificates' },
    { icon: Settings, label: 'Settings', href: '/settings' },
  ];

  const adminMenuItems = [
    { icon: LayoutDashboard, label: 'Dashboard', href: '/dashboard' },
    { icon: Users, label: 'Manage Users', href: '/admin/users' },
    { icon: Calendar, label: 'Seminar Management', href: '/admin/seminars' },
    { icon: BarChart3, label: 'Analytics', href: '/admin/analytics' },
    { icon: Megaphone, label: 'Announcements', href: '/announcements' },
    { icon: Settings, label: 'Settings', href: '/settings' },
  ];

  const menuItems = userRole === 'admin' ? adminMenuItems : scholarMenuItems;

  return (
    <aside className={`
      fixed inset-y-0 left-0 z-50 w-64 bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg 
      border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300
      lg:translate-x-0 lg:static lg:inset-0 shadow-caritas
      ${isOpen ? 'translate-x-0' : '-translate-x-full'}
    `}>
      {/* Logo */}
      <div className="p-6 border-b border-gray-200 dark:border-gray-700">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center">
            <GraduationCap className="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 className="text-xl font-bold text-gray-900 dark:text-white">
              CROSS
            </h1>
            <div className="flex items-center space-x-1">
              <Heart className="w-3 h-3 text-amber-500" />
              <p className="text-xs text-gray-500 dark:text-gray-400">Servus Amoris</p>
            </div>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="p-4 space-y-1">
        {menuItems.map((item) => (
          <a
            key={item.label}
            href={item.href}
            className="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 
                       hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400
                       transition-all duration-200 group border border-transparent hover:border-blue-200 dark:hover:border-blue-800"
          >
            <item.icon className="w-5 h-5 opacity-70 group-hover:opacity-100" />
            <span className="font-medium">{item.label}</span>
          </a>
        ))}
        
        {/* Logout */}
        <button className="flex items-center space-x-3 w-full px-4 py-3 rounded-xl text-red-600 dark:text-red-400 
                          hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group mt-8 border border-transparent hover:border-red-200 dark:hover:border-red-800">
          <LogOut className="w-5 h-5 opacity-70 group-hover:opacity-100" />
          <span className="font-medium">Logout</span>
        </button>
      </nav>
    </aside>
  );
};