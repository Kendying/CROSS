import React from 'react';
import { ApplicationProgress } from '../../components/ApplicationProgress';
import { DocumentUpload } from '../../components/DocumentUpload';
import { Calendar, Clock, UserCheck, AlertCircle, CheckCircle } from 'lucide-react';

export const ApplicationStatus: React.FC = () => {
  // Mock data - in real app, this would come from API
  const applicationData = {
    currentStep: 2,
    status: 'Interview Scheduled',
    nextInterview: '2024-02-20T14:00:00',
    documents: [
      { id: '1', type: 'birth_certificate', name: 'Birth Certificate', status: 'approved' as const, required: true },
      { id: '2', type: 'transcript', name: 'Academic Transcript', status: 'approved' as const, required: true },
      { id: '3', type: 'income_cert', name: 'Income Certificate', status: 'pending' as const, required: true },
      { id: '4', type: 'recommendation', name: 'Recommendation Letter', status: 'pending' as const, required: true },
    ],
    requirements: [
      { id: '1', name: 'Application Form', completed: true },
      { id: '2', name: 'Document Submission', completed: false },
      { id: '3', name: 'Interview', completed: false },
      { id: '4', name: 'Final Approval', completed: false },
    ]
  };

  const handleDocumentUpload = (documentId: string, file: File) => {
    console.log('Uploading document:', documentId, file.name);
    // Upload logic would go here
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Application Status</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Track your scholarship application progress
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Progress Tracker */}
        <div>
          <ApplicationProgress 
            currentStep={applicationData.currentStep} 
            status={applicationData.status} 
          />
        </div>

        {/* Interview Info */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Interview Information</h3>
          
          {applicationData.nextInterview ? (
            <div className="space-y-4">
              <div className="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <Calendar className="w-5 h-5 text-blue-600 dark:text-blue-400" />
                <div>
                  <p className="font-medium text-blue-700 dark:text-blue-300">Scheduled Interview</p>
                  <p className="text-sm text-blue-600 dark:text-blue-400">
                    {new Date(applicationData.nextInterview).toLocaleString()}
                  </p>
                </div>
              </div>
              
              <div className="flex items-center space-x-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <Clock className="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                <div>
                  <p className="font-medium text-yellow-700 dark:text-yellow-300">Preparation Tips</p>
                  <p className="text-sm text-yellow-600 dark:text-yellow-400">
                    Bring your valid ID and academic records
                  </p>
                </div>
              </div>
            </div>
          ) : (
            <div className="text-center py-8">
              <UserCheck className="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
              <p className="text-gray-500 dark:text-gray-400">No interview scheduled yet</p>
            </div>
          )}
        </div>
      </div>

      {/* Document Upload Section */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div className="flex items-center justify-between mb-6">
          <h3 className="text-lg font-bold text-gray-900 dark:text-white">Required Documents</h3>
          <div className="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <AlertCircle className="w-4 h-4" />
            <span>All documents must be verified before approval</span>
          </div>
        </div>
        
        <DocumentUpload 
          documents={applicationData.documents} 
          onUpload={handleDocumentUpload}
        />
      </div>

      {/* Requirements Checklist */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Application Requirements</h3>
        <div className="space-y-3">
          {applicationData.requirements.map((req) => (
            <div key={req.id} className="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
              <span className="text-gray-700 dark:text-gray-300">{req.name}</span>
              {req.completed ? (
                <CheckCircle className="w-5 h-5 text-green-500" />
              ) : (
                <Clock className="w-5 h-5 text-gray-400" />
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};