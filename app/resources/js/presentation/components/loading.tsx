import React from 'react';

interface LoadingProps {
  message?: string;
  className?: string;
}

const Loading: React.FC<LoadingProps> = ({
  message = 'Loading...',
  className = ''
}) => {
  return (
    <div className={`loading-container ${className}`}>
      <div className="loading-spinner"></div>
      <p className="loading-message">{message}</p>
    </div>
  );
};

export default Loading; 