import React from 'react';
import { DollarSign, Calendar, Download, Clock, CheckCircle } from 'lucide-react';

export const FinancialAid: React.FC = () => {
  // Mock data
  const financialAidData = {
    totalDisbursed: 50000,
    upcomingPayments: [
      {
        id: '1',
        type: 'stipend',
        amount: 5000,
        disbursementDate: '2024-03-01',
        status: 'pending',
        semester: '2nd Semester 2023-2024'
      },
      {
        id: '2',
        type: 'book_allowance',
        amount: 3000,
        disbursementDate: '2024-03-15',
        status: 'pending',
        semester: '2nd Semester 2023-2024'
      }
    ],
    paymentHistory: [
      {
        id: '1',
        type: 'stipend',
        amount: 5000,
        disbursementDate: '2024-01-15',
        status: 'disbursed',
        semester: '2nd Semester 2023-2024'
      },
      {
        id: '2',
        type: 'tuition_fee',
        amount: 15000,
        disbursementDate: '2024-01-10',
        status: 'disbursed',
        semester: '2nd Semester 2023-2024'
      },
      {
        id: '3',
        type: 'stipend',
        amount: 5000,
        disbursementDate: '2023-11-15',
        status: 'disbursed',
        semester: '1st Semester 2023-2024'
      }
    ]
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'disbursed':
        return <CheckCircle className="w-5 h-5 text-green-500" />;
      case 'pending':
        return <Clock className="w-5 h-5 text-yellow-500" />;
      default:
        return <Clock className="w-5 h-5 text-gray-400" />;
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'disbursed':
        return 'text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-300';
      case 'pending':
        return 'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300';
      default:
        return 'text-gray-700 bg-gray-100 dark:bg-gray-900/30 dark:text-gray-300';
    }
  };

  const getTypeLabel = (type: string) => {
    switch (type) {
      case 'stipend':
        return 'Monthly Stipend';
      case 'tuition_fee':
        return 'Tuition Fee';
      case 'book_allowance':
        return 'Book Allowance';
      default:
        return type;
    }
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Financial Aid</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Track your scholarship stipends and allowances
        </p>
      </div>

      {/* Total Disbursed */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-sm text-gray-600 dark:text-gray-400">Total Disbursed This Academic Year</p>
            <p className="text-3xl font-bold text-gray-900 dark:text-white">
              ₱{financialAidData.totalDisbursed.toLocaleString()}
            </p>
          </div>
          <div className="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
            <DollarSign className="w-8 h-8 text-blue-600 dark:text-blue-400" />
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Upcoming Payments */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Upcoming Payments</h3>
          
          <div className="space-y-4">
            {financialAidData.upcomingPayments.map((payment) => (
              <div key={payment.id} className="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div className="flex items-center space-x-3">
                  {getStatusIcon(payment.status)}
                  <div>
                    <p className="font-medium text-gray-900 dark:text-white">
                      {getTypeLabel(payment.type)}
                    </p>
                    <p className="text-sm text-gray-500 dark:text-gray-400">
                      {new Date(payment.disbursementDate).toLocaleDateString()}
                    </p>
                  </div>
                </div>
                <div className="text-right">
                  <p className="font-bold text-gray-900 dark:text-white">
                    ₱{payment.amount.toLocaleString()}
                  </p>
                  <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(payment.status)}`}>
                    {payment.status}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Payment History */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-lg font-bold text-gray-900 dark:text-white">Payment History</h3>
            <button className="flex items-center space-x-2 text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
              <Download className="w-4 h-4" />
              <span>Export</span>
            </button>
          </div>

          <div className="space-y-4">
            {financialAidData.paymentHistory.map((payment) => (
              <div key={payment.id} className="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div className="flex items-center space-x-3">
                  {getStatusIcon(payment.status)}
                  <div>
                    <p className="font-medium text-gray-900 dark:text-white">
                      {getTypeLabel(payment.type)}
                    </p>
                    <p className="text-sm text-gray-500 dark:text-gray-400">
                      {payment.semester}
                    </p>
                  </div>
                </div>
                <div className="text-right">
                  <p className="font-bold text-gray-900 dark:text-white">
                    ₱{payment.amount.toLocaleString()}
                  </p>
                  <p className="text-sm text-gray-500 dark:text-gray-400">
                    {new Date(payment.disbursementDate).toLocaleDateString()}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};