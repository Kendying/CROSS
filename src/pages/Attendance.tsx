import React from 'react';
import { CheckCircle, XCircle, Clock, TrendingUp, Calendar } from 'lucide-react';

export const Attendance: React.FC = () => {
  // Mock attendance data
  const attendanceStats = {
    overall: 94,
    present: 45,
    absent: 3,
    late: 2
  };

  const recentSessions = [
    {
      id: 1,
      title: 'Leadership Development Workshop',
      date: '2024-02-10',
      status: 'present',
      time: '2:00 PM - 4:00 PM'
    },
    {
      id: 2,
      title: 'Research Methodology Fundamentals',
      date: '2024-02-03',
      status: 'present',
      time: '10:00 AM - 12:00 PM'
    },
    {
      id: 3,
      title: 'Career Planning Session',
      date: '2024-01-27',
      status: 'late',
      time: '3:00 PM - 5:00 PM'
    },
    {
      id: 4,
      title: 'Academic Writing Workshop',
      date: '2024-01-20',
      status: 'absent',
      time: '1:00 PM - 3:00 PM'
    },
    {
      id: 5,
      title: 'Team Building Activity',
      date: '2024-01-13',
      status: 'present',
      time: '9:00 AM - 12:00 PM'
    }
  ];

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'present':
        return <CheckCircle className="w-5 h-5 text-green-500" />;
      case 'absent':
        return <XCircle className="w-5 h-5 text-red-500" />;
      case 'late':
        return <Clock className="w-5 h-5 text-yellow-500" />;
      default:
        return <Clock className="w-5 h-5 text-gray-500" />;
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'present':
        return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
      case 'absent':
        return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
      case 'late':
        return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
      default:
        return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300';
    }
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Attendance Tracking</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Monitor your seminar attendance and participation records
        </p>
      </div>

      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {/* Overall Attendance */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-sm font-medium text-gray-600 dark:text-gray-400">Overall Attendance</h3>
            <TrendingUp className="w-5 h-5 text-green-500" />
          </div>
          <div className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            {attendanceStats.overall}%
          </div>
          <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div 
              className="bg-green-500 h-2 rounded-full transition-all duration-1000"
              style={{ width: `${attendanceStats.overall}%` }}
            ></div>
          </div>
        </div>

        {/* Present */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <CheckCircle className="w-8 h-8 text-green-500" />
            <div>
              <div className="text-2xl font-bold text-gray-900 dark:text-white">
                {attendanceStats.present}
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">Present</div>
            </div>
          </div>
        </div>

        {/* Absent */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <XCircle className="w-8 h-8 text-red-500" />
            <div>
              <div className="text-2xl font-bold text-gray-900 dark:text-white">
                {attendanceStats.absent}
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">Absent</div>
            </div>
          </div>
        </div>

        {/* Late */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <Clock className="w-8 h-8 text-yellow-500" />
            <div>
              <div className="text-2xl font-bold text-gray-900 dark:text-white">
                {attendanceStats.late}
              </div>
              <div className="text-sm text-gray-600 dark:text-gray-400">Late</div>
            </div>
          </div>
        </div>
      </div>

      {/* Attendance History */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div className="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white">Recent Sessions</h2>
        </div>
        <div className="divide-y divide-gray-200 dark:divide-gray-700">
          {recentSessions.map((session) => (
            <div key={session.id} className="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4">
                  {getStatusIcon(session.status)}
                  <div>
                    <h3 className="font-medium text-gray-900 dark:text-white">
                      {session.title}
                    </h3>
                    <div className="flex items-center space-x-4 mt-1">
                      <div className="flex items-center space-x-1 text-sm text-gray-500 dark:text-gray-400">
                        <Calendar className="w-4 h-4" />
                        <span>{new Date(session.date).toLocaleDateString()}</span>
                      </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        {session.time}
                      </div>
                    </div>
                  </div>
                </div>
                <span className={`px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(session.status)}`}>
                  {session.status.charAt(0).toUpperCase() + session.status.slice(1)}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Progress Chart Placeholder */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Attendance Trend</h3>
        <div className="h-64 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center">
          <div className="text-center">
            <TrendingUp className="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-2" />
            <p className="text-gray-500 dark:text-gray-400">Attendance chart visualization</p>
            <p className="text-sm text-gray-400 dark:text-gray-500">
              Monthly attendance trends and patterns would be displayed here
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};