import React from 'react';
import { CheckCircle, Clock, AlertCircle, FileText, Users, Award } from 'lucide-react';
import { ApplicationProgressProps } from '../types/application';

export const ApplicationProgress: React.FC<ApplicationProgressProps> = ({ currentStep, status }) => {
  const steps = [
    { icon: FileText, label: 'Application Submitted', description: 'Your application has been received' },
    { icon: Users, label: 'Screening', description: 'Under initial review' },
    { icon: Users, label: 'Interview', description: 'Scheduled for interview' },
    { icon: Award, label: 'Approval', description: 'Final decision pending' },
    { icon: CheckCircle, label: 'Completed', description: 'Scholarship awarded' },
  ];

  return (
    <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
      <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Application Progress</h3>
      
      <div className="space-y-4">
        {steps.map((step, index) => {
          const StepIcon = step.icon;
          const isCompleted = index < currentStep;
          const isCurrent = index === currentStep;
          const isUpcoming = index > currentStep;

          return (
            <div key={step.label} className="flex items-center space-x-4">
              <div className={`
                flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                ${isCompleted ? 'bg-green-500 text-white' : ''}
                ${isCurrent ? 'bg-blue-500 text-white' : ''}
                ${isUpcoming ? 'bg-gray-200 dark:bg-gray-700 text-gray-400' : ''}
              `}>
                <StepIcon className="w-5 h-5" />
              </div>
              
              <div className="flex-1">
                <p className={`font-medium ${
                  isCompleted || isCurrent ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'
                }`}>
                  {step.label}
                </p>
                <p className="text-sm text-gray-500 dark:text-gray-400">{step.description}</p>
              </div>
              
              <div>
                {isCompleted && <CheckCircle className="w-5 h-5 text-green-500" />}
                {isCurrent && <Clock className="w-5 h-5 text-blue-500" />}
                {isUpcoming && <div className="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600" />}
              </div>
            </div>
          );
        })}
      </div>

      <div className="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <div className="flex items-center space-x-2">
          <AlertCircle className="w-5 h-5 text-blue-600 dark:text-blue-400" />
          <p className="text-sm text-blue-700 dark:text-blue-300">
            Current Status: <span className="font-semibold">{status}</span>
          </p>
        </div>
      </div>
    </div>
  );
};