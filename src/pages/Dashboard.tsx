import React from 'react';
import { 
  Calendar, 
  Users, 
  Award, 
  TrendingUp, 
  Bell,
  Download,
  Eye
} from 'lucide-react';
import { SeminarCard, Seminar } from '../components/SeminarCard';
import { AnnouncementCard, Announcement } from '../components/AnnouncementCard';

export const Dashboard: React.FC = () => {
  // Mock data
  const stats = [
    { icon: Calendar, label: 'Upcoming Seminars', value: '3', change: '+1' },
    { icon: Award, label: 'Completed Courses', value: '12', change: '+2' },
    { icon: Users, label: 'Peer Connections', value: '47', change: '+5' },
    { icon: TrendingUp, label: 'Attendance Rate', value: '94%', change: '+3%' },
  ];

  const upcomingSeminars: Seminar[] = [
    {
      id: '1',
      title: 'Leadership Development Workshop',
      description: 'Learn essential leadership skills and team management strategies for academic and professional success.',
      date: new Date('2024-02-15'),
      time: '2:00 PM - 4:00 PM',
      location: 'Main Auditorium',
      speaker: 'Dr. Maria Santos',
      maxParticipants: 50,
      currentParticipants: 42,
      category: 'Professional Development',
      status: 'upcoming'
    },
    {
      id: '2',
      title: 'Research Methodology Fundamentals',
      description: 'Comprehensive guide to academic research methods, data collection, and paper writing techniques.',
      date: new Date('2024-02-20'),
      time: '10:00 AM - 12:00 PM',
      location: 'Library Conference Room',
      speaker: 'Prof. James Wilson',
      maxParticipants: 30,
      currentParticipants: 28,
      category: 'Academic Skills',
      status: 'upcoming'
    }
  ];

  const announcements: Announcement[] = [
    {
      id: '1',
      title: 'Scholarship Renewal Deadline',
      content: 'Reminder: Scholarship renewal applications are due by February 28, 2024. Please submit all required documents to maintain your scholarship status.',
      author: 'Scholarship Committee',
      category: 'urgent',
      timestamp: new Date('2024-02-01'),
      isRead: false
    },
    {
      id: '2',
      title: 'New Learning Resources Available',
      content: 'We have added new online learning resources and research databases to the scholar portal. Check the resources section for more information.',
      author: 'Academic Support',
      category: 'info',
      timestamp: new Date('2024-01-28'),
      isRead: true
    },
    {
      id: '3',
      title: 'Community Service Opportunity',
      content: 'Join our upcoming community outreach program on February 25th. This is a great opportunity to fulfill service hours and make a positive impact.',
      author: 'Community Outreach',
      category: 'success',
      timestamp: new Date('2024-01-25'),
      isRead: true
    }
  ];

  const handleRegisterSeminar = (seminarId: string) => {
    console.log('Registering for seminar:', seminarId);
    // Registration logic would go here
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Welcome Section */}
      <div className="bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl p-8 text-white">
        <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold mb-2">Welcome back, Sarah!</h1>
            <p className="text-blue-100 text-lg">
              Here's what's happening with your scholarship journey today.
            </p>
          </div>
          <div className="mt-4 lg:mt-0 flex items-center space-x-2 bg-white/20 rounded-2xl px-4 py-2">
            <Bell className="w-5 h-5" />
            <span>3 new notifications</span>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, index) => (
          <div
            key={stat.label}
            className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 
                     transition-all duration-300 hover:shadow-lg hover:scale-105"
            style={{ animationDelay: `${index * 100}ms` }}
          >
            <div className="flex items-center justify-between mb-4">
              <div className="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <stat.icon className="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
              <span className="text-sm font-medium text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">
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
        {/* Upcoming Seminars */}
        <div>
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
              Upcoming Seminars
            </h2>
            <button className="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 
                             font-medium transition-colors">
              View All
            </button>
          </div>
          <div className="space-y-6">
            {upcomingSeminars.map((seminar) => (
              <SeminarCard
                key={seminar.id}
                seminar={seminar}
                onRegister={handleRegisterSeminar}
              />
            ))}
          </div>
        </div>

        {/* Recent Announcements */}
        <div>
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
              Recent Announcements
            </h2>
            <button className="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 
                             font-medium transition-colors">
              View All
            </button>
          </div>
          <div className="space-y-4">
            {announcements.map((announcement) => (
              <AnnouncementCard
                key={announcement.id}
                announcement={announcement}
              />
            ))}
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
          Quick Actions
        </h3>
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <button className="flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 
                           rounded-xl hover:border-blue-300 dark:hover:border-blue-700 hover:bg-blue-50 
                           dark:hover:bg-blue-900/20 transition-all duration-200 group">
            <Download className="w-5 h-5 text-blue-600 dark:text-blue-400" />
            <span className="font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 
                           dark:group-hover:text-blue-400">
              Download Certificate
            </span>
          </button>
          <button className="flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 
                           rounded-xl hover:border-green-300 dark:hover:border-green-700 hover:bg-green-50 
                           dark:hover:bg-green-900/20 transition-all duration-200 group">
            <Eye className="w-5 h-5 text-green-600 dark:text-green-400" />
            <span className="font-medium text-gray-700 dark:text-gray-300 group-hover:text-green-600 
                           dark:group-hover:text-green-400">
              View Progress
            </span>
          </button>
          <button className="flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 
                           rounded-xl hover:border-purple-300 dark:hover:border-purple-700 hover:bg-purple-50 
                           dark:hover:bg-purple-900/20 transition-all duration-200 group">
            <Calendar className="w-5 h-5 text-purple-600 dark:text-purple-400" />
            <span className="font-medium text-gray-700 dark:text-gray-300 group-hover:text-purple-600 
                           dark:group-hover:text-purple-400">
              Schedule Meeting
            </span>
          </button>
        </div>
      </div>
    </div>
  );
};