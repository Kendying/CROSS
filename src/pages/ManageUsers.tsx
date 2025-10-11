import React, { useState } from 'react';
import { Search, MoreVertical, Edit3, UserCheck, UserX } from 'lucide-react';


interface User {
  id: string;
  name: string;
  email: string;
  course: string;
  year: string;
  status: 'active' | 'pending' | 'inactive';
  joinDate: string;
  lastActive: string;
}

export const ManageUsers: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState<'all' | 'active' | 'pending' | 'inactive'>('all');

  // Mock users data
  const users: User[] = [
    {
      id: '1',
      name: 'Sarah Johnson',
      email: 'sarah.j@university.edu',
      course: 'Information Technology',
      year: '3rd Year',
      status: 'active',
      joinDate: '2023-08-15',
      lastActive: '2024-02-01'
    },
    {
      id: '2',
      name: 'Michael Chen',
      email: 'michael.c@university.edu',
      course: 'Engineering',
      year: '2nd Year',
      status: 'pending',
      joinDate: '2024-01-20',
      lastActive: '2024-01-28'
    },
    {
      id: '3',
      name: 'Maria Garcia',
      email: 'maria.g@university.edu',
      course: 'Business Administration',
      year: '4th Year',
      status: 'active',
      joinDate: '2023-09-10',
      lastActive: '2024-02-01'
    },
    {
      id: '4',
      name: 'David Kim',
      email: 'david.k@university.edu',
      course: 'Psychology',
      year: '1st Year',
      status: 'inactive',
      joinDate: '2023-10-05',
      lastActive: '2024-01-15'
    },
    {
      id: '5',
      name: 'Emily Wilson',
      email: 'emily.w@university.edu',
      course: 'Nursing',
      year: '3rd Year',
      status: 'active',
      joinDate: '2023-08-22',
      lastActive: '2024-02-01'
    }
  ];

  const filteredUsers = users.filter(user => {
    const matchesSearch = user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.course.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesFilter = statusFilter === 'all' || user.status === statusFilter;
    return matchesSearch && matchesFilter;
  });

  const getStatusColor = (status: string) => {
    const colors = {
      active: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
      pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
      inactive: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
    };
    return colors[status as keyof typeof colors];
  };

  const handleApprove = (userId: string) => {
    console.log('Approving user:', userId);
    // Approval logic would go here
  };

  const handleDeactivate = (userId: string) => {
    console.log('Deactivating user:', userId);
    // Deactivation logic would go here
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Manage Users</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Manage scholar accounts, approvals, and access permissions
          </p>
        </div>
        <div className="flex items-center space-x-3 mt-4 lg:mt-0">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input
              type="text"
              placeholder="Search users..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10 pr-4 py-2 w-64 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                         rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         transition-all duration-200"
            />
          </div>
          <select
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value as any)}
            className="px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                       rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       transition-all duration-200"
          >
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>

      {/* Users Table */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div className="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white">
            Scholar Accounts ({filteredUsers.length})
          </h2>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-200 dark:border-gray-700">
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Scholar</th>
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Course & Year</th>
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Join Date</th>
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Last Active</th>
                <th className="text-left py-4 px-6 text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200 dark:divide-gray-700">
              {filteredUsers.map((user) => (
                <tr key={user.id} className="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                  <td className="py-4 px-6">
                    <div>
                      <div className="font-medium text-gray-900 dark:text-white">{user.name}</div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">{user.email}</div>
                    </div>
                  </td>
                  <td className="py-4 px-6">
                    <div className="text-gray-900 dark:text-white">{user.course}</div>
                    <div className="text-sm text-gray-500 dark:text-gray-400">{user.year}</div>
                  </td>
                  <td className="py-4 px-6">
                    <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(user.status)}`}>
                      {user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                    </span>
                  </td>
                  <td className="py-4 px-6 text-gray-900 dark:text-white">
                    {new Date(user.joinDate).toLocaleDateString()}
                  </td>
                  <td className="py-4 px-6 text-gray-900 dark:text-white">
                    {new Date(user.lastActive).toLocaleDateString()}
                  </td>
                  <td className="py-4 px-6">
                    <div className="flex items-center space-x-2">
                      {user.status === 'pending' && (
                        <button
                          onClick={() => handleApprove(user.id)}
                          className="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg 
                                   transition-colors"
                          title="Approve User"
                        >
                          <UserCheck className="w-4 h-4" />
                        </button>
                      )}
                      {user.status === 'active' && (
                        <button
                          onClick={() => handleDeactivate(user.id)}
                          className="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg 
                                   transition-colors"
                          title="Deactivate User"
                        >
                          <UserX className="w-4 h-4" />
                        </button>
                      )}
                      <button className="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg 
                                       transition-colors"
                              title="Edit User">
                        <Edit3 className="w-4 h-4" />
                      </button>
                      <button className="p-2 text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg 
                                       transition-colors"
                              title="More Options">
                        <MoreVertical className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Empty State */}
        {filteredUsers.length === 0 && (
          <div className="text-center py-12">
            <UserX className="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
              No users found
            </h3>
            <p className="text-gray-500 dark:text-gray-400">
              {searchTerm 
                ? `No users match "${searchTerm}"`
                : 'There are no users with the selected status'
              }
            </p>
          </div>
        )}
      </div>

      {/* Bulk Actions */}
      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <select className="px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                           rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option>Bulk Actions</option>
            <option>Approve Selected</option>
            <option>Deactivate Selected</option>
            <option>Export Selected</option>
          </select>
          <button className="px-6 py-2 bg-blue-600 text-white rounded-xl font-medium 
                           hover:bg-blue-700 transition-colors">
            Apply
          </button>
        </div>
        
        <div className="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
          <span>Showing {filteredUsers.length} of {users.length} users</span>
          <div className="flex items-center space-x-2">
            <button className="p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 
                             dark:hover:bg-gray-800 transition-colors">
              Previous
            </button>
            <button className="p-2 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 
                             dark:hover:bg-gray-800 transition-colors">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};