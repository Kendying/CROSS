import React from 'react';
import { Calendar, User, AlertCircle, Info, CheckCircle } from 'lucide-react';

export interface Announcement {
  id: string;
  title: string;
  content: string;
  author: string;
  category: 'info' | 'urgent' | 'success';
  timestamp: Date;
  isRead: boolean;
}

interface AnnouncementCardProps {
  announcement: Announcement;
}

export const AnnouncementCard: React.FC<AnnouncementCardProps> = ({ announcement }) => {
  const categoryIcons = {
    info: Info,
    urgent: AlertCircle,
    success: CheckCircle,
  };

  const categoryColors = {
    info: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
    urgent: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
    success: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
  };

  const Icon = categoryIcons[announcement.category];

  return (
    <div className={`
      bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 
      p-6 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] 
      animate-fadeIn ${!announcement.isRead ? 'ring-2 ring-blue-500/20' : ''}
    `}>
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className={`p-2 rounded-xl ${categoryColors[announcement.category]}`}>
            <Icon className="w-4 h-4" />
          </div>
          <h3 className="font-semibold text-gray-900 dark:text-white">
            {announcement.title}
          </h3>
        </div>
        {!announcement.isRead && (
          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
        )}
      </div>

      <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
        {announcement.content}
      </p>

      <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
        <div className="flex items-center space-x-4">
          <div className="flex items-center space-x-1">
            <User className="w-4 h-4" />
            <span>{announcement.author}</span>
          </div>
          <div className="flex items-center space-x-1">
            <Calendar className="w-4 h-4" />
            <span>{announcement.timestamp.toLocaleDateString()}</span>
          </div>
        </div>
        <span className={`px-2 py-1 rounded-full text-xs font-medium ${categoryColors[announcement.category]}`}>
          {announcement.category}
        </span>
      </div>
    </div>
  );
};