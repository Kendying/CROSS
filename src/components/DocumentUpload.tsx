import React, { useState } from 'react';
import { Upload, CheckCircle, XCircle, FileText } from 'lucide-react';
import { Document } from '../types/application';

interface DocumentUploadProps {
  documents: Document[];
  onUpload: (documentId: string, file: File) => void;
}

export const DocumentUpload: React.FC<DocumentUploadProps> = ({ documents, onUpload }) => {
  const [dragOver, setDragOver] = useState(false);

  const handleFileSelect = (documentId: string, event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      onUpload(documentId, file);
    }
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setDragOver(true);
  };

  const handleDragLeave = () => {
    setDragOver(false);
  };

  const handleDrop = (documentId: string, e: React.DragEvent) => {
    e.preventDefault();
    setDragOver(false);
    const file = e.dataTransfer.files[0];
    if (file) {
      onUpload(documentId, file);
    }
  };

  const getStatusIcon = (status: 'pending' | 'approved' | 'rejected') => {
    switch (status) {
      case 'approved':
        return <CheckCircle className="w-5 h-5 text-green-500" />;
      case 'rejected':
        return <XCircle className="w-5 h-5 text-red-500" />;
      default:
        return <FileText className="w-5 h-5 text-gray-400" />;
    }
  };

  const getStatusColor = (status: 'pending' | 'approved' | 'rejected') => {
    switch (status) {
      case 'approved':
        return 'text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-300';
      case 'rejected':
        return 'text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-300';
      default:
        return 'text-gray-700 bg-gray-100 dark:bg-gray-900/30 dark:text-gray-300';
    }
  };

  return (
    <div className="space-y-4">
      {documents.map((doc) => (
        <div
          key={doc.id}
          className={`bg-white dark:bg-gray-800 border-2 ${
            dragOver ? 'border-blue-500' : 'border-gray-200 dark:border-gray-700'
          } rounded-xl p-4 transition-all duration-200`}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onDrop={(e) => handleDrop(doc.id, e)}
        >
          <div className="flex items-center justify-between mb-3">
            <div className="flex items-center space-x-3">
              {getStatusIcon(doc.status)}
              <div>
                <h4 className="font-medium text-gray-900 dark:text-white">
                  {doc.name}
                </h4>
                <p className="text-sm text-gray-500 dark:text-gray-400">
                  {doc.required ? 'Required' : 'Optional'}
                </p>
              </div>
            </div>
            <span className={`px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(doc.status)}`}>
              {doc.status.charAt(0).toUpperCase() + doc.status.slice(1)}
            </span>
          </div>

          <label className={`
            flex flex-col items-center justify-center w-full p-6 border-2 border-dashed rounded-lg cursor-pointer
            transition-all duration-200
            ${
              dragOver
                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                : 'border-gray-300 dark:border-gray-600 hover:border-blue-500 hover:bg-gray-50 dark:hover:bg-gray-700/50'
            }
          `}>
            <Upload className="w-8 h-8 text-gray-400 mb-2" />
            <p className="text-sm text-gray-600 dark:text-gray-400 text-center">
              <span className="font-medium text-blue-600 dark:text-blue-400">Click to upload</span> or drag and drop
            </p>
            <p className="text-xs text-gray-500 dark:text-gray-500 mt-1">
              PDF, JPG, PNG up to 10MB
            </p>
            <input
              type="file"
              className="hidden"
              accept=".pdf,.jpg,.jpeg,.png"
              onChange={(e) => handleFileSelect(doc.id, e)}
            />
          </label>
        </div>
      ))}
    </div>
  );
};