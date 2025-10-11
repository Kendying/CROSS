export interface Document {
  id: string;
  type: string;
  name: string;
  status: 'pending' | 'approved' | 'rejected';
  required: boolean;
}

export interface ApplicationProgressProps {
  currentStep: number;
  status: string;
}

export interface DocumentUploadProps {
  documents: Document[];
  onUpload: (documentId: string, file: File) => void;
}