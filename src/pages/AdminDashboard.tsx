import { 
  Users, 
  Calendar, 
  BarChart3, 
  Megaphone, 
  Activity,
  Plus,
  Edit3,
  MoreVertical
} from 'lucide-react';


export const AdminDashboard: React.FC = () => {
  // Mock data for admin dashboard
  const adminStats = [
    { icon: Users, label: 'Total Scholars', value: '247', change: '+12', color: 'blue' },
    { icon: Calendar, label: 'Active Seminars', value: '8', change: '+2', color: 'green' },
    { icon: BarChart3, label: 'Attendance Rate', value: '89%', change: '+5%', color: 'purple' },
    { icon: Megaphone, label: 'Pending Announcements', value: '3', change: '-1', color: 'orange' },
  ];

  const recentActivities = [
    { action: 'New scholar registration', user: 'Maria Cruz', time: '2 hours ago', type: 'registration' },
    { action: 'Seminar attendance submitted', user: 'John Smith', time: '4 hours ago', type: 'attendance' },
    { action: 'Certificate generated', user: 'Anna Reyes', time: '1 day ago', type: 'certificate' },
    { action: 'Profile updated', user: 'Michael Tan', time: '1 day ago', type: 'update' },
    { action: 'Scholarship approved', user: 'Sarah Lim', time: '2 days ago', type: 'approval' },
  ];

  const pendingApprovals = [
    { name: 'Robert Johnson', course: 'Computer Science', date: '2024-02-01', status: 'pending' },
    { name: 'Lisa Garcia', course: 'Business Admin', date: '2024-02-01', status: 'pending' },
    { name: 'David Chen', course: 'Engineering', date: '2024-01-30', status: 'pending' },
  ];

  const getActivityColor = (type: string) => {
    const colors = {
      registration: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
      attendance: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
      certificate: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
      update: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
      approval: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
    };
    return colors[type as keyof typeof colors] || 'bg-gray-100 text-gray-700';
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Manage scholars, seminars, and foundation activities
          </p>
        </div>
        <div className="flex space-x-3 mt-4 lg:mt-0">
          <button className="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold 
                           hover:bg-blue-700 transition-all duration-200 hover:scale-105 
                           shadow-lg hover:shadow-xl flex items-center space-x-2">
            <Plus className="w-5 h-5" />
            <span>New Seminar</span>
          </button>
          <button className="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 
                           px-6 py-3 rounded-xl font-semibold hover:border-blue-500 hover:text-blue-600 
                           dark:hover:border-blue-400 dark:hover:text-blue-400 transition-all duration-200 
                           hover:scale-105 flex items-center space-x-2">
            <Megaphone className="w-5 h-5" />
            <span>New Announcement</span>
          </button>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {adminStats.map((stat, index) => (
          <div
            key={stat.label}
            className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 
                     transition-all duration-300 hover:shadow-lg hover:scale-105"
            style={{ animationDelay: `${index * 100}ms` }}
          >
            <div className="flex items-center justify-between mb-4">
              <div className={`p-3 bg-${stat.color}-100 dark:bg-${stat.color}-900/30 rounded-xl`}>
                <stat.icon className={`w-6 h-6 text-${stat.color}-600 dark:text-${stat.color}-400`} />
              </div>
              <span className={`text-sm font-medium text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full`}>
                {stat.change}
              </span>
            </div>
            <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-1">
              {stat.value}
            </h3>
            <p className="text-gray-600 dark:text-gray-400 text-sm">
              {stat.label}
            </p>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Recent Activity */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white flex items-center space-x-2">
              <Activity className="w-5 h-5" />
              <span>Recent Activity</span>
            </h2>
            <button className="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 
                             text-sm font-medium">
              View All
            </button>
          </div>
          <div className="space-y-4">
            {recentActivities.map((activity, index) => (
              <div
                key={index}
                className="flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-700 
                         hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
              >
                <div className="flex items-center space-x-3">
                  <div className={`px-2 py-1 rounded-full text-xs font-medium ${getActivityColor(activity.type)}`}>
                    {activity.type}
                  </div>
                  <div>
                    <p className="font-medium text-gray-900 dark:text-white">
                      {activity.action}
                    </p>
                    <p className="text-sm text-gray-500 dark:text-gray-400">
                      by {activity.user}
                    </p>
                  </div>
                </div>
                <span className="text-sm text-gray-500 dark:text-gray-400">
                  {activity.time}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* Pending Approvals */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-bold text-gray-900 dark:text-white flex items-center space-x-2">
              <Users className="w-5 h-5" />
              <span>Pending Approvals</span>
            </h2>
            <span className="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 px-2 py-1 rounded-full text-sm">
              {pendingApprovals.length} pending
            </span>
          </div>
          <div className="space-y-4">
            {pendingApprovals.map((approval, index) => (
              <div
                key={index}
                className="flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-700 
                         hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
              >
                <div>
                  <p className="font-medium text-gray-900 dark:text-white">
                    {approval.name}
                  </p>
                  <p className="text-sm text-gray-500 dark:text-gray-400">
                    {approval.course} • Applied {approval.date}
                  </p>
                </div>
                <div className="flex items-center space-x-2">
                  <button className="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg 
                                   transition-colors">
                    <Edit3 className="w-4 h-4" />
                  </button>
                  <button className="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg 
                                   transition-colors">
                    <Users className="w-4 h-4" />
                  </button>
                  <button className="p-2 text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg 
                                   transition-colors">
                    <MoreVertical className="w-4 h-4" />
                  </button>
                </div>
              </div>
            ))}
          </div>
          <button className="w-full mt-4 p-3 border border-dashed border-gray-300 dark:border-gray-600 
                           text-gray-500 dark:text-gray-400 rounded-xl hover:border-blue-500 hover:text-blue-600 
                           dark:hover:border-blue-400 dark:hover:text-blue-400 transition-colors">
            View All Applications
          </button>
        </div>
      </div>

      {/* Quick Management */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-6 text-white">
          <Users className="w-8 h-8 mb-4" />
          <h3 className="text-xl font-bold mb-2">Manage Scholars</h3>
          <p className="text-blue-100 mb-4">
            View and manage all scholar accounts and applications
          </p>
          <button className="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold 
                           hover:bg-gray-100 transition-colors">
            Open Panel
          </button>
        </div>

        <div className="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white">
          <Calendar className="w-8 h-8 mb-4" />
          <h3 className="text-xl font-bold mb-2">Seminar Management</h3>
          <p className="text-green-100 mb-4">
            Create and manage seminars, workshops, and events
          </p>
          <button className="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold 
                           hover:bg-gray-100 transition-colors">
            Manage Events
          </button>
        </div>

        <div className="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white">
          <BarChart3 className="w-8 h-8 mb-4" />
          <h3 className="text-xl font-bold mb-2">Analytics & Reports</h3>
          <p className="text-orange-100 mb-4">
            View detailed analytics and generate reports
          </p>
          <button className="bg-white text-orange-600 px-4 py-2 rounded-lg font-semibold 
                           hover:bg-gray-100 transition-colors">
            View Analytics
          </button>
        </div>
      </div>
    </div>
  );
};