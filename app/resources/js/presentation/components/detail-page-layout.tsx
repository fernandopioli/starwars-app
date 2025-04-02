import React, { ReactNode } from 'react';
import Loading from './loading';
import ErrorMessage from './error-message';

interface DetailPageLayoutProps {
  children: ReactNode;
  isLoading?: boolean;
  error?: string | null;
  title?: string;
  loadingMessage?: string;
  className?: string;
}

const DetailPageLayout: React.FC<DetailPageLayoutProps> = ({
  children,
  isLoading = false,
  error = null,
  title = 'Not found',
  loadingMessage = 'Loading...',
  className = ''
}) => {
  return (
    <div className={`detail-container ${className}`}>
      <div className="detail-content">
        {isLoading && (
          <Loading message={loadingMessage} />
        )}
        
        {error && (
          <ErrorMessage 
            message={error} 
            title="Error" 
          />
        )}
        
        {!isLoading && !error && children}
      </div>
    </div>
  );
};

export default DetailPageLayout; 