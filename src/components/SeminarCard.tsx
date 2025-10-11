import React from 'react';
import { Calendar, MapPin, Users, Clock, BookOpen } from 'lucide-react';

export interface Seminar {
  id: string;
  title: string;
  description: string;
  date: Date;
  time: string;
  location: string;
  speaker: string;
  maxParticipants: number;
  currentParticipants: number;
  category: string;
  status: 'upcoming' | 'ongoing' | 'completed';
}

interface SeminarCardProps {
  seminar: Seminar;
  onRegister?: (id: string) => void;
  showActions?: boolean;
}

export const SeminarCard: React.FC<SeminarCardProps> = ({ 
  seminar, 
  onRegister, 
  showActions = true 
}) => {
  const statusColors = {
    upcoming: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
    ongoing: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
    completed: 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300',
  };

  const isFull = seminar.currentParticipants >= seminar.maxParticipants;
  const canRegister = showActions && seminar.status === 'upcoming' && !isFull;

  return (
    <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 
                    p-6 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] animate-fadeIn">
      {/* Header */}
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
            <BookOpen className="w-5 h-5 text-blue-600 dark:text-blue-400" />
          </div>
          <div>
            <h3 className="font-semibold text-gray-900 dark:text-white">
              {seminar.title}
            </h3>
            <p className="text-sm text-gray-500 dark:text-gray-400">{seminar.category}</p>
          </div>
        </div>
        <span className={`px-3 py-1 rounded-full text-xs font-medium ${statusColors[seminar.status]}`}>
          {seminar.status}
        </span>
      </div>

      {/* Description */}
      <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
        {seminar.description}
      </p>

      {/* Details */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
          <Calendar className="w-4 h-4" />
          <span>{seminar.date.toLocaleDateString()}</span>
        </div>
        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
          <Clock className="w-4 h-4" />
          <span>{seminar.time}</span>
        </div>
        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
          <MapPin className="w-4 h-4" />
          <span>{seminar.location}</span>
        </div>
        <div className="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
          <Users className="w-4 h-4" />
          <span>
            {seminar.currentParticipants}/{seminar.maxParticipants} participants
            {isFull && <span className="text-red-500 ml-1">(Full)</span>}
          </span>
        </div>
      </div>

      {/* Speaker */}
      <div className="mb-6 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
        <p className="text-sm text-gray-600 dark:text-gray-400">
          <strong className="text-gray-900 dark:text-white">Speaker:</strong> {seminar.speaker}
        </p>
      </div>

      {/* Actions */}
      {showActions && (
        <div className="flex space-x-3">
          {canRegister ? (
            <button
              onClick={() => onRegister?.(seminar.id)}
              className="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-lg 
                         font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200 
                         hover:scale-105 shadow-sm hover:shadow-md"
            >
              Register Now
            </button>
          ) : (
            <button
              disabled
              className="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 py-2 px-4 
                         rounded-lg font-medium cursor-not-allowed"
            >
              {isFull ? 'Fully Booked' : 'Registration Closed'}
            </button>
          )}
          <button className="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 
                           rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            View Details
          </button>
        </div>
      )}
    </div>
  );
};