import React from 'react';
import { CheckCircle, Clock} from 'lucide-react';

interface Requirement {
  id: string;
  name: string;
  completed: boolean;
  description?: string;
  dueDate?: string;
}

interface RequirementChecklistProps {
  requirements: Requirement[];
  title?: string;
}

export const RequirementChecklist: React.FC<RequirementChecklistProps> = ({ 
  requirements, 
  title = "Requirements Checklist" 
}) => {
  const completedCount = requirements.filter(req => req.completed).length;
  const totalCount = requirements.length;
  const progress = (completedCount / totalCount) * 100;

  return (
    <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
      <div className="flex items-center justify-between mb-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white">{title}</h3>
        <div className="flex items-center space-x-2">
          <span className="text-sm text-gray-600 dark:text-gray-400">
            {completedCount}/{totalCount} completed
          </span>
        </div>
      </div>

      {/* Progress Bar */}
      <div className="mb-6">
        <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
          <span>Progress</span>
          <span>{Math.round(progress)}%</span>
        </div>
        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
          <div 
            className="bg-green-500 h-2 rounded-full transition-all duration-500"
            style={{ width: `${progress}%` }}
          ></div>
        </div>
      </div>

      {/* Requirements List */}
      <div className="space-y-4">
        {requirements.map((requirement) => (
          <div key={requirement.id} className="flex items-start space-x-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div className="flex-shrink-0 mt-1">
              {requirement.completed ? (
                <CheckCircle className="w-5 h-5 text-green-500" />
              ) : (
                <Clock className="w-5 h-5 text-gray-400" />
              )}
            </div>
            
            <div className="flex-1">
              <div className="flex items-center justify-between">
                <span className={`font-medium ${
                  requirement.completed 
                    ? 'text-gray-900 dark:text-white' 
                    : 'text-gray-700 dark:text-gray-300'
                }`}>
                  {requirement.name}
                </span>
                {requirement.dueDate && !requirement.completed && (
                  <span className="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded">
                    Due {new Date(requirement.dueDate).toLocaleDateString()}
                  </span>
                )}
              </div>
              
              {requirement.description && (
                <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                  {requirement.description}
                </p>
              )}
            </div>
          </div>
        ))}
      </div>

      {completedCount === totalCount && (
        <div className="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
          <div className="flex items-center space-x-2">
            <CheckCircle className="w-5 h-5 text-green-600 dark:text-green-400" />
            <span className="text-sm text-green-700 dark:text-green-300 font-medium">
              All requirements completed!
            </span>
          </div>
        </div>
      )}
    </div>
  );
};