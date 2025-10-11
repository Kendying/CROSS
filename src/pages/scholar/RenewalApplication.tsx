import React, { useState } from 'react';
import { CheckCircle, Clock, AlertCircle, FileText, Calendar } from 'lucide-react';

export const RenewalApplication: React.FC = () => {
  const [renewalStatus, setRenewalStatus] = useState<'not_started' | 'in_progress' | 'submitted' | 'approved'>('not_started');

  const renewalData = {
    currentStatus: renewalStatus,
    deadline: '2024-03-31',
    requirements: [
      { id: '1', name: 'Updated Grades Transcript', completed: false },
      { id: '2', name: 'Renewal Application Form', completed: false },
      { id: '3', name: 'Personal Statement', completed: false },
      { id: '4', name: 'Faculty Recommendation', completed: false },
    ],
    timeline: [
      { step: 1, name: 'Application Start', date: '2024-02-01', completed: true },
      { step: 2, name: 'Document Submission', date: null, completed: false },
      { step: 3, name: 'Review Process', date: null, completed: false },
      { step: 4, name: 'Approval', date: null, completed: false },
    ]
  };

  const handleStartApplication = () => {
    setRenewalStatus('in_progress');
  };

  const handleSubmitApplication = () => {
    setRenewalStatus('submitted');
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Scholarship Renewal</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Renew your scholarship for the next academic year
        </p>
      </div>

      {/* Status Card */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-bold text-gray-900 dark:text-white">Renewal Status</h3>
          <div className="flex items-center space-x-2 text-sm">
            <Calendar className="w-4 h-4 text-gray-400" />
            <span className="text-gray-600 dark:text-gray-400">
              Deadline: {new Date(renewalData.deadline).toLocaleDateString()}
            </span>
          </div>
        </div>

        <div className="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
          <AlertCircle className="w-5 h-5 text-blue-600 dark:text-blue-400" />
          <div>
            <p className="font-medium text-blue-700 dark:text-blue-300">
              {renewalData.currentStatus === 'not_started' && 'Renewal application not started'}
              {renewalData.currentStatus === 'in_progress' && 'Renewal application in progress'}
              {renewalData.currentStatus === 'submitted' && 'Renewal application submitted'}
              {renewalData.currentStatus === 'approved' && 'Renewal approved'}
            </p>
            <p className="text-sm text-blue-600 dark:text-blue-400">
              {renewalData.currentStatus === 'not_started' && 'Start your renewal application before the deadline'}
              {renewalData.currentStatus === 'in_progress' && 'Complete all requirements and submit your application'}
              {renewalData.currentStatus === 'submitted' && 'Your application is under review'}
              {renewalData.currentStatus === 'approved' && 'Your scholarship has been renewed'}
            </p>
          </div>
        </div>

        {renewalData.currentStatus === 'not_started' && (
          <button
            onClick={handleStartApplication}
            className="w-full mt-4 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
          >
            Start Renewal Application
          </button>
        )}
      </div>

      {/* Requirements */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Renewal Requirements</h3>
        
        <div className="space-y-3">
          {renewalData.requirements.map((req) => (
            <div key={req.id} className="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
              <div className="flex items-center space-x-3">
                {req.completed ? (
                  <CheckCircle className="w-5 h-5 text-green-500" />
                ) : (
                  <Clock className="w-5 h-5 text-gray-400" />
                )}
                <span className="text-gray-700 dark:text-gray-300">{req.name}</span>
              </div>
              {req.completed ? (
                <span className="text-sm text-green-600 dark:text-green-400 font-medium">Completed</span>
              ) : (
                <button className="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                  Upload
                </button>
              )}
            </div>
          ))}
        </div>

        {renewalData.currentStatus === 'in_progress' && (
          <button
            onClick={handleSubmitApplication}
            className="w-full mt-6 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors"
          >
            Submit Renewal Application
          </button>
        )}
      </div>

      {/* Timeline */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Application Timeline</h3>
        
        <div className="space-y-4">
          {renewalData.timeline.map((step) => (
            <div key={step.step} className="flex items-center space-x-4">
              <div className={`flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${
                step.completed 
                  ? 'bg-green-500 text-white' 
                  : 'bg-gray-200 dark:bg-gray-700 text-gray-400'
              }`}>
                {step.completed ? (
                  <CheckCircle className="w-4 h-4" />
                ) : (
                  <FileText className="w-4 h-4" />
                )}
              </div>
              <div className="flex-1">
                <p className={`font-medium ${
                  step.completed ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'
                }`}>
                  {step.name}
                </p>
                {step.date && (
                  <p className="text-sm text-gray-500 dark:text-gray-400">
                    {new Date(step.date).toLocaleDateString()}
                  </p>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};