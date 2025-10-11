import React, { useState } from 'react';
import { Search, Filter, Calendar } from 'lucide-react';
import { AnnouncementCard, Announcement } from '../components/AnnouncementCard';

export const Announcements: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [filter, setFilter] = useState<'all' | 'info' | 'urgent' | 'success'>('all');

  // Mock announcements data
  const announcements: Announcement[] = [
    {
      id: '1',
      title: 'Scholarship Renewal Deadline Approaching',
      content: 'This is a reminder that all scholarship renewal applications must be submitted by February 28, 2024. Late submissions will not be accepted. Please ensure all required documents are complete.',
      author: 'Scholarship Committee',
      category: 'urgent',
      timestamp: new Date('2024-02-01'),
      isRead: false
    },
    {
      id: '2',
      title: 'New Online Learning Resources Available',
      content: 'We are excited to announce that new digital resources have been added to your scholar portal. This includes access to premium research databases, e-books, and online courses.',
      author: 'Academic Support Team',
      category: 'info',
      timestamp: new Date('2024-01-28'),
      isRead: true
    },
    {
      id: '3',
      title: 'Community Outreach Program Success',
      content: 'Our recent community service initiative helped over 200 families in the local community. Thank you to all scholars who participated and made this event successful!',
      author: 'Community Outreach',
      category: 'success',
      timestamp: new Date('2024-01-25'),
      isRead: true
    },
    {
      id: '4',
      title: 'Maintenance Schedule Notice',
      content: 'The scholar portal will undergo scheduled maintenance on February 5th from 2:00 AM to 4:00 AM. The system may be unavailable during this time.',
      author: 'Technical Team',
      category: 'info',
      timestamp: new Date('2024-01-20'),
      isRead: true
    },
    {
      id: '5',
      title: 'Leadership Workshop Completion Certificates',
      content: 'Certificates for the completed Leadership Development Workshop are now available for download. Participants can access their certificates through the Certificates section.',
      author: 'Program Coordinator',
      category: 'success',
      timestamp: new Date('2024-01-18'),
      isRead: true
    }
  ];

  const filteredAnnouncements = announcements.filter(announcement => {
    const matchesSearch =
      announcement.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
      announcement.content.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesFilter = filter === 'all' || announcement.category === filter;
    return matchesSearch && matchesFilter;
  });

  const filterButtons = [
    { key: 'all' as const, label: 'All Announcements', count: announcements.length },
    { key: 'urgent' as const, label: 'Urgent', count: announcements.filter(a => a.category === 'urgent').length },
    { key: 'info' as const, label: 'Information', count: announcements.filter(a => a.category === 'info').length },
    { key: 'success' as const, label: 'Success Stories', count: announcements.filter(a => a.category === 'success').length },
  ];

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Announcements</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Stay updated with the latest news and important updates from the foundation
          </p>
        </div>
        <div className="flex items-center space-x-3 mt-4 lg:mt-0">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input
              type="text"
              placeholder="Search announcements..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10 pr-4 py-2 w-64 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                         rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                         transition-all duration-200"
            />
          </div>
          <button className="p-2 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 
                           dark:hover:bg-gray-800 transition-colors">
            <Filter className="w-5 h-5" />
          </button>
        </div>
      </div>

      {/* Filter Tabs */}
      <div className="flex flex-wrap gap-2">
        {filterButtons.map((button) => (
          <button
            key={button.key}
            onClick={() => setFilter(button.key)}
            className={`px-4 py-2 rounded-xl font-medium transition-all duration-200 ${
              filter === button.key
                ? 'bg-blue-600 text-white shadow-lg'
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700'
            }`}
          >
            {button.label}
            <span
              className={`ml-2 px-2 py-1 rounded-full text-xs ${
                filter === button.key
                  ? 'bg-white/20 text-white'
                  : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'
              }`}
            >
              {button.count}
            </span>
          </button>
        ))}
      </div>

      {/* Announcements Grid */}
      <div className="grid grid-cols-1 gap-6">
        {filteredAnnouncements.length > 0 ? (
          filteredAnnouncements.map((announcement) => (
            <AnnouncementCard
              key={announcement.id}
              announcement={announcement}
            />
          ))
        ) : (
          <div className="text-center py-12">
            <Calendar className="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
              No announcements found
            </h3>
            <p className="text-gray-500 dark:text-gray-400">
              {searchTerm
                ? `No announcements match "${searchTerm}"`
                : 'There are no announcements in this category'}
            </p>
          </div>
        )}
      </div>

      {/* Load More */}
      {filteredAnnouncements.length > 0 && (
        <div className="text-center">
          <button className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                           text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-medium
                           hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-400 
                           dark:hover:text-blue-400 transition-all duration-200 hover:scale-105">
            Load More Announcements
          </button>
        </div>
      )}
    </div>
  );
};
