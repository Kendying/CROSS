import React from 'react';
import { TrendingUp, BookOpen, Award, Calendar, Download } from 'lucide-react';

export const AcademicProgress: React.FC = () => {
  // Mock data
  const academicData = {
    currentGPA: 3.8,
    targetGPA: 3.5,
    semester: '2nd Semester 2023-2024',
    grades: [
      { subject: 'Computer Science 101', grade: 1.25, units: 3, status: 'passed' },
      { subject: 'Mathematics 101', grade: 1.5, units: 3, status: 'passed' },
      { subject: 'Research Methods', grade: 1.75, units: 3, status: 'passed' },
      { subject: 'Ethics', grade: 2.0, units: 2, status: 'passed' },
    ],
    requirements: [
      { id: '1', name: 'Maintain 2.5 GPA', completed: true },
      { id: '2', name: 'Complete 12 units per semester', completed: true },
      { id: '3', name: 'No failing grades', completed: true },
      { id: '4', name: 'Submit grades on time', completed: true },
    ],
    academicHistory: [
      { semester: '1st Sem 2023-2024', gpa: 3.7, status: 'completed' },
      { semester: '2nd Sem 2022-2023', gpa: 3.6, status: 'completed' },
      { semester: '1st Sem 2022-2023', gpa: 3.5, status: 'completed' },
    ]
  };

  const getGradeColor = (grade: number) => {
    if (grade <= 1.5) return 'text-green-600 dark:text-green-400';
    if (grade <= 2.5) return 'text-blue-600 dark:text-blue-400';
    if (grade <= 3.0) return 'text-yellow-600 dark:text-yellow-400';
    return 'text-red-600 dark:text-red-400';
  };

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Academic Progress</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          Track your academic performance and requirements
        </p>
      </div>

      {/* GPA Overview */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <TrendingUp className="w-8 h-8 text-blue-600 dark:text-blue-400" />
            <div>
              <p className="text-sm text-gray-600 dark:text-gray-400">Current GPA</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{academicData.currentGPA}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <Award className="w-8 h-8 text-green-600 dark:text-green-400" />
            <div>
              <p className="text-sm text-gray-600 dark:text-gray-400">Target GPA</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{academicData.targetGPA}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center space-x-3">
            <BookOpen className="w-8 h-8 text-purple-600 dark:text-purple-400" />
            <div>
              <p className="text-sm text-gray-600 dark:text-gray-400">Current Semester</p>
              <p className="text-lg font-bold text-gray-900 dark:text-white">{academicData.semester}</p>
            </div>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Current Grades */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-lg font-bold text-gray-900 dark:text-white">Current Grades</h3>
            <button className="flex items-center space-x-2 text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
              <Download className="w-4 h-4" />
              <span>Export</span>
            </button>
          </div>

          <div className="space-y-4">
            {academicData.grades.map((course, index) => (
              <div key={index} className="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div>
                  <p className="font-medium text-gray-900 dark:text-white">{course.subject}</p>
                  <p className="text-sm text-gray-500 dark:text-gray-400">{course.units} units</p>
                </div>
                <div className="text-right">
                  <p className={`text-lg font-bold ${getGradeColor(course.grade)}`}>
                    {course.grade}
                  </p>
                  <p className="text-sm text-gray-500 dark:text-gray-400 capitalize">{course.status}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Academic Requirements */}
        <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
          <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Scholarship Requirements</h3>
          
          <div className="space-y-3">
            {academicData.requirements.map((req) => (
              <div key={req.id} className="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                <span className="text-gray-700 dark:text-gray-300">{req.name}</span>
                {req.completed ? (
                  <div className="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                    <span className="text-white text-sm">✓</span>
                  </div>
                ) : (
                  <div className="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                )}
              </div>
            ))}
          </div>

          <div className="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <div className="flex items-center space-x-2">
              <Award className="w-5 h-5 text-green-600 dark:text-green-400" />
              <span className="text-sm text-green-700 dark:text-green-300 font-medium">
                All academic requirements are met!
              </span>
            </div>
          </div>
        </div>
      </div>

      {/* Academic History */}
      <div className="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-6">Academic History</h3>
        
        <div className="space-y-4">
          {academicData.academicHistory.map((semester, index) => (
            <div key={index} className="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
              <div className="flex items-center space-x-3">
                <Calendar className="w-5 h-5 text-gray-400" />
                <span className="font-medium text-gray-900 dark:text-white">{semester.semester}</span>
              </div>
              <div className="text-right">
                <p className="text-lg font-bold text-gray-900 dark:text-white">GPA: {semester.gpa}</p>
                <p className="text-sm text-gray-500 dark:text-gray-400 capitalize">{semester.status}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};