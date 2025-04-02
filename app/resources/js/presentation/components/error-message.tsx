import React from 'react';
import { Link } from 'react-router-dom';

interface ErrorMessageProps {
  title?: string;
  message: string;
  showBackButton?: boolean;
  backUrl?: string;
  backText?: string;
  className?: string;
}

const ErrorMessage: React.FC<ErrorMessageProps> = ({
  title = 'Error',
  message,
  showBackButton = true,
  backUrl = '/',
  backText = 'BACK TO SEARCH',
  className = ''
}) => {
  return (
    <div className={`error-message ${className}`}>
      <h2 className="error-title">{title}</h2>
      <p className="error-text">{message}</p>
      
      {showBackButton && (
        <div className="error-actions">
          <Link to={backUrl} className="button button-primary">
            {backText}
          </Link>
        </div>
      )}
    </div>
  );
};

export default ErrorMessage; 