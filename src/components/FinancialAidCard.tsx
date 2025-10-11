import React from 'react';
import { DollarSign, Calendar, Download, Clock, CheckCircle } from 'lucide-react';

interface FinancialAid {
  id: string;
  type: 'stipend' | 'tuition' | 'books' | 'special_grant';
  amount: number;
  disbursementDate: string;
  status: 'pending' | 'approved' | 'disbursed' | 'cancelled';
  semester: string;
  description?: string;
}

interface FinancialAidCardProps {
  financialAid: FinancialAid;
  onDownload?: (id: string) => void;
}

export const FinancialAidCard: React.FC<FinancialAidCardProps> = ({ financialAid, onDownload }) => {
  const typeConfig = {
    stipend: { color: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', label: 'Monthly Stipend' },
    tuition: { color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300', label: 'Tuition Fee' },
    books: { color: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300', label: 'Book Allowance' },
    special_grant: { color: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300', label: 'Special Grant' },
  };

  const statusConfig = {
    pending: { icon: Clock, color: 'text-yellow-600 dark:text-yellow-400', label: 'Pending' },
    approved: { icon: CheckCircle, color: 'text-green-600 dark:text-green-400', label: 'Approved' },
    disbursed: { icon: CheckCircle, color: 'text-blue-600 dark:text-blue-400', label: 'Disbursed' },
    cancelled: { icon: Clock, color: 'text-red-600 dark:text-red-400', label: 'Cancelled' },
  };

  const StatusIcon = statusConfig[financialAid.status].icon;

  return (
    <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-lg">
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
            <DollarSign className="w-5 h-5 text-blue-600 dark:text-blue-400" />
          </div>
          <div>
            <h3 className="font-semibold text-gray-900 dark:text-white">
              {typeConfig[financialAid.type].label}
            </h3>
            <p className="text-sm text-gray-500 dark:text-gray-400">
              {financialAid.semester}
            </p>
          </div>
        </div>
        <span className={`px-3 py-1 rounded-full text-xs font-medium ${typeConfig[financialAid.type].color}`}>
          {typeConfig[financialAid.type].label}
        </span>
      </div>

      <div className="mb-4">
        <p className="text-2xl font-bold text-gray-900 dark:text-white">
          ₱{financialAid.amount.toLocaleString()}
        </p>
        {financialAid.description && (
          <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {financialAid.description}
          </p>
        )}
      </div>

      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
          <div className="flex items-center space-x-1">
            <Calendar className="w-4 h-4" />
            <span>{new Date(financialAid.disbursementDate).toLocaleDateString()}</span>
          </div>
          <div className="flex items-center space-x-1">
            <StatusIcon className={`w-4 h-4 ${statusConfig[financialAid.status].color}`} />
            <span>{statusConfig[financialAid.status].label}</span>
          </div>
        </div>

        {financialAid.status === 'disbursed' && onDownload && (
          <button
            onClick={() => onDownload(financialAid.id)}
            className="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            title="Download Receipt"
          >
            <Download className="w-4 h-4" />
          </button>
        )}
      </div>
    </div>
  );
};