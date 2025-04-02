import React from 'react';
import { Link } from 'react-router-dom';

interface HeaderProps {
  title?: string;
  className?: string;
}

const Header: React.FC<HeaderProps> = ({ 
  title = 'SWStarter',
  className = ''
}) => {
  return (
    <header className={`app-header ${className}`}>
      <div className="app-title">
        <Link to="/">{title}</Link>
      </div>
    </header>
  );
};

export default Header; 